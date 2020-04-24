#ifndef _KMALLOC_H_
#define _KMALLOC_H_

#include "../tools.h"

#define KMALLOC_MINSIZE		16

struct k_mem_alloc_header
{
	unsigned long size:31;
	unsigned long used:1;
} __attribute__ ((packed));

void *ksbrk(int n);
void *k_mem_alloc(unsigned long size);
void k_mem_free(void *v_addr);

#endif