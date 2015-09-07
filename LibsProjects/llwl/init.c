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

// This one is obvious, isn't?
void *llwl_init(int width, int height, char *title)
{
	XGCValues xgcv;
	XSizeHints hints;
	XEvent ev;
	long hintsReturn;
	lApp *app;

	// Init our lApp structure
	if (!(app = malloc(sizeof(*app))) || (app->display = XOpenDisplay(NULL))==0)
		return ((void *)0);

	// Init X11 and init window
	app->screen = DefaultScreen(app->display);
	app->root = DefaultRootWindow(app->display);
	app->cmap = DefaultColormap(app->display,app->screen);
	app->depth = DefaultDepth(app->display,app->screen);
	app->visual = DefaultVisual(app->display,app->screen);
	app->window = XCreateSimpleWindow(app->display, app->root, 0, 0, width, height, 1, WhitePixel(app->display, app->screen), BlackPixel(app->display, app->screen));
	app->width = width;
	app->height = height;
	XStoreName(app->display,app->window,title);

	// Forbid window resizing
	XGetWMNormalHints(app->display, app->window, &hints, &hintsReturn);
	hints.width = width;
	hints.height = height;
	hints.min_width = width;
	hints.min_height = height;
	hints.max_width = width;
	hints.max_height = height;
	hints.flags = PPosition | PSize | PMinSize | PMaxSize;
	XSetWMNormalHints(app->display, app->window, &hints);

	// Finish window init
	app->wingc = XCreateGC(app->display, app->window, GCForeground, &xgcv);
	XMapRaised(app->display, app->window);

	return (app);
}

// Must be called at exit
void llwl_exit(lApp *app)
{
	XDestroyWindow(app->display,app->window);
	XFreeGC(app->display,app->wingc);
}