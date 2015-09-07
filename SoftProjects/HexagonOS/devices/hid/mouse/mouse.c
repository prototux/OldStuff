#define _MOUSE_C_
#include <tools.h>
#include <arch.h>
#include <devices.h>
#include <kernel.h>

void mouse_wait_read()
{
	unsigned int timeout = 1000;
	while(timeout-- && !(k_hard_read_int8(0x64) & 1));
}

void mouse_wait_write()
{
	unsigned int timeout = 1000;
	while(timeout-- && (k_hard_read_int8(0x64) & 2));
}

void mouse_wait_ack()
{
	unsigned int timeout = 1000;
	while(timeout-- && !(k_hard_read_int8(0x60) == 0xFA));
}

void mouse_write(char command)
{
	mouse_wait_write();
	k_hard_write_int8(0x64, 0xD4);

	mouse_wait_write();
	k_hard_write_int8(0x60, command);
}

uint8_t mouse_read()
{
	mouse_wait_read();
	return k_hard_read_int8(0x60);
}

void mouse_handler(struct registers_t *registers)
{
	uint8_t status;
	status = k_hard_read_int8(0x64);

	while(status & 0x01)
	{
		if (status & 0x20)
		{
			mouse_byte[mouse_cycle++] = k_hard_read_int8(0x60);
			if (mouse_cycle == 3)
			{

				if (mouse_byte[0] & 0x80 || mouse_byte[0] & 0x40)
					break;

				mousex += mouse_byte[1];
				mousey += mouse_byte[2]*-1;

				if (mousex > 1024-10)
					mousex = 1024-10;
				if (mousey > 768-10)
					mousey = 768-10;
				if (mousex < 2)
					mousex = 2;
				if (mousey < 2)
					mousey = 2;

				mouselc = (mouse_byte[0] & 0x01);
				mouserc = (mouse_byte[0] & 0x02);
				mousemc = (mouse_byte[0] & 0x04);

				mouse_cycle = 0;
			}
		}
		status = k_hard_read_int8(0x64);
	}
}

void init_mouse()
{
	uint8_t status;

	// Enable the interrupts
	mouse_wait_write();
	k_hard_write_int8(0x64, 0xA8);

	mouse_wait_write();
	k_hard_write_int8(0x64, 0x20);

	mouse_wait_read();
	status = k_hard_read_int8(0x60) | 2;

	mouse_wait_write();
	k_hard_write_int8(0x64, 0x60);
	mouse_wait_write();
	k_hard_write_int8(0x60, status);
	mouse_wait_ack();

	// Tell the mouse to use default settings
	mouse_write(0xF6);
	//mouse_wait_ack();

	// Enable the mouse
	mouse_write(0xF4);
	mouse_wait_ack();

	// Install mouse handler
	k_int_add_std_handler(12, &mouse_handler);
}