#include <tools.h>
#include <arch.h>
#include <kernel.h>
#include <bus.h>

static void pci_enumerate_devices(struct pci_device *devices, uint8_t bus);

static void pci_add_device(struct pci_device *devices, uint8_t bus, uint8_t device, uint8_t function)
{
	struct pci_device *new_device;

	if((k_pci_config_read_int8(bus, device, function, 2, 3) == 0x06) && (k_pci_config_read_int8(bus, device, function, 2, 2) == 0x04))
		pci_enumerate_devices(devices, k_pci_config_read_int16(bus, device, function, 7, 1));
	else
	{
		new_device = (struct pci_device*) k_mem_alloc(sizeof(struct pci_device));

		new_device->vendor_id = k_pci_config_read_int16(bus, device, function, 0, 0);
		new_device->device_id = k_pci_config_read_int16(bus, device, function, 0, 1);
		//new_device->command = k_pci_config_read_int16(bus, device, function, 0, 2);
		//new_device->status = k_pci_config_read_int16(bus, device, function, 0, 3);

		new_device->revision_id = k_pci_config_read_int8(bus, device, function, 2, 0);
		new_device->prog_if = k_pci_config_read_int8(bus, device, function, 2, 1);
		new_device->subclass = k_pci_config_read_int8(bus, device, function, 2, 2);
		new_device->class_code = k_pci_config_read_int8(bus, device, function, 2, 3);

		new_device->cache_line_size = k_pci_config_read_int8(bus, device, function, 3, 0);
		new_device->latency_timer = k_pci_config_read_int8(bus, device, function, 3, 1);
		new_device->header_type = k_pci_config_read_int8(bus, device, function, 3, 2);
		//new_device->bist = k_pci_config_read_int8(bus, device, function, 2, 3);

		new_device->bar0 = k_pci_config_read_int32(bus, device, function, 4);
		new_device->bar1 = k_pci_config_read_int32(bus, device, function, 5);
		new_device->bar2 = k_pci_config_read_int32(bus, device, function, 6);
		new_device->bar3 = k_pci_config_read_int32(bus, device, function, 7);
		new_device->bar4 = k_pci_config_read_int32(bus, device, function, 8);
		new_device->bar5 = k_pci_config_read_int32(bus, device, function, 9);

		new_device->cardbus_pointer = k_pci_config_read_int32(bus, device, function, 10);

		new_device->subsystem_vendor_id = k_pci_config_read_int16(bus, device, function, 11, 0);
		new_device->subsystem_id = k_pci_config_read_int16(bus, device, function, 11, 1);

		new_device->expansion_address = k_pci_config_read_int32(bus, device, function, 12);

		new_device->capabilities_pointer = k_pci_config_read_int8(bus, device, function, 13, 0);

		new_device->interrupt_line = k_pci_config_read_int8(bus, device, function, 15, 0);
		new_device->interrupt_pin = k_pci_config_read_int8(bus, device, function, 15, 1);
		new_device->min_grant = k_pci_config_read_int8(bus, device, function, 15, 2);
		new_device->max_latency = k_pci_config_read_int8(bus, device, function, 15, 3);

		list_add(&(new_device->list), &(devices->list));
	}
}

static void pci_enumerate_functions(struct pci_device *devices, uint8_t bus, uint8_t device)
{
	uint8_t function = 0;

	if(k_pci_config_read_int16(bus, device, function, 0, 0) != 0xFFFF)
	{
		pci_add_device(devices, bus, device, function);
		if((k_pci_config_read_int8(bus, device, function, 3, 2) & 0x80))
			for(function = 1; function < 8; function++)
				if(k_pci_config_read_int16(bus, device, function, 0, 0) != 0xFFFF)
					pci_add_device(devices, bus, device, function);
	}
}

static void pci_enumerate_devices(struct pci_device *devices, uint8_t bus)
{
	uint8_t device = 0;
	for(device = 0; device < 32; device++)
		pci_enumerate_functions(devices, bus, device);
}

static void pci_enumerate_bus(struct pci_device *devices)
{
     uint8_t function;
     uint8_t bus;

     if(!(k_pci_config_read_int8(0, 0, 0, 3, 2) & 0x80))
         pci_enumerate_devices(devices, 0);
     else
         for(function = 0; function < 8; function++)
         {
             if(k_pci_config_read_int16(0, 0, function, 0, 0) != 0xFFFF)
             	break;
             bus = function;
             pci_enumerate_devices(devices, bus);
         }
}

uint32_t k_pci_config_read_int32(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg)
{
	uint32_t address = 0;
	uint32_t lbus  = (uint32_t)bus;
	uint32_t ldevice = (uint32_t)device;
	uint32_t lfunc = (uint32_t)func;
	uint32_t lreg = (uint32_t)reg;
	uint32_t ret = 0;

	// Brew CONFIG_ADDRESS register and send it
	address = (uint32_t)((0x01 << 31) | (lbus << 16) | (ldevice << 11) | (lfunc << 8) | (lreg << 2));
	k_hard_write_int32(0xCF8, address);

	// Read and return the data
	ret = (uint32_t)((k_hard_read_int32(0xCFC)));
	return ret;
}

