#ifndef _PAGING_H_
#define _PAGING_H_

#include "../../tools.h"
#include "segmentation.h"

#define PAGESIZE 4096
#define RAM_MAXSIZE 0x100000000
#define RAM_MAXPAGE 0x100000

#define IDTSIZE 0x30
//#define GDTSIZE 0xFF

#define IDTBASE 0x00000000
#define GDTBASE 0x00000800

#define KERN_PDIR 0x00001000
#define KERN_STACK 0x0009FFF0
#define KERN_BASE 0x00100000
#define KERN_PG_HEAP 0x00800000
#define KERN_PG_HEAP_LIM 0x10000000
#define KERN_HEAP 0x10000000
#define KERN_HEAP_LIM 0x40000000

#define USER_OFFSET 0x40000000
#define USER_STACK 0xE0000000

#define HARD_OFFSET 0xC0000000

#define VADDR_PD_OFFSET(addr) ((addr) & 0xFFC00000) >> 22
#define VADDR_PT_OFFSET(addr) ((addr) & 0x003FF000) >> 12
#define VADDR_PG_OFFSET(addr) (addr) & 0x00000FFF
#define PAGE(addr) (addr) >> 12

#define PAGING_FLAG 0x80000000
#define PSE_FLAG 0x00000010

#define PG_PRESENT 0x00000001
#define PG_WRITE 0x00000002
#define PG_USER 0x00000004
#define PG_4MB 0x00000080

// memory location for MMIO of devices (networking card, EHCI, graphics card, ...)
#define PCI_MEM_START 0xC0000000
#define PCI_MEM_END 0xE0000000

struct page
{
	char *v_addr;
	char *p_addr;
	struct list_head list;
};

struct page_directory
{
	struct page *base;
	struct list_head pt;
};

struct vm_area
{
	char *vm_start;
	char *vm_end;
	struct list_head list;
};

// The top of the kernel heap
char *kern_heap;

// The kernel free pages list
struct list_head kern_free_vm;

#ifdef _PAGING_C_
		uint32_t *pd0 = (uint32_t *) KERN_PDIR;
		char *pg0 = (char *) 0;
		char *pg1 = (char *) 0x400000;
		char *pg1_end = (char *) 0x800000;
		uint8_t mem_bitmap[RAM_MAXPAGE / 8];
		uint32_t k_mem_alloc_used = 0;
#else
		uint32_t *pd0;
		extern uint8_t mem_bitmap[];
		uint32_t k_mem_alloc_used;
#endif

// Set a page used/free in the bitmap
#define set_page_frame_used(page) mem_bitmap[((uint32_t) page)/8] |= (1 << (((uint32_t) page)%8))
#define release_page_frame(p_addr) mem_bitmap[((uint32_t) p_addr/PAGESIZE)/8] &= ~(1 << (((uint32_t) p_addr/PAGESIZE)%8))


char *get_page_frame(void);
struct page *pages_heap_add_page(void);
int pages_heap_remove_page(char *);
void init_mm(uint32_t);
struct page_directory *user_heap_create(void);
int user_heap_destroy(struct page_directory *);
int kernel_heap_add_page(char *, char *, int);
int user_heap_add_page(char *, char *, int, struct page_directory *);
int user_heap_remove_page(char *);
uintptr_t *k_mem_get_phys_addr(uintptr_t *);
void init_pagination(uint32_t high_mem);

#endif