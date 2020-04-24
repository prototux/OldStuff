#ifndef _MOUSE_H_
#define _MOUSE_H_

#include "../../tools.h"

#ifdef _KERNEL_INTERRUPTS_C_
	uint8_t mouse_cycle=0;     //unsigned char
	int8_t mouse_byte[4];    //signed char
	int8_t mouse_x=0;         //signed char
	int8_t mouse_y=0;         //signed char

	// Mouse position
	int16_t mousex = 0;
	int16_t mousey = 0;
	uint8_t mouselc = 0;
	uint8_t mouserc = 0;
	uint8_t mousemc = 0;
	//uint16_t ticks = 0;
#else
	extern uint8_t mouse_cycle;     //unsigned char
	extern int8_t mouse_byte[];    //signed char
	extern int8_t mouse_x;         //signed char
	extern int8_t mouse_y;

	// Mouse position
	extern int16_t mousex;
	extern int16_t mousey;
	extern uint8_t mouselc;
	extern uint8_t mouserc;
	extern uint8_t mousemc;
#endif

void init_mouse(void);

#endif