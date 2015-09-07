#ifndef _TIMER_H_
#define _TIMER_H_

#define CURRENT_YEAR 2014

struct rtc_date
{
	uint8_t second;
	uint8_t minute;
	uint8_t hour;
	uint8_t day;
	uint8_t month;
	uint16_t year;
};

#ifdef _TIMER_C_
	volatile uint32_t ticks;
	struct rtc_date date;
	const int century_register = 0x00;

	// Private functions
	static uint8_t rtc_read_register(uint8_t reg);
	static void rtc_update(void);
#else
	extern uint32_t ticks;
	extern uint32_t date;
#endif

// Public functions
void ticks_handler(struct registers_t *registers);
void k_get_rtc(void);
void k_sleep_ms(int milliseconds);
void k_sleep_s(int seconds);

#endif