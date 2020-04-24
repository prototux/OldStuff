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
* Thanks to @kailokyra here                                            *
***********************************************************************/

int is_pixel(lApp *app, lSurface *surface, unsigned int x, unsigned int y)
{
	int i = x * (surface->bpp / 8) + (y * surface->size_line);
	return (surface->data[i]+surface->data[++i]+surface->data[++i]);
}
