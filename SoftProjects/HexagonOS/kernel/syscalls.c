#include <tools.h>
#include <kernel.h>
#include <arch.h>

void do_syscalls(struct registers_t *registers)
{
    uint32_t sys_num = 0;
    uint32_t *stack_ptr;

    asm("mov %%ebp, %0": "=m"(stack_ptr):);

    if (sys_num == 1)
    {
    	char *message;
        k_int_disable_all();
        asm("mov %%ebx, %0": "=m"(message):);
        k_log(info, "kernel/syscalls/log() // %s", message);
    }
    else if (sys_num == 2)
    {
        int status;

        k_int_disable_all();
        asm("mov %%ebx, %0": "=m"(status):);
        sys_exit(status);
    }
    else if (sys_num == 3)
    {
        char *path;

        k_int_disable_all();
        asm("mov %%ebx, %0": "=m"(path):);
        stack_ptr[14] = sys_open(path);
    }
    else if (sys_num == 4)
    {
        char *u_buf;
        uint32_t fd;
        uint32_t bufsize;
        struct open_file *of;

        asm("mov %%ebx, %0;  \
            mov %%ecx, %1;  \
            mov %%edx, %2": "=m"(fd), "=m"(u_buf), "=m"(bufsize):);

        of = current->fd;
        while (fd)
        {
            of = of->next;
            if (of == 0)
            {
                k_log(error, "syscalls/sys_read() // invalid file descriptor\n");
                stack_ptr[14] = -1;
                k_int_enable_all();
                return;
            }
            fd--;
        }

        if ((of->ptr + bufsize) > of->file->inode->i_size)
            bufsize = of->file->inode->i_size - of->ptr;

        k_mem_copy(u_buf, (char *) (of->file->mmap + of->ptr), bufsize);
        of->ptr += bufsize;

        stack_ptr[14] = bufsize;
    }
    else if (sys_num == 5)
    {
        uint32_t fd;
        struct open_file *of;

        k_int_disable_all();
        asm("mov %%ebx, %0": "=m"(fd):);

        of = current->fd;
        while (fd)
        {
            of = of->next;
            if (of == 0)
            {
                k_log(error, "syscalls/sys_close() // invalid file descriptor\n");
                k_int_enable_all();
                return;
            }
            fd--;
        }
        k_mem_free(of->file->mmap);
        of->file->mmap = 0;
        of->file = 0;
        of->ptr = 0;
    }
    else if (sys_num == 6)
    {
        char *u_buf;

        asm("mov %%ebx, %0": "=m"(u_buf):);

        stack_ptr[14] = sys_console_read(u_buf);
    }
    else if (sys_num == 7)
    {
        char *path;
        struct file *fp;

        k_int_disable_all();
        asm("mov %%ebx, %0": "=m"(path):);

        if (!(fp = path_to_file(path)))
        {
            k_log(error, "syscalls//chdir() // can't chdir to %s\n", path);
            k_int_enable_all();
            return;
        }

        if (!fp->inode)
            fp->inode = ext2_read_inode(fp->disk, fp->inum);

        if (!is_directory(fp))
        {
            k_log(error, "syscalls//chdir() // %s is not a directory\n", path);
            k_int_enable_all();
            return;
        }

        current->pwd = fp;
    }
    else if (sys_num == 8)
    {
        char *u_buf;
        int sz;
        struct file *fp;

        k_int_disable_all();
        asm("mov %%ebx, %0": "=m"(u_buf):);

        if (current->pwd == f_root)
        {
            u_buf[0] = '/';
            u_buf[1] = 0;
            return;
        }

        fp = current->pwd;
        sz = strlen(fp->name) + 1;
        while (fp->parent != f_root)
        {
            fp = fp->parent;
            sz += (strlen(fp->name) + 1);
        }

        fp = current->pwd;
        u_buf[sz] = 0;

        while (sz > 0)
        {
            k_mem_copy(u_buf + sz - strlen(fp->name), fp->name, strlen(fp->name));
            sz -= (strlen(fp->name) + 1);
            u_buf[sz] = '/';
            fp = fp->parent;
        }
    }
    else if (sys_num == 9)
    {
        char *path;
        char **argv;

        asm("mov %%ebx, %0   \n \
            mov %%ecx, %1"
            : "=m"(path), "=m"(argv) :);

        stack_ptr[14] = sys_exec(path, argv);
    }
    else if (sys_num == 10)
    {
        int  size;

        asm("mov %%ebx, %0": "=m"(size):);
        stack_ptr[14] = (uint32_t) sys_sbrk(size);
    }
    else if (sys_num == 11)
    {
        int *status;

        asm("mov %%ebx, %0": "=m"(status):);
        stack_ptr[14] = sys_wait(status);
    }
    else if (sys_num == 12)
    {
        int pid, sig;

        k_int_disable_all();
        asm("mov %%ebx, %0 \n\
            mov %%ecx, %1"
            : "=m"(pid), "=m"(sig) :);

        if (p_list[pid].state > 0)
        {
            set_signal(&p_list[pid].signal, sig);
            stack_ptr[14] = 0;
        }
        else
            stack_ptr[14] = -1;
    }
    else if (sys_num == 13)
    {
        char *fn;
        int sig;

        k_int_disable_all();
        asm("mov %%ebx, %0 \n\
            mov %%ecx, %1"
            : "=m"(sig), "=m"(fn) :);

        if (sig < 32)
            current->sigfn[sig] = fn;

        stack_ptr[14] = 0;
    }
    else if (sys_num == 14)
    {
        k_int_disable_all();
        sys_sigreturn();
    }
    else
        k_log(warning, "syscalls // Unknown syscall\n", 0x04);
    k_int_enable_all();
    return;
}

