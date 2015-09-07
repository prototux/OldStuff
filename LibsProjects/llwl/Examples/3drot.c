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
* Thanks to furrtek' for his great 3d-rot for the math-noobish :)      *
***********************************************************************/

#include <llwl.h>
#include <math.h>

//Some defines...
#define SIZE_FACTOR 64
#define Z_CONST 2.5
#define POSX1 50
#define POSX2 200
#define POSX3 350
#define POSY 150

//Base pixels.
const int pixelsx[8] = {-1,1,1,-1,-1,1,1,-1};
const int pixelsy[8] = {-1,-1,1,1,-1,-1,1,1};
const int pixelsz[8] = {-1,-1,-1,-1,1,1,1,1};

// Clear surface...
void clear_surface(lApp *app, lSurface *surface)
{
	bzero(surface->data,(app->width+32)*app->height*4);
}

// Put a pixel with x, y, and a hex html-like color
void put_pixel(lApp *app, lSurface *surface, unsigned int x, unsigned int y, int color)
{
	int   i;
	int   remain;

	if (x > app->width || y > app->height)
		printf("WARN: cannot put pixel %i %i: out of window\n", x, y);
	else
	{
		i = x * (surface->bpp / 8) + (y * surface->size_line);
		remain = surface->bpp;
		while (remain)
		{
			surface->data[i++] = color & 0xFF;
			color = color >> 8;
			remain -= 8;
		}
	}
}

// This one is somewhat ugly :|
void callback(lApp *llwl, lSurface *surface)
{
	int i;
	clear_surface(llwl, surface);
	static float angle = 0;

	for (i = 0; i != 8; i++)
	{
		int x = pixelsx[i];
		int y = pixelsy[i];
		int z = pixelsz[i];
		float xxx, yyy, zzz;
		int px, py;

		//Rot2D sur Y
		xxx = (cos(angle)*x)+(sin(angle)*z);
		zzz = (cos(angle)*z)-(sin(angle)*x);
		yyy = y;

		//Projection
		px = round(SIZE_FACTOR + ((xxx * SIZE_FACTOR) / (zzz + Z_CONST)));
		py = round(SIZE_FACTOR + ((yyy * SIZE_FACTOR) / (zzz + Z_CONST)));
		put_pixel(llwl, surface, POSX1+px, POSY+py, 0xFF0000);

		//Rot2D sur X
		xxx = x;
		zzz = (sin(angle)*y)+(cos(angle)*z);
		yyy = (cos(angle)*y)-(sin(angle)*z);

		//Projection
		px = round(SIZE_FACTOR + ((xxx * SIZE_FACTOR) / (zzz + Z_CONST)));
		py = round(SIZE_FACTOR + ((yyy * SIZE_FACTOR) / (zzz + Z_CONST)));
		put_pixel(llwl, surface, POSX2+px, POSY+py, 0x00FF00);

		//Rot3D
		float xx = (cos(angle)*x)-(sin(angle)*y);
		float yy = (sin(angle)*x)+(cos(angle)*y);
		xxx = (cos(angle)*xx)+(sin(angle)*z);
		float zz = (cos(angle)*z)-(sin(angle)*xx);
		yyy = (cos(angle)*yy)-(sin(angle)*zz);
		zzz = (sin(angle)*yy)+(cos(angle)*zz);

		//Projection
		px = round(SIZE_FACTOR + ((xxx * SIZE_FACTOR) / (zzz + Z_CONST)));
		py = round(SIZE_FACTOR + ((yyy * SIZE_FACTOR) / (zzz + Z_CONST)));
		put_pixel(llwl, surface, POSX3+px, POSY+py, 0x0000FF);
	}
	angle += 0.041;
	usleep(8000);
	llwl_blit_surface(llwl, surface);
}

int main()
{
	lApp *llwl = (lApp*)llwl_init(640,480,"Test window");
	lSurface *surface = (lSurface*)llwl_new_surface(llwl);
	llwl_set_callback(llwl, callback, surface);
	llwl_loop(llwl);
	return EXIT_SUCCESS;
}