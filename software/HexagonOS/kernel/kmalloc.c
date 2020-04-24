#include <tools.h>
#include <arch.h>
#include <kernel.h>

void *ksbrk(int n)
{
	struct k_mem_alloc_header *chunk;
	int i;

	if ((kern_heap + (n * PAGESIZE)) > (char*) KERN_HEAP_LIM)
	{
		k_log(error, "arch/oldpc/paging/pages_heap_remove_page() //  No memory left");
		return (char *) -1;
	}

	chunk = (struct k_mem_alloc_header *) kern_heap;

	// Get and redeem a free page
	for (i = 0; i < n; i++)
	{
		char *p_addr = get_page_frame();
		if (!p_addr)
		{
			k_log(error, "arch/oldpc/paging/pages_heap_remove_page() //  No free pages available");
			return (char*) -1;
		}
		kernel_heap_add_page(kern_heap, p_addr, 0);
		kern_heap += PAGESIZE;
	}
	chunk->size = PAGESIZE * n;
	chunk->used = 0;
	return chunk;
}

void *k_mem_alloc(unsigned long size)
{
	unsigned long realsize;
	struct k_mem_alloc_header *chunk;

	if ((realsize = sizeof(struct k_mem_alloc_header) + size) < KMALLOC_MINSIZE)
		realsize = KMALLOC_MINSIZE;

	// Searching for a free (size) block from the start of the heap
	chunk = (struct k_mem_alloc_header *) KERN_HEAP;
	while (chunk->used || chunk->size < realsize)
	{
		if (chunk->size == 0)
		{
			k_log(error, "arch/oldpc/paging/pages_heap_remove_page() // Corrupted chunk");;
			asm("hlt");
		}

		chunk = (struct k_mem_alloc_header*) ((char*) chunk + chunk->size);
		if (chunk == (struct k_mem_alloc_header *) kern_heap)
		{
			if (ksbrk((realsize / PAGESIZE) + 1) < 0)
			{
				k_log(error, "arch/oldpc/paging/pages_heap_remove_page() // No memory left");
				asm("hlt");
			}
		}
		else if (chunk > (struct k_mem_alloc_header *) kern_heap)
		{
			k_log(error, "arch/oldpc/paging/pages_heap_remove_page() // Chunk hout of limits");
			asm("hlt");
		}
	}

	// We found a free block, we try to set each block to the minimal size
	if (chunk->size - realsize < KMALLOC_MINSIZE)
		chunk->used = 1;
	else
	{
		struct k_mem_alloc_header *other = (struct k_mem_alloc_header *) ((char *) chunk + realsize);
		other->size = chunk->size - realsize;
		other->used = 0;
		chunk->size = realsize;
		chunk->used = 1;
	}

	k_mem_alloc_used += realsize;

	// Return a pointer to the data...
	return (char*) chunk + sizeof(struct k_mem_alloc_header);
}

void k_mem_free(void *v_addr)
{
	struct k_mem_alloc_header *chunk, *other;

	// Wii Free
	chunk =	(struct k_mem_alloc_header *) (v_addr - sizeof(struct k_mem_alloc_header));
	chunk->used = 0;

	k_mem_alloc_used -= chunk->size;

	// Merging the current block with the next one if it's free too (IT'S FREE)
	while ((other =	(struct k_mem_alloc_header*) ((char*) chunk + chunk->size)) && other < (struct k_mem_alloc_header *) kern_heap && other->used == 0)
		chunk->size += other->size;
}