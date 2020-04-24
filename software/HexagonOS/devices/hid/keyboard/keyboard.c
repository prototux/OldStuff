#define _KEYBOARD_C_
#include <arch.h>
#include <kernel.h> // for the k_log, to be removed
#include <devices.h>

void keyboard_handler(struct registers_t *registers)
{
	uint8_t c = 0; // char makes a very strange 0xffffffXX thing...
	static int lshift_enable = 0;
	static int rshift_enable = 0;
	static int alt_enable = 0;
	static int ctrl_enable = 0;

	while (!(k_hard_read_int8(0x64) & 0x01));
	c = k_hard_read_int8(0x60)-1;

	if (alt_enable || ctrl_enable)
		c = c+1;

	//If i < 0x80 the key is pressed, else, the key is released
	if (c < 0x80)
	{
		switch (c)
		{
			case 0x29:
				lshift_enable = 1;
			break;
			case 0x35:
				rshift_enable = 1;
			break;
			case 0x1C:
				ctrl_enable = 1;
			break;
			case 0x37:
				alt_enable = 1;
			break;
			default:
				k_log(text, "%c", keyboard_map[c * 4 + (lshift_enable || rshift_enable)]);
			break;
		}
	}
	else
	{
		c -= 0x80;
		switch (c)
		{
			case 0x29:
				lshift_enable = 0;
			break;
			case 0x35:
				rshift_enable = 0;
			break;
			case 0x1C:
				ctrl_enable = 0;
			break;
			case 0x37:
				alt_enable = 0;
			break;
		}
	}
}


void init_keyboard(void)
{
	k_int_add_std_handler(1, &keyboard_handler);
}