#define _PROCESS_C_
#include <kernel.h>
#include <arch.h>
#include <filesystems.h>
#include <executables.h>

int load_task(struct ext2_disk *hd, struct ext2_inode *inode, int argc, char **argv)
{
	struct page *kstack;
	struct process *previous;
	uint32_t stackp;
	char **param, **uparam;
	char *file = 0;
	uint32_t e_entry;
	int pid;
	int i;

	// Get the PID for the new processus
	pid = 1;
	while (p_list[pid].state != 0 && pid++ < MAXPID);
	if (p_list[pid].state != 0)
	{
		k_log(error, "arch/oldpc/paging/pages_heap_remove_page() // No enough slots");
		return 0;
	}

	n_proc++;
	p_list[pid].pid = pid;

	// Arguments
	if (argc)
	{
		param = (char**) k_mem_alloc(sizeof(char*) * (argc+1));
		for (i=0 ; i<argc ; i++)
		{
			param[i] = (char*) k_mem_alloc(strlen(argv[i]) + 1);
			strcpy(param[i], argv[i]);
		}
		param[i] = 0;
	}

	//Init PageDirectory
	p_list[pid].pd = user_heap_create();
	INIT_LIST_HEAD(&p_list[pid].pglist);
	previous = current;
	current = &p_list[pid];
	asm("mov %0, %%eax; mov %%eax, %%cr3"::"m"(p_list[pid].pd->base->p_addr));

	// TO BE REMOVED!
	int pgc;
	for (pgc = 0xfd000000; pgc <= 0xfdffffff; pgc += 0x400)
		user_heap_add_page((char*)pgc, (char*)pgc, 0x03, p_list[pid].pd);

	file = ext2_read_file(hd, inode);
	e_entry = (uint32_t) load_elf(file, &p_list[pid]);
	k_mem_free(file);
	if (e_entry == 0)
	{
		for (i=0 ; i<argc ; i++)
			k_mem_free(param[i]);
		k_mem_free(param);
		current = previous;
		asm("mov %0, %%eax ;mov %%eax, %%cr3"::"m" (current->regs.cr3));
		user_heap_destroy(p_list[pid].pd);
		return 0;
	}

	// Create user stack and put argv on it
	stackp = USER_STACK - 16;
	if (argc)
	{
		uparam = (char**) k_mem_alloc(sizeof(char*) * argc);
		for (i=0 ; i<argc ; i++)
		{
			stackp -= (strlen(param[i]) + 1);
			strcpy((char*) stackp, param[i]);
			uparam[i] = (char*) stackp;
		}

		stackp &= 0xFFFFFFF0;
		stackp -= sizeof(char*);
		*((char**) stackp) = 0;

		for (i=argc-1 ; i>=0 ; i--)
		{
			stackp -= sizeof(char*);
			*((char**) stackp) = uparam[i];
		}

		stackp -= sizeof(char*);	/* argv */
		*((char**) stackp) = (char*) (stackp + 4);
		stackp -= sizeof(char*);	/* argc */
		*((int*) stackp) = argc;
		stackp -= sizeof(char*);

		for (i=0 ; i<argc ; i++)
			k_mem_free(param[i]);
		k_mem_free(param);
		k_mem_free(uparam);
	}

	// Create kernel stack
	kstack = pages_heap_add_page();

 	// Init the processus structure
	p_list[pid].pid = pid;
	p_list[pid].regs.ss = 0x33;
	p_list[pid].regs.esp = stackp;
	p_list[pid].regs.eflags = 0x0;
	p_list[pid].regs.cs = 0x23;
	p_list[pid].regs.eip = e_entry;
	p_list[pid].regs.ds = 0x2B;
	p_list[pid].regs.es = 0x2B;
	p_list[pid].regs.fs = 0x2B;
	p_list[pid].regs.gs = 0x2B;
	p_list[pid].regs.cr3 = (uint32_t) p_list[pid].pd->base->p_addr;
	p_list[pid].kstack.ss0 = 0x18;
	p_list[pid].kstack.esp0 = (uint32_t) kstack->v_addr + PAGESIZE - 16;
	p_list[pid].regs.eax = 0;
	p_list[pid].regs.ecx = 0;
	p_list[pid].regs.edx = 0;
	p_list[pid].regs.ebx = 0;
	p_list[pid].regs.ebp = 0;
	p_list[pid].regs.esi = 0;
	p_list[pid].regs.edi = 0;
	p_list[pid].b_heap = (char*) ((uint32_t) p_list[pid].e_bss & 0xFFFFF000) + PAGESIZE;
	p_list[pid].e_heap = p_list[pid].b_heap;
	p_list[pid].pwd = previous->pwd;
	p_list[pid].fd = 0;
	if (previous->state != 0)
		p_list[pid].parent = previous;
	else
		p_list[pid].parent = &p_list[0];

	INIT_LIST_HEAD(&p_list[pid].child);

	if (previous->state != 0)
		list_add(&p_list[pid].sibling, &previous->child);
	else
		list_add(&p_list[pid].sibling, &p_list[0].child);

	p_list[pid].signal = 0;
	for(i=0 ; i<32 ; i++)
		p_list[pid].sigfn[i] = (char*) SIG_DFL;
	p_list[pid].status = 0;

	p_list[pid].state = 1;
	current = previous;
	asm("mov %0, %%eax ;mov %%eax, %%cr3":: "m"(current->regs.cr3));
	return pid;
}