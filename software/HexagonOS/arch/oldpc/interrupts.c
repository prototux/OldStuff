#define _INTERRUPTS_C_
#include <tools.h>
#include <kernel.h>
#include <arch.h>
#include <devices.h>

void init_idt_desc(uint16_t select, uint32_t offset, uint16_t type, struct idtdesc *descriptor)
{
    descriptor->offset_1 = (offset & 0xffff);
    descriptor->select = select;
    descriptor->type = type;
    descriptor->offset_2 = (offset & 0xffff0000) >> 16;
}

void isr_GP_exc(struct registers_t *r)
{
	k_log(panic, "GP fault\n");
	while (1);
}

void isr_PF_exc(struct registers_t *r)
{
	uint32_t faulting_addr;
	//uint32_t eip;
	struct page *pg;

	asm("mov %%cr2, %0" : "=r" (faulting_addr));

	if (faulting_addr >= USER_OFFSET && faulting_addr < USER_STACK)
	{
		pg = (struct page*) k_mem_alloc(sizeof(struct page));
		pg->p_addr = get_page_frame();
		pg->v_addr = (char*) (faulting_addr & 0xFFFFF000);
		list_add(&pg->list, &current->pglist);
		user_heap_add_page(pg->v_addr, pg->p_addr, PG_USER, current->pd);
	}
	else
	{
		if (current->pid == 0)
		{
			uint32_t *framebuffer = (uint32_t *) VBE_PIXELS;
			int fi = 0;
			for (fi = 0; fi <1024*768; fi++)
				framebuffer[fi] = 0x00252580;

			k_log(panic, "Kernel segfault!\n");

			bool pres = !(r->error_code & 1<<(0)); // Page not present
			bool rw = r->error_code & 1<<(1); // Write operation?
			bool us = r->error_code & 1<<(2); // Processor was in user-mode?
			bool res = r->error_code & 1<<(3); // Overwritten CPU-reserved bits of page entry?
			bool id = r->error_code & 1<<(4); // Caused by an instruction fetch?
			k_log(panic, "Address: %Xh EIP: %Xh\n", faulting_addr, r->eip);

			if (pres)
				k_log(panic, "page not present");
			if (rw)
				k_log(panic, "read-only - write operation");
			if (us)
				k_log(panic, "user-mode");
			if (res)
				k_log(panic, "overwritten CPU-reserved bits of page entry");
			if (id)
				k_log(panic, "caused by an instruction fetch");

			while (1);
		}
		else
		{
			k_log(error, "Segmentation fault PID:%i\n", current->pid);
			sys_exit(1);
		}
	}
}

void scheduler_handler(struct registers_t *r)
{
	schedule();
}

void redraw_handler(struct registers_t *r)
{
	static uint32_t count = 0;
	if (count == 2)
	{
		vbe_redraw();
		count = 0;
	}
	count++;
}


void isr_fault_exc(struct registers_t *r)
{
	k_log(error, "Fault No %d\n", r->int_number);
}


void init_interrupts(void)
{
	int i;

	// PICs init
	k_hard_write_int8(0x20, 0x11);
	k_hard_write_int8(0xA0, 0x11);

	// PIC1 irqs starts at 0x20 (32), PIC2 ones at 0x28 (40)
	k_hard_write_int8(0x21, 0x20);
	k_hard_write_int8(0xA1, 0x28);

	// Set PIC1 as master, PIC2 as slave
	k_hard_write_int8(0x21, 0x04);
	k_hard_write_int8(0xA1, 0x02);

	// Set PICs in x86 mode and enable all interrupts
	k_hard_write_int8(0x21, 0x01);
	k_hard_write_int8(0xA1, 0x01);
	k_hard_write_int8(0x21, 0x00);
	k_hard_write_int8(0xA1, 0x00);

	//Init descriptors
	for (i = 0; i < IDTSIZE; i++)
		init_idt_desc(0x0008, (uint32_t) irqs[i], INTGATE, &kidt[i]);
	init_idt_desc(0x0008, (uint32_t) irqs[48], TRAPGATE, &kidt[48]);

	for (i = 0; i < 32; i++)
		if (i != 13 && i != 14)
			k_int_add_cpu_handler(i, &isr_fault_exc);

	// Install GPF and PF handlers
	k_int_add_cpu_handler(13, &isr_GP_exc);
	k_int_add_cpu_handler(14, &isr_PF_exc);

	// Install clock (scheduler + redraw) handler
	k_int_add_std_handler(0, &scheduler_handler);
	k_int_add_std_handler(0, &ticks_handler);
	k_int_add_std_handler(0, &redraw_handler);

	// Install syscalls handler
	k_int_add_std_handler(48, &do_syscalls);

	//Init IDT struct and copy it to memory
	kidtr.limit = IDTSIZE*8;
	kidtr.addr = IDTBASE;
	k_mem_copy((uint8_t*) kidtr.addr, (uint8_t*) kidt, kidtr.limit);

	//Loading IDTR
	asm("lidtl (kidtr)");
}