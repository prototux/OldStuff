#define _KERNEL_INTERRUPTS_C_
#include <kernel.h>
#include <arch.h>
#include <devices.h>

static void install_handler(struct irq *irq, void *handler, enum irq_handler_type type, void *device)
{
	struct irq_handler *new_handler = k_mem_alloc(sizeof(struct irq_handler));

	if (irq->handlers_count == 0)
	{
		irq->handlers = (struct irq_handler*) k_mem_alloc(sizeof(struct irq_handler));
		INIT_LIST_HEAD(&(irq->handlers->list));
	}

	new_handler->function.def = handler;
	new_handler->type = type;
	new_handler->device = device;
	list_add(&(new_handler->list), &(irq->handlers->list));

	irq->handlers_count++;
}

void k_int_add_cpu_handler(uint8_t irq, void (*handler)(struct registers_t*))
{
	install_handler(&(interrupts[irq]), handler, INT_DEFAULT, 0);
}


void k_int_add_std_handler(uint8_t irq, void (*handler)(struct registers_t*))
{
	install_handler(&(interrupts[32+irq]), handler, INT_DEFAULT, 0);
}

void k_int_add_pci_handler(uint8_t irq, void (*handler)(struct registers_t*), struct pci_device *device)
{
	install_handler(&(interrupts[32+irq]), handler, INT_PCI, device);
}

uintptr_t k_int_exec_handlers(uintptr_t stack)
{
	struct registers_t* r = (struct registers_t*)stack;

	if (interrupts[r->int_number].handlers_count)
	{
		struct list_head *p, *n;
		struct irq_handler *handler;
		list_for_each_safe(p, n, &(interrupts[r->int_number].handlers->list))
		{
			handler = list_entry(p, struct irq_handler, list);
			if (handler->type == INT_PCI)
				handler->function.pci(r, handler->device);
			else
				handler->function.def(r);
		}
	}
	else
		k_log(warning, "No handler for interrupt %d\n", r->int_number);

	// ACK to the PICs
	if(r->int_number >= (32+8))
		k_hard_write_int8(0xA0, 0x20);
	k_hard_write_int8(0x20, 0x20);
	return stack;
}