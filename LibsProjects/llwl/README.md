llwl
====

Low Level Window Library is a minimal X11 window library.

It only handle window opening and surface creation. after init, you will only have a memory space to work with (and some infos like BPP...).

This repo contain the lib and some examples and tools.


##cat ./RTFMHERE
Well... not really a big thing here, you can almost just read the sources, but for the lazy, here's a little manual:

###Functions:
llwl_init(int height, int width, char *title): init the lib and create a windows, params are obvious.
llwl_exit(lApp *app): must be called at exit.
llwl_set_callback(lApp *app, int (*callbackFunction)(), void* callbackParam): set the loop hook callback, you can pass one param here.
llwl_loop(lApp *app): the 42 loop, just an infinite loop (finished in 5seconds), and call the callback.
llwl_new_surface(lApp *app): create a new surface.
llwl_blit_surface(lApp *app, lSurface *surface): blit a surface into the window.

###Howto use lApp:
The only interesting vars here would be lApp.height and lApp.width. these one are pretty obvious.

###Howto use lSurface:
lSurface.size_line: size of one horizontal line (used to calculate data[i] from x:y coordinates), mostly 32.
lSurface.bpp: bits per pixel, used to calculate data[i] too. mostly 24.
lSurface.data: the main data[] memory zone, this is the array you will work with.

###Typical use:
```C
#include <llwl.h>

int callback(lApp *app, lSurface *surface)
{
	// Draw hatsune miku porn here.
	llwl_blit_surface(llwl, surface);
}

int main()
{
	lApp *app = llwl_init(640,480,"Test window");
	lSurface *surface = llwl_new_surface(app);
	llwl_set_callback(app, callback, surface);
	llwl_loop(app);
}
```
###Compiling a project (because i'm too lazy to write a Makefile) on OSX:
gcc -L../ -L/usr/X11/lib -I../ -I/usr/X11/include -o llwl_noise -m32 -lllwl -lX11 -lXext noise.c