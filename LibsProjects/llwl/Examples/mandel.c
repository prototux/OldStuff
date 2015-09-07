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
* A mandelbrot fractal implementation...                               *
***********************************************************************/

#include <llwl.h>
#include <math.h>

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
			color >>= 8;
			remain -= 8;
		}
	}
}

int get_color(int r, int g, int b)
{
	int color = 0;
	color += r & 0xFF;
	color <<= 8;
	color += g & 0xFF;
	color <<= 8;
	color += b & 0xFF;
	return color;
}

//Your FPU is gonna be hurt a little bit, sorry.
void callback(lApp *llwl, lSurface *surface)
{
	static int cn = 0;
	clear_surface(llwl, surface);
    double zoom = cn;
    double MinRe = -2.0+zoom/(120+zoom);
    double MaxRe = 1.0;
    double MinIm = -1.2+zoom/(150+zoom);
    double MaxIm = MinIm+(MaxRe-MinRe)*(480+zoom)/(640+zoom);
    double Re_factor = (MaxRe-MinRe)/((640+zoom)-1);
    double Im_factor = (MaxIm-MinIm)/((480+zoom)-1);
    unsigned MaxIterations = cn/2;
    unsigned iterations = 0;

    for(unsigned y=0; y<480; ++y)
    {
        double c_im = MaxIm - y*Im_factor;

        for(unsigned x=0; x<640; ++x)
        {
            double c_re = MinRe + x*Re_factor;
            double Z_re = c_re, Z_im = c_im;
            char isInside = 1;
            for(iterations=0; iterations<MaxIterations; iterations++)
            {
                double Z_re2 = Z_re*Z_re, Z_im2 = Z_im*Z_im;
                if(Z_re2 + Z_im2 > 4)
                {
                    isInside = 0;
                    break;
                }
                Z_im = 2*Z_re*Z_im + c_im;
                Z_re = Z_re2 - Z_im2 + c_re;
            }
            put_pixel(llwl, surface, x, y, (isInside)?0x80CCEF:0x000000);
        }
    }
    cn++;
	llwl_blit_surface(llwl, surface);
}

int main()
{
	lApp *llwl = (lApp*)llwl_init(640,480,"Mandelbrot set");
	lSurface *surface = (lSurface*)llwl_new_surface(llwl);
	llwl_set_callback(llwl, callback, surface);
	llwl_loop(llwl);
	return EXIT_SUCCESS;
}