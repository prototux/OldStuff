/***********************************************************************
*  .--.              .-.   .-.  ****************************************
* / _.-' .-.   .-.  | OO| | OO| *   Prototux's LLWL graphics library   *
* \  '-. '-'   '-'  |   | |   | *    2013 - codeoverflow.org/p/llwl    *
*  '--'             '^^^' '^^^' ****************************************
************************************************************************
*  This program is free software. It comes without any warranty, to    *
* the extent permitted by applicable law. You can redistribute it      *
* and/or modify it under the terms of the Do What The Fuck You Want    *
* To Public License, Version 2, as published by Sam Hocevar. See       *
* http://www.wtfpl.net/ for more details.                              *
************************************************************************
* Example of use...                                                    *
***********************************************************************/


#include <llwl.h>

// This one puts random noise...
void callback(lApp *llwl, lSurface *surface)
{
	int i;

	for (i = 0; i < (llwl->width+32)*llwl->height*4; i++)
		surface->data[i] = rand();
	llwl_blit_surface(llwl, surface);
}

int main()
{
	lApp *llwl = llwl_init(640,480,"Test window");
	lSurface *surface = llwl_new_surface(llwl);
	llwl_set_callback(llwl, callback, surface);
	llwl_loop(llwl);
	return EXIT_SUCCESS;
}