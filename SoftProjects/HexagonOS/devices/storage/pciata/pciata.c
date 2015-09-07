#define _PCIATA_C_
#include <tools.h>
#include <kernel.h>
#include <arch.h>
#include <devices.h>
#include <bus.h>

void pciata_handler(struct registers_t *registers)
{

}

int init_pciata()
{
	struct pci_device *device = k_pci_get_device(0x01, 0x01, 0x8086, 0x7010);
	int i, j, k, count = 0;

	if (!device)
		return 0;

	channels[ATA_PRIMARY].base  = (k_pci_get_io_addr(device, 0) & 0xFFFFFFFC) + 0x1F0 * (!k_pci_get_io_addr(device, 0));
	channels[ATA_PRIMARY].ctrl  = (k_pci_get_io_addr(device, 1) & 0xFFFFFFFC) + 0x1F6 * (!k_pci_get_io_addr(device, 1));
	channels[ATA_SECONDARY].base  = (k_pci_get_io_addr(device, 2) & 0xFFFFFFFC) + 0x170 * (!k_pci_get_io_addr(device, 2));
	channels[ATA_SECONDARY].ctrl  = (k_pci_get_io_addr(device, 3) & 0xFFFFFFFC) + 0x376 * (!k_pci_get_io_addr(device, 3));
	channels[ATA_PRIMARY].bmide = (k_pci_get_io_addr(device, 4) & 0xFFFFFFFC) + 0; // Bus Master IDE
	channels[ATA_SECONDARY].bmide = (k_pci_get_io_addr(device, 4) & 0xFFFFFFFC) + 8; // Bus Master IDE

	for (i = 0; i < 2; i++)
	{
		for (j = 0; j < 2; j++)
		{
			uint8_t err = 0;
			uint8_t type = IDE_ATA;
			uint8_t status;
			ide_devices[count].reserved = 0;

			// I should implement sleep() and add sleep() after each
			ide_write(i, ATA_REG_HDDEVSEL, 0xA0 | (j << 4));
			ide_write(i, ATA_REG_COMMAND, ATA_CMD_IDENTIFY);

			if (ide_read(i, ATA_REG_STATUS) == 0)
				continue;

			while(1)
			{
				status = ide_read(i, ATA_REG_STATUS);
				if ((status & ATA_SR_ERR))
				{
					err = 1;
					break;
				}
				if (!(status & ATA_SR_BSY) && (status & ATA_SR_DRQ))
					break;
			}
			if (err != 0)
			{
				uint8_t cl = ide_read(i, ATA_REG_LBA1);
				uint8_t ch = ide_read(i, ATA_REG_LBA2);

				if (cl == 0x14 && ch ==0xEB)
				   type = IDE_ATAPI;
				else if (cl == 0x69 && ch == 0x96)
				   type = IDE_ATAPI;
				else
				   continue;

				ide_write(i, ATA_REG_COMMAND, ATA_CMD_IDENTIFY_PACKET);
			}

			ide_read_buffer(i, ATA_REG_DATA, (uint32_t) ide_buf, 128);
			ide_devices[count].reserved = 1;
			ide_devices[count].type = type;
			ide_devices[count].channel = i;
			ide_devices[count].drive = j;
			ide_devices[count].signature = *((uint16_t*) (ide_buf + ATA_IDENT_DEVICETYPE));
			ide_devices[count].capabilities = *((uint16_t*) (ide_buf + ATA_IDENT_CAPABILITIES));
			ide_devices[count].commandSets = *((uint32_t*) (ide_buf + ATA_IDENT_COMMANDSETS));

			if (ide_devices[count].commandSets & (1 << 26))
				ide_devices[count].size   = *((uint32_t*) (ide_buf + ATA_IDENT_MAX_LBA_EXT));
			else
				ide_devices[count].size   = *((uint32_t*) (ide_buf + ATA_IDENT_MAX_LBA));

			for(k = 0; k < 40; k += 2)
			{
				ide_devices[count].model[k] = ide_buf[ATA_IDENT_MODEL + k + 1];
				ide_devices[count].model[k + 1] = ide_buf[ATA_IDENT_MODEL + k];
			}
			ide_devices[count].model[40] = 0; // Terminate String.
			count++;
		}
	}

	// Init hard drive interrupts
	k_int_add_pci_handler(14, &pciata_handler, device);
	k_int_add_pci_handler(15, &pciata_handler, device);

	return 1;
}

uint8_t ide_read(uint8_t channel, uint8_t reg)
{
	uint8_t result;
	if (reg > 0x07 && reg < 0x0C)
		ide_write(channel, ATA_REG_CONTROL, 0x80 | channels[channel].nIEN);

	if (reg < 0x08)
		result = k_hard_read_int8(channels[channel].base + reg - 0x00);
	else if (reg < 0x0C)
		result = k_hard_read_int8(channels[channel].base + reg - 0x06);
	else if (reg < 0x0E)
		result = k_hard_read_int8(channels[channel].ctrl + reg - 0x0A);
	else if (reg < 0x16)
		result = k_hard_read_int8(channels[channel].bmide + reg - 0x0E);

	if (reg > 0x07 && reg < 0x0C)
		ide_write(channel, ATA_REG_CONTROL, channels[channel].nIEN);
	return result;
}

