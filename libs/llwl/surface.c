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

// Create a new surface
void *llwl_new_surface(lApp *app)
{
	lSurface *surface;

	// Init the surface and blank data, then, create the image (return -1 if failed)
	if (!(surface = malloc(sizeof(*surface))) || !(surface->data = malloc((app->width+32)*app->height*4)))
		return ((void *)-1);
	bzero(surface->data,(app->width+32)*app->height*4);
	surface->image = XCreateImage(app->display, app->visual, app->depth, ZPixmap, 0, surface->data, app->width, app->height, 32, 0);
	if (!surface->image)
	{
		free(surface->data);
		free(surface);
		return (void *)-1;
	}

	//Write some infos
	surface->gc = 0;
	surface->size_line = surface->image->bytes_per_line;
	surface->bpp = surface->image->bits_per_pixel;
	//surface->width = app->width; Already on lApp
	//surface->height = app->height; Already on lApp
	surface->pix = XCreatePixmap(app->display,app->root,app->width,app->height,app->depth);
	surface->format = ZPixmap;
	XFlush(app->display);

	return surface;
}

// Blit a surface in a window
void llwl_blit_surface(lApp *app, lSurface *surface)
{
	GC gc = app->wingc;

	if (surface->gc)
	{
		gc = surface->gc;
		XSetClipOrigin(app->display, gc, 0, 0);
	}
	XPutImage(app->display,surface->pix, app->wingc, surface->image, 0, 0, 0, 0, app->width, app->height);
	XCopyArea(app->display,surface->pix, app->window, gc, 0, 0, app->width, app->height, 0, 0);
	XFlush(app->display);
}

