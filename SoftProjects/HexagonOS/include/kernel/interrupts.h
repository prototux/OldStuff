#ifndef _INTERRUPTS_H_
#define _INTERRUPTS_H_

#include "../bus/pci.h"
#include "../arch/oldpc/interrupts.h"

enum irq_handler_type
{
	INT_DEFAULT,
	INT_PCI
};

struct irq_handler
{
	struct pci_device *device;
	union
	{
		void (*def)(struct registers_t*);
		void (*pci)(struct registers_t*, struct pci_device*);
	} function;

	enum irq_handler_type type;
	struct list_head list;
};

struct irq
{
	uint32_t handlers_count;
	struct irq_handler *handlers;
};

#ifdef _KERNEL_INTERRUPTS_C_
	static struct irq interrupts[48];
#else
	extern struct irq interrupts[];
#endif

void k_int_add_cpu_handler(uint8_t irq, void (*handler)(struct registers_t*));
void k_int_add_std_handler(uint8_t irq, void (*handler)(struct registers_t*));
void k_int_add_pci_handler(uint8_t irq, void (*handler)(struct registers_t*), struct pci_device *device);

#endif