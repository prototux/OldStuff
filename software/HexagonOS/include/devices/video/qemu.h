#ifndef _QEMU_H_
#define _QEMU_H_

#define VBE_DISPI_IOPORT_INDEX 0x01CE
#define VBE_DISPI_IOPORT_DATA  0x01CF
#define VBE_DISPI_INDEX_ID              0x0
#define VBE_DISPI_INDEX_XRES            0x1
#define VBE_DISPI_INDEX_YRES            0x2
#define VBE_DISPI_INDEX_BPP             0x3
#define VBE_DISPI_INDEX_ENABLE          0x4
#define VBE_DISPI_INDEX_BANK            0x5
#define VBE_DISPI_INDEX_VIRT_WIDTH      0x6
#define VBE_DISPI_INDEX_VIRT_HEIGHT     0x7
#define VBE_DISPI_INDEX_X_OFFSET        0x8
#define VBE_DISPI_INDEX_Y_OFFSET        0x9

#define VBE_DISPI_DISABLED              0x00
#define VBE_DISPI_ENABLED               0x01
#define VBE_DISPI_GETCAPS               0x02
#define VBE_DISPI_8BIT_DAC              0x20
#define VBE_DISPI_LFB_ENABLED           0x40
#define VBE_DISPI_NOCLEARMEM            0x80

#define VBE_PIXELS 0xfd000000

void vbe_write(uint16_t index, uint16_t value);
void vbe_set(uint16_t xres, uint16_t yres, uint16_t bpp);
void vbe_blit(uint32_t *pixels, uint16_t x, uint16_t y, uint16_t sx, uint16_t sy);
void wtf_mandelbrot(void);
void vbe_putpixel(uint16_t x, uint16_t y, uint32_t color);
void vbe_random(int seed);
int init_qemu();
void vbe_draw_mouse();
void vbe_redraw();

#ifdef _QEMU_C_
	uint32_t *wp;
	uint32_t *win;
	int32_t winx;
	int32_t winy;
	uint32_t buffer_number = 0;
	uint32_t *vbe_buffer[2];
#endif

#endif