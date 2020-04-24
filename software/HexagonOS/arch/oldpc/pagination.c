#define _PAGING_C_
#include <tools.h>
#include <kernel.h>
#include <arch.h>

// Search a free page and use it before returning it's (physical) address
char* get_page_frame(void)
{
	int byte, bit;
	int page = 0;

	for (byte = 0; byte < RAM_MAXPAGE / 8; byte++)
		if (mem_bitmap[byte] != 0xFF)
			for (bit = 0; bit < 8; bit++)
				if (!(mem_bitmap[byte] & (1 << bit)))
				{
					page = 8 * byte + bit;
					set_page_frame_used(page);
					return (char*) (page * PAGESIZE);
				}
	return (char*) 0;
}

// Get a virtual page from kernel heap
struct page* pages_heap_add_page(void)
{
	struct page *pg;
	struct vm_area *area;
	char *v_addr, *p_addr;

	// Get a free physical page or panic
	p_addr = get_page_frame();
	if (!p_addr)
	{
		k_log(error, "arch/oldpc/paging/pages_heap_add_page() // No page available");
		asm("hlt");
	}

	// Panic if there isn't a free page available, else... grab it
	if (list_empty(&kern_free_vm))
	{
		k_log(error, "arch/oldpc/paging/pages_heap_add_page() // No memory left");
		asm("hlt");
	}

	area = list_first_entry(&kern_free_vm, struct vm_area, list);
	v_addr = area->vm_start;

	area->vm_start += PAGESIZE;
	if (area->vm_start == area->vm_end)
	{
		list_del(&area->list);
		k_mem_free(area);
	}

	// Update and return
	kernel_heap_add_page(v_addr, p_addr, 0);
	pg = (struct page*) k_mem_alloc(sizeof(struct page));
	pg->v_addr = v_addr;
	pg->p_addr = p_addr;
	pg->list.next = 0;
	pg->list.prev = 0;
	return pg;
}

// Release a virtual page from kernel heap
int pages_heap_remove_page(char *v_addr)
{
	struct vm_area *next_area, *prev_area, *new_area;
	char *p_addr;

	// Release physical page or panic
	p_addr = (char*)k_mem_get_phys_addr((uintptr_t*)v_addr);
	if (p_addr)
		release_page_frame(p_addr);
	else
	{
		k_log(error, "arch/oldpc/paging/pages_heap_remove_page() // NO PAGES?!?!");
		return 1;
	}

	// *takes tourettes's guy voice* UPDATE!
	user_heap_remove_page(v_addr);
	list_for_each_entry(next_area, &kern_free_vm, list)
	{
		if (next_area->vm_start > v_addr)
			break;
	}

	prev_area = list_entry(next_area->list.prev, struct vm_area, list);

	if (prev_area->vm_end == v_addr)
	{
		prev_area->vm_end += PAGESIZE;
		if (prev_area->vm_end == next_area->vm_start)
		{
			prev_area->vm_end = next_area->vm_end;
			list_del(&next_area->list);
			k_mem_free(next_area);
		}
	}
	else if (next_area->vm_start == v_addr + PAGESIZE)
		next_area->vm_start = v_addr;
	else if (next_area->vm_start > v_addr + PAGESIZE)
	{
		new_area = (struct vm_area*) k_mem_alloc(sizeof(struct vm_area));
		new_area->vm_start = v_addr;
		new_area->vm_end = v_addr + PAGESIZE;
		list_add(&new_area->list, &prev_area->list);
	}
	else
	{
		k_log(panic, "arch/oldpc/paging/pages_heap_remove_page() // List Corruption\n");
		asm("hlt");
	}
	return 0;
}

// Init the paging system
void init_pagination(uint32_t high_mem)
{
	int pg, pg_limit;
	unsigned long i;
	struct vm_area *p;

	// The last page
	pg_limit = (high_mem * 1024) / PAGESIZE;

	// Init physical bitmap
	for (pg = 0; pg < pg_limit / 8; pg++)
		mem_bitmap[pg] = 0;
	for (pg = pg_limit / 8; pg < RAM_MAXPAGE / 8; pg++)
		mem_bitmap[pg] = 0xFF;

	// Kernel reserved pages
	for (pg = PAGE(0x0); pg < PAGE((uint32_t) pg1_end); pg++)
		set_page_frame_used(pg);

	// Page directory init
	pd0[0] = ((uint32_t) pg0 | (PG_PRESENT | PG_WRITE | PG_4MB));
	pd0[1] = ((uint32_t) pg1 | (PG_PRESENT | PG_WRITE | PG_4MB));
	for (i = 2; i < 1023; i++)
		pd0[i] = ((uint32_t) pg1 + PAGESIZE * i) | (PG_PRESENT | PG_WRITE);
	pd0[1023] = ((uint32_t) pd0 | (PG_PRESENT | PG_WRITE));

	// Init pagination mode
	asm("mov %0, %%eax \n \
		mov %%eax, %%cr3 \n \
		mov %%cr4, %%eax \n \
		or %2, %%eax \n \
		mov %%eax, %%cr4 \n \
		mov %%cr0, %%eax \n \
		or %1, %%eax \n \
		mov %%eax, %%cr0"::"m"(pd0), "i"(PAGING_FLAG), "i"(PSE_FLAG));

	// Init kernel heap
	kern_heap = (char*) KERN_HEAP;
	ksbrk(1);

	// Init free virtual memory
	p = (struct vm_area*) k_mem_alloc(sizeof(struct vm_area));
	p->vm_start = (char*) KERN_PG_HEAP;
	p->vm_end = (char*) KERN_PG_HEAP_LIM;
	INIT_LIST_HEAD(&kern_free_vm);
	list_add(&p->list, &kern_free_vm);
}