void ide_write(uint8_t channel, uint8_t reg, uint8_t data)
{
	if (reg > 0x07 && reg < 0x0C)
		ide_write(channel, ATA_REG_CONTROL, 0x80 | channels[channel].nIEN);

	if (reg < 0x08)
		k_hard_write_int8(channels[channel].base + reg - 0x00, data);
	else if (reg < 0x0C)
		k_hard_write_int8(channels[channel].base + reg - 0x06, data);
	else if (reg < 0x0E)
		k_hard_write_int8(channels[channel].ctrl + reg - 0x0A, data);
	else if (reg < 0x16)
		k_hard_write_int8(channels[channel].bmide + reg - 0x0E, data);

	if (reg > 0x07 && reg < 0x0C)
		ide_write(channel, ATA_REG_CONTROL, channels[channel].nIEN);
}

void ide_read_buffer(uint8_t channel, uint8_t reg, uint32_t buffer, uint32_t quads)
{
	/*
	*	WARNING: This code contains a serious bug. The inline assembly trashes ES and
	*	ESP for all of the code the compiler generates between the inline assembly blocks.
	*/
	if (reg > 0x07 && reg < 0x0C)
	  ide_write(channel, ATA_REG_CONTROL, 0x80 | channels[channel].nIEN);
	asm("pushw %es; movw %ds, %ax; movw %ax, %es");

	if (reg < 0x08)
		readslong(channels[channel].base  + reg - 0x00, buffer, quads);
	else if (reg < 0x0C)
		readslong(channels[channel].base  + reg - 0x06, buffer, quads);
	else if (reg < 0x0E)
		readslong(channels[channel].ctrl  + reg - 0x0A, buffer, quads);
	else if (reg < 0x16)
		readslong(channels[channel].bmide + reg - 0x0E, buffer, quads);

	asm("popw %es;");
	if (reg > 0x07 && reg < 0x0C)
		ide_write(channel, ATA_REG_CONTROL, channels[channel].nIEN);
}


uint8_t ide_polling(uint8_t channel, bool advanced_check)
{
	uint8_t i;
	for(i = 0; i < 4; i++)
		ide_read(channel, ATA_REG_ALTSTATUS);
	while (ide_read(channel, ATA_REG_STATUS) & ATA_SR_BSY);

	if (advanced_check)
	{
		uint32_t state = ide_read(channel, ATA_REG_STATUS);

		if (state & ATA_SR_ERR)
			return 2; // Error.
		if (state & ATA_SR_DF)
			return 1; // Device Fault.
		if ((state & ATA_SR_DRQ) == 0)
			return 3; // DRQ should be set
	}
	return 0;
}

