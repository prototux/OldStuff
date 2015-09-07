#include <tools.h>
#include <kernel.h>
#include <arch.h>

void switch_to_task(int n, int mode)
{
	uint32_t kesp, eflags;
	uint16_t kss, ss, cs;
	int sig;

	current = &p_list[n];

	// Handle signals
	if ((sig = dequeue_signal(current->signal)))
		handle_signal(sig);

	default_tss.ss0 = current->kstack.ss0;
	default_tss.esp0 = current->kstack.esp0;

	ss = current->regs.ss;
	cs = current->regs.cs;
	eflags = (current->regs.eflags | 0x200) & 0xFFFFBFFF;

	if (mode == USERMODE)
	{
		kss = current->kstack.ss0;
		kesp = current->kstack.esp0;
	}
	else
	{
		kss = current->regs.ss;
		kesp = current->regs.esp;
	}
	asm("mov %0, %%ss; \
		mov %1, %%esp; \
		cmpb %[KMODE], %[mode]; \
		je next; \
		push %2; \
		push %3; \
		next: \
		push %4; \
		push %5; \
		push %6; \
		push %7; \
		ljmp $0x08, $do_switch"
		:: \
		"m"(kss), \
		"m"(kesp), \
		"m"(ss), \
		"m"(current->regs.esp), \
		"m"(eflags), \
		"m"(cs), \
		"m"(current->regs.eip), \
		"m"(current), \
		[KMODE] "i"(KERNELMODE), \
		[mode] "g"(mode)
	    );
}

void schedule(void)
{
	uint32_t* stack_ptr;
	struct process *p;
	int i, newpid;

	//Save saved registers pointer to stack_ptr
	asm("mov (%%ebp), %%eax; mov %%eax, %0" : "=m" (stack_ptr):);

	//If there's no loaded process and one is ready... load it
	//or if there's one or no current process, direct return
	//or commute to the next process
	if (current->pid != 0 && n_proc == 1)
		return;
	else if (!n_proc)
		return;
	else
	{
		//Save current process registers
		current->regs.eflags = stack_ptr[16];
		current->regs.cs  = stack_ptr[15];
		current->regs.eip = stack_ptr[14];
		current->regs.eax = stack_ptr[13];
		current->regs.ecx = stack_ptr[12];
		current->regs.edx = stack_ptr[11];
		current->regs.ebx = stack_ptr[10];
		current->regs.ebp = stack_ptr[8];
		current->regs.esi = stack_ptr[7];
		current->regs.edi = stack_ptr[6];
		current->regs.ds = stack_ptr[5];
		current->regs.es = stack_ptr[4];
		current->regs.fs = stack_ptr[3];
		current->regs.gs = stack_ptr[2];
		current->regs.esp = stack_ptr[17];
		current->regs.ss = stack_ptr[18];

		//This clean up the kernel stack
		//default_tss.esp0 = (uint32_t) (stack_ptr + 19);
		if (current->regs.cs != 0x08)
		{
			current->regs.esp = stack_ptr[17];
			current->regs.ss = stack_ptr[18];
		}
		else
		{
			current->regs.esp = stack_ptr[9] + 12;
			current->regs.ss = default_tss.ss0;
		}

		current->kstack.ss0 = default_tss.ss0;
		current->kstack.esp0 = default_tss.esp0;
	}

	newpid = 0;
	for (i = current->pid + 1; i < MAXPID && newpid == 0; i++)
		if (p_list[i].state == 1)
			newpid = i;
	if (!newpid)
		for (i = 1; i < current->pid && newpid == 0; i++)
			if (p_list[i].state == 1)
				newpid = i;

	p = &p_list[newpid];

	// Commute
	if (p->regs.cs != 0x08)
		switch_to_task(p->pid, USERMODE);
	else
		switch_to_task(p->pid, KERNELMODE);
}