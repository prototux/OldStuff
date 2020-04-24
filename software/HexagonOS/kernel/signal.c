#include <tools.h>
#include <kernel.h>

int dequeue_signal(int mask)
{
	int sig;

	if (mask)
	{
		sig = 1;
		while (!(mask & 1))
		{
			mask = mask >> 1;
			sig++;
		}
	}
	else
		sig = 0;

	return sig;
}

int handle_signal(int sig)
{
	uint32_t *esp;

	if (current->sigfn[sig] == (void*) SIG_IGN)
		clear_signal(&current->signal, sig);
	else if (current->sigfn[sig] == (void*) SIG_DFL)
	{
		switch(sig)
		{
			case SIGHUP:
			case SIGINT:
			case SIGQUIT:
				asm("mov %0, %%eax; mov %%eax, %%cr3"::"m"(current->regs.cr3));
				sys_exit(1);
			break;
			case SIGCHLD:
			break;
			default:
				clear_signal(&current->signal, sig);
		}
	}
	else
	{
		// Save registers on user stack
		esp = (uint32_t*) current->regs.esp - 20;

		asm("mov %0, %%eax; mov %%eax, %%cr3"::"m"(current->regs.cr3));

		//call sig_sigreturn
		esp[19] = 0x0030CD00;
		esp[18] = 0x00000EB8;

		// Save registers
		esp[17] = current->kstack.esp0;
		esp[16] = current->regs.ss;
		esp[15] = current->regs.esp;
		esp[14] = current->regs.eflags;
		esp[13] = current->regs.cs;
		esp[12] = current->regs.eip;
		esp[11] = current->regs.eax;
		esp[10] = current->regs.ecx;
		esp[9] = current->regs.edx;
		esp[8] = current->regs.ebx;
		esp[7] = current->regs.ebp;
		esp[6] = current->regs.esi;
		esp[5] = current->regs.edi;
		esp[4] = current->regs.ds;
		esp[3] = current->regs.es;
		esp[2] = current->regs.fs;
		esp[1] = current->regs.gs;

		// Callback address
		esp[0] = (uint32_t)&esp[18];

		// Replace esp and eip to call user defined handler
		current->regs.esp = (uint32_t)esp;
		current->regs.eip = (uint32_t)current->sigfn[sig];

		// Clear signal and set default handler
		current->sigfn[sig] = (void*) SIG_DFL;
		if (sig != SIGCHLD)
			clear_signal(&current->signal, sig);
	}
	return 0;
}