uint16_t k_pci_config_read_int16(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg, uint8_t offset)
{
	uint32_t address = 0;
	uint32_t lbus  = (uint32_t)bus;
	uint32_t ldevice = (uint32_t)device;
	uint32_t lfunc = (uint32_t)func;
	uint32_t lreg = (uint32_t)reg;
	uint16_t ret = 0;

	// Brew CONFIG_ADDRESS register and send it
	address = (uint32_t)((0x01 << 31) | (lbus << 16) | (ldevice << 11) | (lfunc << 8) | (lreg << 2));
	k_hard_write_int32(0xCF8, address);

	// Read and return the data
	ret = (uint16_t)((k_hard_read_int32(0xCFC) >> (offset * 16)) & 0xffff);
	return ret;
}

uint8_t k_pci_config_read_int8(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg, uint8_t offset)
{
	uint32_t address = 0;
	uint32_t lbus  = (uint32_t)bus;
	uint32_t ldevice = (uint32_t)device;
	uint32_t lfunc = (uint32_t)func;
	uint32_t lreg = (uint32_t)reg;
	uint8_t ret = 0;

	// Brew CONFIG_ADDRESS register and send it
	address = (uint32_t)((0x01 << 31) | (lbus << 16) | (ldevice << 11) | (lfunc << 8) | (lreg << 2));
	k_hard_write_int32(0xCF8, address);

	// Read and return the data
	ret = (uint8_t)((k_hard_read_int32(0xCFC) >> (offset * 8)) & 0xffffff);
	return ret;
}

void k_pci_config_write_int32(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg, uint32_t value)
{
	uint32_t address = 0;
	uint32_t lbus  = (uint32_t)bus;
	uint32_t ldevice = (uint32_t)device;
	uint32_t lfunc = (uint32_t)func;
	uint32_t lreg = (uint32_t)reg;
	//uint8_t ret = 0;

	// Brew CONFIG_ADDRESS register and send it
	address = (uint32_t)((0x01 << 31) | (lbus << 16) | (ldevice << 11) | (lfunc << 8) | (lreg << 2));
	k_hard_write_int32(0xCF8, address);

	// Read and return the data
	k_hard_write_int32(0xCFC, value);
}

void k_pci_config_write_int16(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg, uint16_t value)
{
	uint32_t address = 0;
	uint32_t lbus  = (uint32_t)bus;
	uint32_t ldevice = (uint32_t)device;
	uint32_t lfunc = (uint32_t)func;
	uint32_t lreg = (uint32_t)reg;
	//uint8_t ret = 0;

	// Brew CONFIG_ADDRESS register and send it
	address = (uint32_t)((0x01 << 31) | (lbus << 16) | (ldevice << 11) | (lfunc << 8) | (lreg & 0xFC));
	k_hard_write_int32(0xCF8, address);

	// Write into it
	k_hard_write_int16(0xCFC, value);
}

struct pci_device *k_pci_get_device(uint8_t class_id, uint8_t subclass_id, uint16_t vendor_id, uint16_t device_id)
{
	//k_log("Looking for PCI device: Class:%x Vendor:%x ID:%x", class_id, vendor_id, device_id);
	struct list_head *p, *n;
	struct pci_device *device;
	list_for_each_safe(p, n, &devices->list)
	{
		device = list_entry(p, struct pci_device, list);
		//k_log("Device: Class: %x Sub: %x ID: %x Vendor: %x\n", device->class_code, device->subclass, device->device_id, device->vendor_id);
		if (device->class_code == class_id && device->subclass == subclass_id && device->device_id == device_id && device->vendor_id == vendor_id)
			return device;
	}
	return 0;
}

uint32_t k_pci_get_io_addr(struct pci_device *device, uint8_t number)
{
	switch (number)
	{
		case 0:
			return (device->bar0 & 0xFFFFFFF0);
		break;
		case 1:
			return (device->bar1 & 0xFFFFFFF0);
		break;
		case 2:
			return (device->bar2 & 0xFFFFFFF0);
		break;
		case 3:
			return (device->bar3 & 0xFFFFFFF0);
		break;
		case 4:
			return (device->bar4 & 0xFFFFFFF0);
		break;
		case 5:
			return (device->bar5 & 0xFFFFFFF0);
		break;
	}
	return 0;
}

uint32_t k_pci_get_irq(struct pci_device *device)
{
	return (device->interrupt_line);
}

void init_pci()
{
	// Init PCI devices list
	devices = (struct pci_device*) k_mem_alloc(sizeof(struct pci_device));
	INIT_LIST_HEAD(&(devices->list));

	pci_enumerate_bus(devices);
}