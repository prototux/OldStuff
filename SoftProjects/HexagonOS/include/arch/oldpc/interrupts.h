#ifndef _IDT_H_
#define _IDT_H_

#include "../../tools.h"
#include "pagination.h"

#define INTGATE  0x8E00
#define TRAPGATE 0xEF00

// IDT descriptor
struct idtdesc
{
    uint16_t offset_1;
    uint16_t select;
    uint16_t type;
    uint16_t offset_2;
} __attribute__ ((packed));

// IDT register
struct idtr
{
	uint16_t limit;
	uint32_t addr;
} __attribute__ ((packed));

// This defines what the stack looks like after an ISR was running
struct registers_t
{
	uint32_t gs, fs, es, ds;
	uint32_t edi, esi, ebp, ebx, edx, ecx, eax;
	uint32_t int_number, error_code;
	uint32_t eip, cs, eflags, useresp, ss;
} __attribute__ ((packed));

#ifdef _INTERRUPTS_C_
	struct idtr kidtr;
	struct idtdesc kidt[IDTSIZE];

	// ISR, CPU errors
	void _asm_irq_0(void);  // Division by zero
	void _asm_irq_1(void);  // Debug
	void _asm_irq_2(void);  // NMI
	void _asm_irq_3(void);  // Breakpoint
	void _asm_irq_4(void);  // Overflow
	void _asm_irq_5(void);  // Out of bounds
	void _asm_irq_6(void);  // Invalid opcode
	void _asm_irq_7(void);  // Device not available
	void _asm_irq_8(void);  // Double fault
	void _asm_irq_9(void);  // Coprocessor segment overrun (legacy shit)
	void _asm_irq_10(void); // Bad TSS
	void _asm_irq_11(void); // Segment not present
	void _asm_irq_12(void); // Stack segment fault
	void _asm_irq_13(void); // General protection fault
	void _asm_irq_14(void); // Page fault
	void _asm_irq_15(void); // RESERVED
	void _asm_irq_16(void); // x87 floating point exception
	void _asm_irq_17(void); // Alignement check
	void _asm_irq_18(void); // Machine check
	void _asm_irq_19(void); // SIMD Exeption
	void _asm_irq_20(void); // Virtualization exception
	void _asm_irq_21(void); // RESERVED
	void _asm_irq_22(void); // RESERVED
	void _asm_irq_23(void); // RESERVED
	void _asm_irq_24(void); // RESERVED
	void _asm_irq_25(void); // RESERVED
	void _asm_irq_26(void); // RESERVED
	void _asm_irq_27(void); // RESERVED
	void _asm_irq_28(void); // RESERVED
	void _asm_irq_29(void); // RESERVED
	void _asm_irq_30(void); // Security exception
	void _asm_irq_31(void); // RESERVED

	// IRQ, hardware interrupts
	void _asm_irq_32(void); // IRQ 0 // PIT (Timer)
	void _asm_irq_33(void); // IRQ 1 // Keyboard
	void _asm_irq_34(void); // IRQ 2 // Cascade (unused)
	void _asm_irq_35(void); // IRQ 3 // COM2
	void _asm_irq_36(void); // IRQ 4 // COM1
	void _asm_irq_37(void); // IRQ 5 // LPT2
	void _asm_irq_38(void); // IRQ 6 // Floppy disk
	void _asm_irq_39(void); // IRQ 7 // LPT1
	void _asm_irq_40(void); // IRQ 8 // CMOS RTC (Clock)
	void _asm_irq_41(void); // IRQ 9 // Free/SCSI/NIC
	void _asm_irq_42(void); // IRQ 10 // Free/SCSI/NIC
	void _asm_irq_43(void); // IRQ 11 // Free/SCSI/NIC
	void _asm_irq_44(void); // IRQ 12 // Mouse
	void _asm_irq_45(void); // IRQ 13 // FPU error
	void _asm_irq_46(void); // IRQ 14 // Primary ATA
	void _asm_irq_47(void); // IRQ 15 // Secondary ATA

	// Syscalls
	void _asm_irq_48(void);

	void (*irqs[49])(void) =
	{
		_asm_irq_0,
		_asm_irq_1,
		_asm_irq_2,
		_asm_irq_3,
		_asm_irq_4,
		_asm_irq_5,
		_asm_irq_6,
		_asm_irq_7,
		_asm_irq_8,
		_asm_irq_9,
		_asm_irq_10,
		_asm_irq_11,
		_asm_irq_12,
		_asm_irq_13,
		_asm_irq_14,
		_asm_irq_15,
		_asm_irq_16,
		_asm_irq_17,
		_asm_irq_18,
		_asm_irq_19,
		_asm_irq_20,
		_asm_irq_21,
		_asm_irq_22,
		_asm_irq_23,
		_asm_irq_24,
		_asm_irq_25,
		_asm_irq_26,
		_asm_irq_27,
		_asm_irq_28,
		_asm_irq_29,
		_asm_irq_30,
		_asm_irq_31,
		_asm_irq_32,
		_asm_irq_33,
		_asm_irq_34,
		_asm_irq_35,
		_asm_irq_36,
		_asm_irq_37,
		_asm_irq_38,
		_asm_irq_39,
		_asm_irq_40,
		_asm_irq_41,
		_asm_irq_42,
		_asm_irq_43,
		_asm_irq_44,
		_asm_irq_45,
		_asm_irq_46,
		_asm_irq_47,
		_asm_irq_48
	};
#endif

void init_interrupts(void);
void init_idt_desc(uint16_t select, uint32_t offset, uint16_t type, struct idtdesc *descriptor);

#endif