// Create and init a page directory for a usertask
struct page_directory *user_heap_create(void)
{
	struct page_directory *pd;
	uint32_t *pdir;
	int i;

	// Take a page to have the directory inside
	pd = (struct page_directory*) k_mem_alloc(sizeof(struct page_directory));
	pd->base = pages_heap_add_page();

	// Kernel then user space
	pdir = (uint32_t*) pd->base->v_addr;
	for (i = 0; i < 256; i++)
		pdir[i] = pd0[i];
	for (i = 256; i < 1023; i++)
		pdir[i] = 0;
	pdir[1023] = ((uint32_t) pd->base->p_addr | (PG_PRESENT | PG_WRITE));

	INIT_LIST_HEAD(&pd->pt);
	return pd;
}

// Destroy a usertask page directory
int user_heap_destroy(struct page_directory *pd)
{
	struct page *pg;
	struct list_head *p, *n;

	list_for_each_safe(p, n, &pd->pt)
	{
		pg = list_entry(p, struct page, list);
		pages_heap_remove_page(pg->v_addr);
		list_del(p);
		k_mem_free(pg);
	}

	pages_heap_remove_page(pd->base->v_addr);
	k_mem_free(pd);
	return 0;
}

// Update kernel page directory
int kernel_heap_add_page(char *v_addr, char *p_addr, int flags)
{
	uint32_t *pde;
	uint32_t *pte;

	if (v_addr > (char*) USER_OFFSET && v_addr < (char*) HARD_OFFSET)
	{
		k_log(error, "arch/oldpc/paging/kernel_heap_add_page() //  Not in kernel space\n7");
		return 0;
	}

	// Panic if there isn't any page directory
	pde = (uint32_t*) (0xFFFFF000 | (((uint32_t) v_addr & 0xFFC00000) >> 20));
	if ((*pde & PG_PRESENT) == 0)
	{
		k_log(panic, "arch/oldpc/paging/pdo_add_page() // Kernel pages not found\n");
		asm("hlt");
	}

	pte = (uint32_t*) (0xFFC00000 | (((uint32_t) v_addr & 0xFFFFF000) >> 10));
	*pte = ((uint32_t) p_addr) | (PG_PRESENT | PG_WRITE | flags);
	return 0;
}

// Add a page to the usertask Page Directory
int user_heap_add_page(char *v_addr, char *p_addr, int flags, struct page_directory *pd)
{
	uint32_t *pde;
	uint32_t *pte;
	uint32_t *pt;
	struct page *pg;
	int i;

	// The last Page directory element is itself
	pde = (uint32_t*) (0xFFFFF000 | (((uint32_t) v_addr & 0xFFC00000) >> 20));

	if ((*pde & PG_PRESENT) == 0)
	{
		pg = pages_heap_add_page();

		pt = (uint32_t*) pg->v_addr;
		for (i = 1; i < 1024; i++)
			pt[i] = 0;

		*pde = (uint32_t) pg->p_addr | (PG_PRESENT | PG_WRITE | flags);

		if (pd)
			list_add(&pg->list, &pd->pt);
	}

	pte = (uint32_t*) (0xFFC00000 | (((uint32_t) v_addr & 0xFFFFF000) >> 10));
	*pte = ((uint32_t) p_addr) | (PG_PRESENT | PG_WRITE | flags);
	return 0;
}

// Remove a page from the usertask Page Directory
int user_heap_remove_page(char *v_addr)
{
	uint32_t *pte;

	if (k_mem_get_phys_addr((uintptr_t*)v_addr))
	{
		pte = (uint32_t*) (0xFFC00000 | (((uint32_t) v_addr & 0xFFFFF000) >> 10));
		*pte = (*pte & (~PG_PRESENT));
		asm("invlpg %0"::"m"(v_addr));
	}
	return 0;
}

// Get the physical address of the page from it's virtual one
uintptr_t *k_mem_get_phys_addr(uintptr_t *v_addr)
{
	uint32_t *pde;
	uint32_t *pte;

	pde = (uint32_t*) (0xFFFFF000 | (((uint32_t) v_addr & 0xFFC00000) >> 20));
	if ((*pde & PG_PRESENT))
	{
		pte = (uint32_t*) (0xFFC00000 | (((uint32_t) v_addr & 0xFFFFF000) >> 10));
		if ((*pte & PG_PRESENT))
			return (uintptr_t*) ((*pte & 0xFFFFF000) + (VADDR_PG_OFFSET((uint32_t) v_addr)));
	}
	return 0;
}