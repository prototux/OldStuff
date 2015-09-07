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
* With (non optimized) scanline filling...                             *
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


void    draw_line_horizontal(lApp *app, lSurface *surface, int ax, int ay, int bx, int by, int color)
{
	int dx, dy;
	int e;
	int dec;

	if (ax > bx)
	{
		int swap;

		swap = ax;
		ax = bx;
		bx = swap;
		swap = ay;
		ay = by;
		by = swap;
	}
	e = bx - ax;
	dx = e << 1;
	dy = abs((by - ay)) << 1;
	dec = ((by - ay) > 0) ? 1 : -1;
	while (ax < bx)
	{
		put_pixel(app, surface, ax, ay, color);
		++ax;
		if ((e -= dy) <= 0)
		{
			ay += dec;
			e += dx;
		}
	}
}

void    draw_line_vertical(lApp *app, lSurface *surface, int ax, int ay, int bx, int by, int color)
{
	int dx, dy;
	int e;
	int dec;

	if (ay > by)
	{
		int swap;

		swap = ax;
		ax = bx;
		bx = swap;
		swap = ay;
		ay = by;
		by = swap;
	}
	e = by - ay;
	dy = e << 1;
	dx =  abs((bx - ax)) << 1;
	dec = ((bx - ax) > 0) ? 1 : -1;
	while (ay < by)
	{
			put_pixel(app, surface, ax, ay, color);
			++ay;
			if ((e -= dx) <= 0)
			{
				ax += dec;
				e += dy;
			}
	}
}

void  draw_line(lApp *app, lSurface *surface, int ax, int ay, int bx, int by, int color)
{
	if (abs((bx - ax)) < abs((by - ay)))
		draw_line_vertical(app, surface, ax, ay, bx, by, color);
	else
		draw_line_horizontal(app, surface, ax, ay, bx, by, color);
}

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

	if (x > app->width || y > app->height || !app || !surface)
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

int is_pixel(lApp *app, lSurface *surface, unsigned int x, unsigned int y)
{
	int i = x * (surface->bpp / 8) + (y * surface->size_line);
	return (surface->data[i]+surface->data[++i]+surface->data[++i]);
}

