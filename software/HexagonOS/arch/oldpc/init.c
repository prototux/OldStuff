#include <tools.h>
#include <arch.h>
#include <kernel.h>

// Init the timer
void init_pit(int hz)
{
    int divisor = 1193180 / hz;
    k_hard_write_int8(0x43, 0x36);
    k_hard_write_int8(0x40, divisor & 0xFF);
    k_hard_write_int8(0x40, divisor >> 8);
}

void boot_oldpc(struct multiboot_info *mbi)
{
	// segmentation
	init_segmentation();

	// Init TSS
	asm("movw $0x38, %ax \n ltr %ax");

	// Init Pagination
	init_pagination(mbi->high_mem);

	// Init interruptions
	init_interrupts();

	// Init ESP stack pointer to 0x20000
	asm("movw $0x18, %ax \n\
		 movw %ax, %ss \n\
		 movl $0x20000, %esp");

	// Tick every 10 ms
	init_pit(100);
}