uint8_t ide_ata_access(uint8_t direction, uint8_t drive, uint32_t lba, uint8_t numsects, uint32_t edi)
{
	uint8_t lba_mode, dma, cmd;
	uint8_t lba_io[6];
	uint32_t channel = ide_devices[drive].channel;
	uint32_t slavebit = ide_devices[drive].drive;
	uint32_t bus = channels[channel].base;
	uint32_t words = 256;
	uint16_t cyl, i;
	uint8_t head, sect, err;

	ide_write(channel, ATA_REG_CONTROL, channels[channel].nIEN = (ide_irq_invoked = 0x0) + 0x02);
	if (lba >= 0x10000000)
	{
		lba_mode = 2;
		lba_io[0] = (lba & 0x000000FF) >> 0;
		lba_io[1] = (lba & 0x0000FF00) >> 8;
		lba_io[2] = (lba & 0x00FF0000) >> 16;
		lba_io[3] = (lba & 0xFF000000) >> 24;
		lba_io[4] = 0;
		lba_io[5] = 0;
		head = 0;
	}
	else if (ide_devices[drive].capabilities & 0x200)
	{
		lba_mode = 1;
		lba_io[0] = (lba & 0x00000FF) >> 0;
		lba_io[1] = (lba & 0x000FF00) >> 8;
		lba_io[2] = (lba & 0x0FF0000) >> 16;
		lba_io[3] = 0;
		lba_io[4] = 0;
		lba_io[5] = 0;
		head = (lba & 0xF000000) >> 24;
	}
	else
	{
		lba_mode = 0;
		sect = (lba % 63) + 1;
		cyl = (lba + 1  - sect) / (16 * 63);
		lba_io[0] = sect;
		lba_io[1] = (cyl >> 0) & 0xFF;
		lba_io[2] = (cyl >> 8) & 0xFF;
		lba_io[3] = 0;
		lba_io[4] = 0;
		lba_io[5] = 0;
		head = (lba + 1 - sect) % (16 * 63) / (63);
	}
	dma = 0;

	while (ide_read(channel, ATA_REG_STATUS) & ATA_SR_BSY);

	if (lba_mode == 0)
		ide_write(channel, ATA_REG_HDDEVSEL, 0xA0 | (slavebit << 4) | head);
	else
		ide_write(channel, ATA_REG_HDDEVSEL, 0xE0 | (slavebit << 4) | head);

	if (lba_mode == 2)
	{
		ide_write(channel, ATA_REG_SECCOUNT1, 0);
		ide_write(channel, ATA_REG_LBA3, lba_io[3]);
		ide_write(channel, ATA_REG_LBA4, lba_io[4]);
		ide_write(channel, ATA_REG_LBA5, lba_io[5]);
	}
	ide_write(channel, ATA_REG_SECCOUNT0, numsects);
	ide_write(channel, ATA_REG_LBA0, lba_io[0]);
	ide_write(channel, ATA_REG_LBA1, lba_io[1]);
	ide_write(channel, ATA_REG_LBA2, lba_io[2]);

	if (lba_mode == 0 && dma == 0 && direction == 0)
		cmd = ATA_CMD_READ_PIO;
	if (lba_mode == 1 && dma == 0 && direction == 0)
		cmd = ATA_CMD_READ_PIO;
	if (lba_mode == 2 && dma == 0 && direction == 0)
		cmd = ATA_CMD_READ_PIO_EXT;
	if (lba_mode == 0 && dma == 1 && direction == 0)
		cmd = ATA_CMD_READ_DMA;
	if (lba_mode == 1 && dma == 1 && direction == 0)
		cmd = ATA_CMD_READ_DMA;
	if (lba_mode == 2 && dma == 1 && direction == 0)
		cmd = ATA_CMD_READ_DMA_EXT;
	if (lba_mode == 0 && dma == 0 && direction == 1)
		cmd = ATA_CMD_WRITE_PIO;
	if (lba_mode == 1 && dma == 0 && direction == 1)
		cmd = ATA_CMD_WRITE_PIO;
	if (lba_mode == 2 && dma == 0 && direction == 1)
		cmd = ATA_CMD_WRITE_PIO_EXT;
	if (lba_mode == 0 && dma == 1 && direction == 1)
		cmd = ATA_CMD_WRITE_DMA;
	if (lba_mode == 1 && dma == 1 && direction == 1)
		cmd = ATA_CMD_WRITE_DMA;
	if (lba_mode == 2 && dma == 1 && direction == 1)
		cmd = ATA_CMD_WRITE_DMA_EXT;

	ide_write(channel, ATA_REG_COMMAND, cmd);

	if (dma)
	{
		if (direction == 0)
			direction = 0;
		else
			direction = 1;
	}
	else
	{
		if (direction == 0) // PIO Read.
		for (i = 0; i < numsects; i++)
		{
			if ((err = ide_polling(channel, true)))
				return err;
			asm("pushw %es");
			//asm("mov %%ax, %%es" : : "a"(selector));
			asm("rep insw" :: "c"(words), "d"(bus), "D"(edi));
			asm("popw %es");
			edi += (words*2);
		}
		else
		{
			// PIO Write.
			for (i = 0; i < numsects; i++)
			{
				ide_polling(channel, true);
				asm("pushw %ds");
				//asm("mov %%ax, %%ds"::"a"(selector));
				asm("rep outsw"::"c"(words), "d"(bus), "S"(edi));
				asm("popw %ds");
				edi += (words*2);
			}
			ide_write(channel, ATA_REG_COMMAND, (char[]) {ATA_CMD_CACHE_FLUSH, ATA_CMD_CACHE_FLUSH, ATA_CMD_CACHE_FLUSH_EXT}[lba_mode]);
			ide_polling(channel, false);
		}
	}
	return 0;
}

bool ide_read_sectors(uint8_t drive, uint8_t numsects, uint32_t lba, uint32_t edi)
{
	if (drive > 3 || ide_devices[drive].reserved == 0)
		return false; // Drive Not Found!
	else if (((lba + numsects) > ide_devices[drive].size) && (ide_devices[drive].type == IDE_ATA))
		return false; // Seeking to invalid position.
	else if (ide_devices[drive].type == IDE_ATA) // Read...
		 ide_ata_access(ATA_READ, drive, lba, numsects, edi);
	return true;
}

bool ide_write_sectors(uint8_t drive, uint8_t numsects, uint32_t lba, uint32_t edi)
{
	if (drive > 3 || ide_devices[drive].reserved == 0)
		return false; // Drive Not Found!
	else if (((lba + numsects) > ide_devices[drive].size) && (ide_devices[drive].type == IDE_ATA))
		return false; // Seeking to invalid position.
	else if (ide_devices[drive].type == IDE_ATA) //write
		ide_ata_access(ATA_WRITE, drive, lba, numsects, edi);
	return true;
}

int disk_read(int drive, int offset, char *buf, int count)
{
	int bl_begin, bl_end, blocks;

	bl_begin = offset / 512;
	bl_end = (offset + count) / 512;
	blocks = bl_end - bl_begin + 1;

	char *buffer = (char*) k_mem_alloc(blocks * 512);

	ide_read_sectors(drive, blocks, bl_begin, (uint32_t)buffer);
	k_mem_copy(buf, (char*) (buffer + offset % 512), count);

	return count;
}