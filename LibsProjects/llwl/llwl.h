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

#include <X11/Xlib.h>
#include <X11/Xutil.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

typedef struct s_surface
{
	// X11 stuff
	XImage *image;
	Pixmap pix;
	GC gc;

	// Some infos
	int size_line;
	int bpp;
	int type;
	int format;
	char *data;
	//int height;
	//int width;
} lSurface;

typedef struct s_app
{
	// X11 stuff
	Display *display;
	Window root;
	Visual *visual;
	Window window;
	GC wingc;
	Colormap cmap;
	int screen;
	int depth;

	// Some infos
	int height;
	int width;

	// Hooks
	int (*loop_hook)();
	void *loop_param;
} lApp;

void *llwl_init(int width, int height, char *title);
void *llwl_new_surface(lApp *app);
void llwl_exit(lApp *app);
void llwl_blit_surface(lApp *app, lSurface *surface);
void llwl_loop(lApp *app);
void llwl_set_callback(lApp *app, int (*funct)(), void *param);