void sys_exit(int status)
{
    uint16_t kss;
    uint32_t kesp;
    struct list_head *p, *n;
    struct page *pg;
    struct open_file *fd, *fdn;
    struct process *proc;

    if (current->pid == 0)
    {
        k_log(error, "syscalls/exit() // Can't exit kernel!\n");
        return;
    }

    n_proc--;
    current->state = -1;
    current->status = status;

    // Free process memory
    list_for_each_safe(p, n, &current->pglist)
    {
        pg = list_entry(p, struct page, list);
        release_page_frame(pg->p_addr);
        list_del(p);
        k_mem_free(pg);
    }

    // Free Opened files
    if (current->fd)
    {
        fd = current->fd;
        while (fd)
        {
           fd->file->opened--;
           if (fd->file->opened == 0)
           {
               k_mem_free(fd->file->mmap);
               fd->file->mmap = 0;
           }
           fdn = fd->next;
           k_mem_free(fd);
           fd = fdn;
        }
    }

    // Update father status
    if (current->parent->state > 0)
        set_signal(&current->parent->signal, SIGCHLD);
    else
        k_log(warning, "syscalls/sys_exit() // process %d without valid parent\n", current->pid);

    // Update child' parent
    list_for_each_safe(p, n, &current->child)
    {
       proc = list_entry(p, struct process, sibling);
       proc->parent = &p_list[0];
       list_del(p);
       list_add(p, &p_list[0].child);
    }

    // Free the kernel stack
    kss = p_list[0].regs.ss;
    kesp = p_list[0].regs.esp;
    asm("mov %0, %%ss; mov %1, %%esp;"::"m"(kss), "m"(kesp));
    pages_heap_remove_page((char *) ((uint32_t) current->kstack.esp0 & 0xFFFFF000));

    // Free the PageDirectory
    asm("mov %0, %%eax; mov %%eax, %%cr3"::"m"(pd0));
    user_heap_destroy(current->pd);

    switch_to_task(0, KERNELMODE);
}

int sys_exec(char *path, char **argv)
{
    char **ap;
    int argc, pid;
    struct file *fp;

    if (!(fp = path_to_file(path)))
        return -1;

    if (!fp->inode)
        fp->inode = ext2_read_inode(fp->disk, fp->inum);

    ap = argv;
    argc = 0;
    while (*ap++)
        argc++;

    k_int_disable_all();
    pid = load_task(fp->disk, fp->inode, argc, argv);
    k_int_enable_all();
    return pid;
}

int sys_console_read(char *u_buf)
{
    return 0;
}


int sys_open(char *path)
{
    uint32_t fd;
    struct file *fp;
    struct open_file *of;

    if (!(fp = path_to_file(path)))
        return -1;


    fp->opened++;

    if (!fp->inode)
        fp->inode = ext2_read_inode(fp->disk, fp->inum);

    // Read the file
    fp->mmap = ext2_read_file(fp->disk, fp->inode);

    // Seek for a free fd
    fd = 0;
    if (current->fd == 0)
    {
        current->fd = (struct open_file*) k_mem_alloc(sizeof(struct open_file));
        current->fd->file = fp;
        current->fd->ptr = 0;
        current->fd->next = 0;
    }
    else
    {
        of = current->fd;
        while (of->file && of->next)
        {
            of = of->next;
            fd++;
        }

        if (of->file == 0)
        {
            of->file = fp;
            of->ptr = 0;
        }
        else
        {
            of->next = (struct open_file*) k_mem_alloc(sizeof(struct open_file));
            of->next->file = fp;
            of->next->ptr = 0;
            of->next->next = 0;
            fd++;
        }
    }

    return fd;
}


char* sys_sbrk(int size)
{
    char *ret;
    ret = current->e_heap;

    current->e_heap += size;
    return ret;
}

void sys_sigreturn(void)
{
    uint32_t *esp;

    k_int_disable_all();

    asm("mov (%%ebp), %%eax; mov %%eax, %0": "=m"(esp):);
    esp += 17;

    current->kstack.esp0 = esp[17];
    current->regs.ss = esp[16];
    current->regs.esp = esp[15];
    current->regs.eflags = esp[14];
    current->regs.cs = esp[13];
    current->regs.eip = esp[12];
    current->regs.eax = esp[11];
    current->regs.ecx = esp[10];
    current->regs.edx = esp[9];
    current->regs.ebx = esp[8];
    current->regs.ebp = esp[7];
    current->regs.esi = esp[6];
    current->regs.edi = esp[5];
    current->regs.ds = esp[4];
    current->regs.es = esp[3];
    current->regs.fs = esp[2];
    current->regs.gs = esp[1];

    switch_to_task(0, KERNELMODE);
}


int sys_wait(int* status)
{
    int pid;
    struct list_head *p, *n;
    struct process *children;

    while (0 == is_signal(current->signal, SIGCHLD));

    k_int_disable_all();

    // Search for the dead child
    list_for_each_safe(p, n, &current->child)
    {

        children = list_entry(p, struct process, sibling);
        if (children->state == -1)
        {
            pid = children->pid;
            *status = children->status;
            children->state = 0;
            list_del(p);
            clear_signal(&current->signal, SIGCHLD);
            break;
        }
    }

    k_int_enable_all();
    return pid;
}