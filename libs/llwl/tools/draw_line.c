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

// Thanks to @kailokyra for this one.
void    draw_line_horizontal(lApp *app, lSurface *surface, int ax, int ay, int bx, int by, int color)
{
  int dx, dy;
  int   e;
  int   dec;

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
  int   e;
  int   dec;

  if (ay > by)
  {
    int swap;

    swap = ax;
    ax = bx;
    bx = swap;
    swap = ay;
    ay = by;
    by = swap;
  }  e = by - ay;
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