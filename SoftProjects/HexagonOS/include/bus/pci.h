#ifndef _PCI_H_
#define _PCI_H_

#include "../tools.h"

#define PCI_CLASS_PRIOR_DEFINITION 0x00
#define PCI_CLASS_MASS_STORAGE 0x01
#define PCI_CLASS_NETWORK 0x02
#define PCI_CLASS_DISPLAY 0x03
#define PCI_CLASS_MULTIMEDIA 0x04
#define PCI_CLASS_MEMORY 0x05
#define PCI_CLASS_BRIDGE 0x06
#define PCI_CLASS_COMMUNICATION 0x07
#define PCI_CLASS_SYSTEM 0x08
#define PCI_CLASS_INPUT 0x09
#define PCI_CLASS_DOCKING_STATION 0x0A
#define PCI_CLASS_PROCESSOR 0x0B
#define PCI_CLASS_SERIAL 0x0C
#define PCI_CLASS_WIRELESS 0x0D
#define PCI_CLASS_INTELLIGENT_IO 0x0E
#define PCI_CLASS_SATELLITE 0x0F
#define PCI_CLASS_CRYPTO 0x10
#define PCI_CLASS_ACQUISITION 0x11
#define PCI_CLASS_OTHER 0xFF


struct pci_bar
{
	uint32_t base_address;
	uint8_t prefetchable:1;
	uint8_t type:2;
};

struct pci_command
{
	uint8_t reserved_0:5;
	uint8_t interrupt_disable:1;
	uint8_t fast_btb_enable:1;
	uint8_t serr_enable:1;
	uint8_t reserved_1:1;
	uint8_t parity_error_response:1;
	uint8_t vga_palette_snoop:1;
	uint8_t memory_wai_enable:1;
	uint8_t special_cycles:1;
	uint8_t bus_master:1;
	uint8_t memory_space:1;
	uint8_t io_space:1;
};

struct pci_status
{
	uint8_t detected_parity_error:1;
	uint8_t signaled_system_error:1;
	uint8_t received_master_abort:1;
	uint8_t received_target_abort:1;
	uint8_t signaled_target_abort:1;
	uint8_t devsel_timing:1;
	uint8_t master_data_error:1;
	uint8_t fast_btb_capable:1;
	uint8_t reserved_0:1;
	uint8_t sixtymhz_capable:1;
	uint8_t capabilities_list:1;
	uint8_t interrupt_status:1;
	uint8_t reserved_1:1;
};

struct pci_bist
{
	uint8_t bist_capable:1;
	uint8_t start_bist:1;
	uint8_t reserved:2;
	uint8_t completion_code:4;
};

struct pci_header
{
	uint8_t multiple:1;
	uint8_t type:7;
};

struct pci_device
{
	//Header type == 0x00
	uint16_t device_id;
	uint16_t vendor_id;
	struct pci_status status;
	struct pci_command command;
	uint8_t class_code;
	uint8_t subclass;
	uint8_t prog_if;
	uint8_t revision_id;
 	struct pci_bist bist;
 	uint8_t header_type;
 	uint8_t latency_timer;
 	uint8_t cache_line_size;
 	uint32_t bar0;
 	uint32_t bar1;
 	uint32_t bar2;
 	uint32_t bar3;
 	uint32_t bar4;
 	uint32_t bar5;
 	uint32_t cardbus_pointer;
 	uint16_t subsystem_id;
 	uint16_t subsystem_vendor_id;
 	uint32_t expansion_address;
 	uint16_t reserved_0;
 	uint8_t reserved_1;
 	uint8_t capabilities_pointer;
 	uint32_t reserved_2;
 	uint8_t max_latency;
 	uint8_t min_grant;
 	uint8_t interrupt_pin;
 	uint8_t interrupt_line;

	struct list_head list;
};

// PCI I/O functions
uint32_t k_pci_config_read_int32(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg);
uint16_t k_pci_config_read_int16(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg, uint8_t offset);
uint8_t k_pci_config_read_int8(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg, uint8_t offset);

void k_pci_config_write_int16(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg, uint16_t value);
void k_pci_config_write_int32(uint8_t bus, uint8_t device, uint8_t func, uint8_t reg, uint32_t value);

// Init/Enumerate functions
struct pci_device *k_pci_get_device(uint8_t class_id, uint8_t subclass_id, uint16_t vendor_id, uint16_t device_id);
uint32_t k_pci_get_io_addr(struct pci_device *device, uint8_t number);
uint32_t k_pci_get_irq(struct pci_device *device);

void init_pci();

struct pci_device *devices;

#endif