// This one is somewhat ugly :|
void callback(lApp *llwl, lSurface *surface)
{
	int i;
	clear_surface(llwl, surface);
	static float angle = 0;

	int c3dpx[8];
	int c3dpy[8];
	int c2ypx[8];
	int c2ypy[8];
	int c2xpx[8];
	int c2xpy[8];
	for (i = 0; i != 8; i++)
	{
		int x = pixelsx[i];
		int y = pixelsy[i];
		int z = pixelsz[i];
		float xxx, yyy, zzz;
		int px, py;

		//Rot2D on Y
		xxx = (cos(angle)*x)+(sin(angle)*z);
		zzz = (cos(angle)*z)-(sin(angle)*x);
		yyy = y;

		//Projection
		px = round(SIZE_FACTOR + ((xxx * SIZE_FACTOR) / (zzz + Z_CONST)));
		py = round(SIZE_FACTOR + ((yyy * SIZE_FACTOR) / (zzz + Z_CONST)));
		c2ypx[i] = px;
		c2ypy[i] = py;
		put_pixel(llwl, surface, POSX1+px, 20+py, 0xFF0000);

		//Rot2D on X
		xxx = x;
		zzz = (sin(angle)*y)+(cos(angle)*z);
		yyy = (cos(angle)*y)-(sin(angle)*z);

		//Projection
		px = round(SIZE_FACTOR + ((xxx * SIZE_FACTOR) / (zzz + Z_CONST)));
		py = round(SIZE_FACTOR + ((yyy * SIZE_FACTOR) / (zzz + Z_CONST)));
		c2xpx[i] = px;
		c2xpy[i] = py;
		put_pixel(llwl, surface, POSX2+px, 20+py, 0x00FF00);

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
		c3dpx[i] = px;
		c3dpy[i] = py;
		put_pixel(llwl, surface, POSX3+px, 20+py, 0x0000FF);
	}

	// Optimisation skills here :D

	// 3D
	draw_line(llwl, surface, POSX3+c3dpx[0], POSY+c3dpy[0], POSX3+c3dpx[1], POSY+c3dpy[1], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[1], POSY+c3dpy[1], POSX3+c3dpx[2], POSY+c3dpy[2], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[2], POSY+c3dpy[2], POSX3+c3dpx[3], POSY+c3dpy[3], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[3], POSY+c3dpy[3], POSX3+c3dpx[0], POSY+c3dpy[0], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[4], POSY+c3dpy[4], POSX3+c3dpx[5], POSY+c3dpy[5], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[5], POSY+c3dpy[5], POSX3+c3dpx[6], POSY+c3dpy[6], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[6], POSY+c3dpy[6], POSX3+c3dpx[7], POSY+c3dpy[7], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[7], POSY+c3dpy[7], POSX3+c3dpx[4], POSY+c3dpy[4], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[4], POSY+c3dpy[4], POSX3+c3dpx[0], POSY+c3dpy[0], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[7], POSY+c3dpy[7], POSX3+c3dpx[3], POSY+c3dpy[3], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[5], POSY+c3dpy[5], POSX3+c3dpx[1], POSY+c3dpy[1], 0x0000FF);
	draw_line(llwl, surface, POSX3+c3dpx[6], POSY+c3dpy[6], POSX3+c3dpx[2], POSY+c3dpy[2], 0x0000FF);

	// 2D on Y
	draw_line(llwl, surface, POSX1+c2ypx[0], POSY+c2ypy[0], POSX1+c2ypx[1], POSY+c2ypy[1], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[1], POSY+c2ypy[1], POSX1+c2ypx[2], POSY+c2ypy[2], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[2], POSY+c2ypy[2], POSX1+c2ypx[3], POSY+c2ypy[3], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[3], POSY+c2ypy[3], POSX1+c2ypx[0], POSY+c2ypy[0], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[4], POSY+c2ypy[4], POSX1+c2ypx[5], POSY+c2ypy[5], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[5], POSY+c2ypy[5], POSX1+c2ypx[6], POSY+c2ypy[6], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[6], POSY+c2ypy[6], POSX1+c2ypx[7], POSY+c2ypy[7], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[7], POSY+c2ypy[7], POSX1+c2ypx[4], POSY+c2ypy[4], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[4], POSY+c2ypy[4], POSX1+c2ypx[0], POSY+c2ypy[0], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[7], POSY+c2ypy[7], POSX1+c2ypx[3], POSY+c2ypy[3], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[5], POSY+c2ypy[5], POSX1+c2ypx[1], POSY+c2ypy[1], 0xFF0000);
	draw_line(llwl, surface, POSX1+c2ypx[6], POSY+c2ypy[6], POSX1+c2ypx[2], POSY+c2ypy[2], 0xFF0000);

	// 2D on X
	draw_line(llwl, surface, POSX2+c2xpx[0], POSY+c2xpy[0], POSX2+c2xpx[1], POSY+c2xpy[1], 0x00FF00);//1
	draw_line(llwl, surface, POSX2+c2xpx[1], POSY+c2xpy[1], POSX2+c2xpx[2], POSY+c2xpy[2], 0x00FF00);//2
	draw_line(llwl, surface, POSX2+c2xpx[2], POSY+c2xpy[2], POSX2+c2xpx[3], POSY+c2xpy[3], 0x00FF00);//1
	draw_line(llwl, surface, POSX2+c2xpx[3], POSY+c2xpy[3], POSX2+c2xpx[0], POSY+c2xpy[0], 0x00FF00);//2
	draw_line(llwl, surface, POSX2+c2xpx[4], POSY+c2xpy[4], POSX2+c2xpx[5], POSY+c2xpy[5], 0x00FF00);//3
	draw_line(llwl, surface, POSX2+c2xpx[5], POSY+c2xpy[5], POSX2+c2xpx[6], POSY+c2xpy[6], 0x00FF00);//4
	draw_line(llwl, surface, POSX2+c2xpx[6], POSY+c2xpy[6], POSX2+c2xpx[7], POSY+c2xpy[7], 0x00FF00);//3
	draw_line(llwl, surface, POSX2+c2xpx[7], POSY+c2xpy[7], POSX2+c2xpx[4], POSY+c2xpy[4], 0x00FF00);//4
	draw_line(llwl, surface, POSX2+c2xpx[4], POSY+c2xpy[4], POSX2+c2xpx[0], POSY+c2xpy[0], 0x00FF00);//5
	draw_line(llwl, surface, POSX2+c2xpx[7], POSY+c2xpy[7], POSX2+c2xpx[3], POSY+c2xpy[3], 0x00FF00);//6
	draw_line(llwl, surface, POSX2+c2xpx[5], POSY+c2xpy[5], POSX2+c2xpx[1], POSY+c2xpy[1], 0x00FF00);//5
	draw_line(llwl, surface, POSX2+c2xpx[6], POSY+c2xpy[6], POSX2+c2xpx[2], POSY+c2xpy[2], 0x00FF00);//6

	// Scanline filling
	int xline = 0;
	int yline = 0;
	int xxline = 0;
	int plain1s = 0, plain1e = 0;
	int plain2s = 0, plain2e = 0;
	int plain3s = 0, plain3e = 0;
	for (yline = 0; yline <= 480; yline++)
	{
		plain1s = 0;
		plain1e = 0;
		plain2s = 0;
		plain2e = 0;
		plain3s = 0;
		plain3e = 0;
		for (xline = 0; xline <= 640; xline++)
		{
			if (xline <= 175 && yline >= 150 && yline <= 275)
			{
				if (plain1s && is_pixel(llwl, surface, xline, yline))
					plain1e = xline;
				else if (is_pixel(llwl, surface, xline, yline))
					plain1s = xline;
			}
			if (xline >= 175 && xline <= 340 && yline >= 150 && yline <= 275)
			{
				if (plain2s && is_pixel(llwl, surface, xline, yline))
					plain2e = xline;
				else if (is_pixel(llwl, surface, xline, yline))
					plain2s = xline;
			}
			if (xline >= 340 && xline <= 480 && yline >= 150 && yline <= 275)
			{
				if (plain3s && is_pixel(llwl, surface, xline, yline))
					plain3e = xline;
				else if (is_pixel(llwl, surface, xline, yline))
					plain3s = xline;
			}
		}

		for (xxline = 0; xxline <= 640; xxline++)
		{
			if (plain1s && plain1e && xxline >= plain1s && xxline <= plain1e)
				if (!is_pixel(llwl, surface, xxline, yline))
					put_pixel(llwl, surface, xxline, yline+150, 0x550000);
				else
					put_pixel(llwl, surface, xxline, yline+150, 0xFF0000);

			if (plain2s && plain2e && xxline >= plain2s && xxline <= plain2e)
				if (!is_pixel(llwl, surface, xxline, yline))
					put_pixel(llwl, surface, xxline, yline+150, 0x005500);
				else
					put_pixel(llwl, surface, xxline, yline+150, 0x00FF00);

			if (plain3s && plain3e && xxline >= plain3s && xxline <= plain3e)
				if (!is_pixel(llwl, surface, xxline, yline))
					put_pixel(llwl, surface, xxline, yline+150, 0x000055);
				else
					put_pixel(llwl, surface, xxline, yline+150, 0x0000FF);
		}
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