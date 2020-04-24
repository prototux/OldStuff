#ifndef _GDT_H_
#define _GDT_H_

#include "pagination.h"

#define GDTSIZE         0xFF

//Segment descriptor
struct gdtdesc
{
	uint16_t limit_1;
	uint16_t base_1;
	uint8_t base_2;
	uint8_t acces;
	uint8_t limit_2 :4;
	uint8_t other :4;
	uint8_t base_3;
} __attribute__ ((packed));

//GDTR Register
struct gdtr
{
	uint16_t limit;
	uint32_t addr;
} __attribute__ ((packed));

//Task State Segment
struct tss
{
	uint16_t previous_task, __previous_task_unused;
	uint32_t esp0;
	uint16_t ss0, __ss0_unused;
	uint32_t esp1;
	uint16_t ss1, __ss1_unused;
	uint32_t esp2;
	uint16_t ss2, __ss2_unused;
	uint32_t cr3;
	uint32_t eip, eflags, eax, ecx, edx, ebx, esp, ebp, esi, edi;
	uint16_t es, __es_unused;
	uint16_t cs, __cs_unused;
	uint16_t ss, __ss_unused;
	uint16_t ds, __ds_unused;
	uint16_t fs, __fs_unused;
	uint16_t gs, __gs_unused;
	uint16_t ldt_selector, __ldt_sel_unused;
	uint16_t debug_flag, io_map;
} __attribute__ ((packed));

void init_gdt(void);

#ifdef _GDT_C_
	struct gdtdesc kgdt[GDTSIZE];
	struct gdtr kgdtr;
	struct tss default_tss;

	// Private functions
	static void init_gdt_desc(uint32_t, uint32_t, uint8_t, uint8_t, struct gdtdesc*);
#else
	extern struct tss default_tss;
#endif

void init_segmentation();

#endif