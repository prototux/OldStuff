#define _QEMU_C_
#include <tools.h>
#include <arch.h>
#include <bus.h>
#include <devices.h>
#include <kernel.h>

void vbe_write(uint16_t index, uint16_t value)
{
   k_hard_write_int16(VBE_DISPI_IOPORT_INDEX, index);
   k_hard_write_int16(VBE_DISPI_IOPORT_DATA, value);
}

int init_qemu()
{
	struct pci_device *device = k_pci_get_device(0x03, 0x00, 0x1234, 0x1111);

	if (!device)
		return 0;

	// Allocate pages
	int pgc;
	for (pgc = VBE_PIXELS; pgc <= 0xfdffffff; pgc += 0x400)
		kernel_heap_add_page((char*)pgc, (char*)pgc, 0x03);

	vbe_buffer[0] = k_mem_alloc(1024*768*4);
	vbe_buffer[1] = k_mem_alloc(1024*768*4);

	vbe_set(1024, 768, 32);

	struct file *fp = path_to_file("/wallpaper.bmp");
	fp->inode = ext2_read_inode(f_root->disk, fp->inum);
	wp = (uint32_t*)(ext2_read_file(fp->disk, fp->inode)+0x8B);

	struct file *wfp = path_to_file("/test.bmp");
	wfp->inode = ext2_read_inode(wfp->disk, wfp->inum);
	win = (uint32_t*)(ext2_read_file(wfp->disk, wfp->inode)+0x8B);

	return 0;
}

void vbe_set(uint16_t xres, uint16_t yres, uint16_t bpp)
{
   vbe_write(VBE_DISPI_INDEX_ENABLE, VBE_DISPI_DISABLED);
   vbe_write(VBE_DISPI_INDEX_XRES, xres);
   vbe_write(VBE_DISPI_INDEX_YRES, yres);
   vbe_write(VBE_DISPI_INDEX_BPP, bpp);
   vbe_write(VBE_DISPI_INDEX_ENABLE, VBE_DISPI_ENABLED | VBE_DISPI_LFB_ENABLED);
}

void vbe_putpixel(uint16_t x, uint16_t y, uint32_t color)
{
	vbe_buffer[buffer_number][y*1024+x] = color;
}

void vbe_draw_mouse()
{
	uint32_t x, y;

	uint8_t mouse_pointer[10][10] =
	{
		{1,1,1,1,1,1,0,0,0,0},
		{1,1,1,1,1,0,0,0,0,0},
		{1,1,1,1,0,0,0,0,0,0},
		{1,1,1,1,1,0,0,0,0,0},
		{1,1,0,1,1,1,0,0,0,0},
		{1,0,0,0,1,1,1,0,0,0},
		{0,0,0,0,0,1,1,1,0,0},
		{0,0,0,0,0,0,1,1,1,0},
		{0,0,0,0,0,0,0,1,1,1},
		{0,0,0,0,0,0,0,0,1,1}
	};

	uint32_t color = (mouselc)?0x00FF0000:0x00FFFFFF;
	color = (mouserc)?0x000000FF:color;
	color = (mouserc)?0x0000FF00:color;

	for (x = mousex; x < mousex+10; x++)
		for (y = mousey; y < mousey+10; y++)
			if (mouse_pointer[y-mousey][x-mousex])
				vbe_putpixel(x, y, color);
}

void vbe_redraw()
{
	// Ultimate optimization skills here, contain your orgasm :3
	register int i;
	register uint32_t *framebuffer = (uint32_t *) 0xfd000000;
	register uint32_t *doublebuffer = (uint32_t *) vbe_buffer[buffer_number];

	// Wallpaper test
	for(i = 0; i < (1024*768); i++)
		doublebuffer[i] = wp[i];

	if (mouselc && mousex >= winx && mousex <= winx+300 && mousey >= winy && mousey <= winy+225)
	{
		winx = mousex-50;
		if (winx < 0)
			winx = 0;
		if (winx+300 > 1024)
			winx = 1024-300;
		winy = mousey-50;
		if (winy < 0)
			winy = 0;
		if (winy+225 > 768)
			winy = 768-225;
	}

	uint32_t tx = 0, ty = 0;

	for (ty=0;ty < 225; ty++)
		for (tx=0;tx < 300;tx++)
			vbe_putpixel(winx+tx, winy+ty,  win[ty*300+tx]);

	vbe_draw_mouse();


	for(i = 0; i < (1024*768); i++)
		framebuffer[i] = doublebuffer[i];
	buffer_number = !buffer_number;
}


/*
// OMG, an easter egg!
void wtf_mandelbrot(void)
{
	int cn = 0;
	while (1)
	{
	    double zoom = cn;
	    double minRe = -2.0+zoom/(120+zoom);
	    double maxRe = 1.0;
	    double minIm = -1.2+zoom/(150+zoom);
	    double maxIm = minIm+(maxRe-minRe)*(768+zoom)/(1024+zoom);
	    double reFactor = (maxRe-minRe)/((1024+zoom)-1);
	    double imFactor = (maxIm-minIm)/((768+zoom)-1);
	    unsigned maxIterations = cn/2;
	    unsigned iterations = 0;
		uint32_t x,y;
	    for(y=0; y<768; ++y)
	    {
	        double c_im = maxIm - y*imFactor;

	        for(x=0; x<1024; ++x)
	        {
	            double c_re = minRe + x*reFactor;
	            double Z_re = c_re, Z_im = c_im;
	            bool isInside = true;
	            for(iterations=0; iterations<maxIterations; iterations++)
	            {
	                double Z_re2 = Z_re*Z_re, Z_im2 = Z_im*Z_im;
	                if(Z_re2 + Z_im2 > 4)
	                {
	                    isInside = false;
	                    break;
	                }
	                Z_im = 2*Z_re*Z_im + c_im;
	                Z_re = Z_re2 - Z_im2 + c_re;
	            }
	            vbe_putpixel(x, y, (isInside)?0x0080CCEF:0x00000000);
	        }
	    }
	    cn++;
	}
}
*/