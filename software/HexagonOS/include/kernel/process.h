#ifndef _PROCESS_H_
#define _PROCESS_H_

#include "../tools.h"
#include "../filesystems/ext2.h"

#define MAXPID	32

struct __attribute__ ((packed)) process
{
	unsigned int pid;

	struct __attribute__ ((packed))
	{
		uint32_t eax, ecx, edx, ebx;
		uint32_t esp, ebp, esi, edi;
		uint32_t eip, eflags;
		uint32_t cs:16, ss:16, ds:16, es:16, fs:16, gs:16;
		uint32_t cr3;
	} regs;

	struct  __attribute__ ((packed))
	{
		uint32_t esp0;
		uint16_t ss0;
	} kstack;

	struct page_directory *pd;
	struct list_head pglist;

	char *b_exec;
	char *e_exec;
	char *b_bss;
	char *e_bss;
	char *b_heap;
	char *e_heap;

	struct process *parent;
	struct list_head child;
	struct list_head sibling;

	struct file *pwd;
	struct open_file *fd;
	uint32_t signal;
	void* sigfn[32];

	int status;

	// -> -1 = zombie, 0 = not runnable, 1 = ready or running, 2 = sleep
	int state;
};

#ifdef _PROCESS_C_
	struct process p_list[MAXPID + 1];
	struct process *current = 0;
	int n_proc = 0;
#else
	extern struct process p_list[];
	extern struct process *current;
	extern int n_proc;
#endif

int load_task(struct ext2_disk *, struct ext2_inode *, int, char **);

#endif