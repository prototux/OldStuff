#define _TIMER_C_
#include <arch.h>
#include <kernel.h>

void ticks_handler(struct registers_t *registers)
{
	ticks++;
}

void k_sleep_ms(int milliseconds)
{
	uint32_t end_tick = ticks + (milliseconds/10);
	while (ticks < end_tick);
}

void k_sleep_s(int seconds)
{
	k_sleep_ms(seconds*1000);
}

void k_get_rtc(void)
{
	rtc_update();
	k_log(4, "It's %d/%d/%d %d:%d\n", date.day, date.month, date.year, date.hour, date.minute);
}

static uint8_t rtc_read_register(uint8_t reg)
{
	  k_hard_write_int8(0x70, reg);
	  return k_hard_read_int8(0x71);
}

static void rtc_update(void)
{
	uint8_t century = 0;
	uint8_t registerB = 0;
	bool is_updating = k_hard_read_int8(0x71) & 0x80;

	while (is_updating)
	{
		k_hard_write_int8(0x70, 0x0A);
		is_updating = k_hard_read_int8(0x71) & 0x80;
	}

	date.second = rtc_read_register(0x00);
	date.minute = rtc_read_register(0x02);
	date.hour = rtc_read_register(0x04);
	date.day = rtc_read_register(0x07);
	date.month = rtc_read_register(0x08);
	date.year = rtc_read_register(0x09);

	if(century_register)
		century = rtc_read_register(century_register);

	registerB = rtc_read_register(0x0B);

	// Convert BCD to binary values if necessary
	if (!(registerB & 0x04))
	{
		date.second = (date.second & 0x0F) + ((date.second/16)*10);
		date.minute = (date.minute & 0x0F) + ((date.minute/16)*10);
		date.hour = ((date.hour & 0x0F) + (((date.hour & 0x70)/16)*10)) | (date.hour & 0x80);
		date.day = (date.day & 0x0F) + ((date.day/16)*10);
		date.month = (date.month & 0x0F) + ((date.month/16)*10);
		date.year = (date.year & 0x0F) + ((date.year/16)*10);

		if(century_register)
			century = (century & 0x0F) + ((century/16)*10);
	}

	if (!(registerB & 0x02) && (date.hour & 0x80))
		date.hour = ((date.hour & 0x7F) + 12) % 24;

	if(century_register)
		date.year += century * 100;
	else
	{
		date.year += (CURRENT_YEAR/100)*100;
		if(date.year < CURRENT_YEAR)
			date.year += 100;
	}
}