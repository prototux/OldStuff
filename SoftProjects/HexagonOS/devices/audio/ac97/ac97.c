#define _AC97_C_
#include <tools.h>
#include <arch.h>
#include <bus.h>
#include <devices.h>
#include <kernel.h>

void ac97_handler()
{
	//k_log(info, "AC97 IRQ!\n");
	k_hard_write_int8(nabmbar + PORT_NABM_POSTATUS, k_hard_read_int8(nabmbar + PORT_NABM_POSTATUS) | (1<<3));
}

struct buf_desc *BufDescList;
int16_t *play;

void sleep_pre(int ms)
{
	k_int_enable_all();
	k_sleep_ms(ms);
	k_int_disable_all();
}

void init_ac97(void)
{
	struct pci_device *device = k_pci_get_device(0x04, 0x01, 0x8086, 0x2415);

	if (!device)
		return;

	nambar  = k_pci_get_io_addr(device, 0);
	nabmbar = k_pci_get_io_addr(device, 1);

	// Install the interrupt handler
	k_int_add_pci_handler(k_pci_get_irq(device), (void*) &ac97_handler, device);

	// Enable bus mastering and I/O space
	k_pci_config_write_int16(0, 4, 0, 0x04, (k_pci_config_read_int16(0, 4, 0, 0x04, 2) | 0x05));

	// Alloc something
	BufDescList = k_mem_alloc(4096);
	play = k_mem_alloc(0x400000);
	int i = 0;
	for (i = 0; i < 0x400000/2; i++)
		play[i] = 0;

	// Reset the device
	k_hard_write_int16(nambar  + PORT_NAM_RESET, 1);
	k_hard_write_int16(nabmbar + PORT_NABM_GLB_CTRL_STAT, 2);
	k_hard_write_int16(nabmbar + PORT_NABM_MCCONTROL, 0);
	sleep_pre(100);

	// Set the sample rate (if it's not fixed to 48khz)
	if (k_hard_read_int16(nambar + PORT_NAM_EXT_AUDIO_ID) & 1)
	{
		k_hard_write_int16(nambar + PORT_NAM_EXT_AUDIO_STS_CTRL, k_hard_read_int16(nambar + PORT_NAM_EXT_AUDIO_STS_CTRL) | 1); // Activate variable rate audio
		sleep_pre(10);
		k_hard_write_int16(nambar + PORT_NAM_FRONT_DAC_RATE, 44100);
		k_hard_write_int16(nambar + PORT_NAM_LR_ADC_RATE,    44100);
		sleep_pre(10);
	}

	// Set volume
	uint8_t volume = 0; // 150 = Silence 0 = 100%
	k_hard_write_int16(nambar + PORT_NAM_MASTER_VOLUME,  (volume<<8) | volume);
	k_hard_write_int16(nambar + PORT_NAM_MONO_VOLUME,     volume);
	k_hard_write_int16(nambar + PORT_NAM_PC_BEEP_VOLUME,  volume);
	k_hard_write_int16(nambar + PORT_NAM_PCM_OUT_VOLUME, (volume<<8) | volume);
}


bool ac97_play(uint32_t *buffer, uint32_t size)
{
	uint8_t i;
	uint8_t final = 0;

	if (!size)
		return false;
	if (size > 0x400000)
		size = 0x400000;

	k_mem_copy(play, (void *)buffer, size);
	for (i = 0; (i < 32) && size; i++)
	{
		BufDescList[i].buffer = k_mem_get_phys_addr((uintptr_t*)play) + (i+8)*0x20000;
		if (size >= 0x20000)
		{
			BufDescList[i].length = 0xFFFE;
			size -= 0x20000;
		}
		else
		{
			BufDescList[i].length = size >> 1;
			size = 0;
		}
		BufDescList[i].ioc = 1;
		if (size)
			BufDescList[i].bup = 0;
		else
		{
			BufDescList[i].bup = 1;
			final = i;
		}
	}
	k_log(info, "ac97: Playing music f=%d\n", final);

	k_hard_write_int32(nabmbar + PORT_NABM_POBDBAR, (uint32_t)k_mem_get_phys_addr((uintptr_t*)BufDescList));
	k_hard_write_int8(nabmbar + PORT_NABM_POLVI, final);
	k_hard_write_int8(nabmbar + PORT_NABM_POCONTROL, 0x15);
	return true;
}