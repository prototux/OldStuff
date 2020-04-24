#define _GDT_C_
#include <tools.h>
#include <kernel.h>
#include <arch.h>

// Init a new segment descriptor (desc is the linear address)
static void init_gdt_desc(uint32_t base, uint32_t limit, uint8_t acces, uint8_t other, struct gdtdesc *descriptor)
{
	descriptor->limit_1 = (limit & 0xffff);
	descriptor->base_1 = (base & 0xffff);
	descriptor->base_2 = (base & 0xff0000) >> 16;
	descriptor->acces = acces;
	descriptor->limit_2 = (limit & 0xf0000) >> 16;
	descriptor->other = (other & 0xf);
	descriptor->base_3 = (base & 0xff000000) >> 24;
}

// Init the GDT
void init_segmentation(void)
{
	// Init kernel segment descriptors (NULL, Code, Data and Stack)
	init_gdt_desc(0x0, 0x0, 0x0, 0x0, &kgdt[0]);
	init_gdt_desc(0x0, 0xFFFFF, 0x9B, 0x0D, &kgdt[1]);
	init_gdt_desc(0x0, 0xFFFFF, 0x93, 0x0D, &kgdt[2]);
	init_gdt_desc(0x0, 0x0, 0x97, 0x0D, &kgdt[3]);

	// Init task segment descriptors (Code, Data and Stack)
	init_gdt_desc(0x0, 0xFFFFF, 0xFF, 0x0D, &kgdt[4]);
	init_gdt_desc(0x0, 0xFFFFF, 0xF3, 0x0D, &kgdt[5]);
	init_gdt_desc(0x0, 0x0,     0xF7, 0x0D, &kgdt[6]);

	// Init TSS
	default_tss.debug_flag = 0x00;
	default_tss.io_map = 0x00;
	default_tss.esp0 = 0x1FFF0;
	default_tss.ss0 = 0x18;
	init_gdt_desc((uint32_t) &default_tss, 0x67, 0xE9, 0x00, &kgdt[7]);

	// Init GDTR struct and copy it to memory
	kgdtr.limit = GDTSIZE*8;
	kgdtr.addr = GDTBASE;
	k_mem_copy((uint8_t*) kgdtr.addr, (uint8_t*) kgdt, kgdtr.limit);

	// Loading GDTR register
	asm("lgdtl (kgdtr)");

	// Update segments selectors
	asm("movw $0x10, %ax \n\
         movw %ax, %ds \n\
         movw %ax, %es \n\
         movw %ax, %fs \n\
         movw %ax, %gs \n\
         ljmp $0x08, $next \n\
         next:\n");
}