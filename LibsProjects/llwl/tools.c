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

#include "llwl.h"

// Loop and call the loop hook (if defined) when vblank
void llwl_loop(lApp *app)
{
	XEvent ev;
	GC gc = app->wingc;
	while (42)
	{
		while (!app->loop_hook || XPending(app->display))
			XNextEvent(app->display, &ev);
		app->loop_hook(app, app->loop_param);
	}
}

// Define loop hook
void llwl_set_callback(lApp *app, int (*funct)(), void *param)
{
	app->loop_hook = funct;
	app->loop_param = param;
}