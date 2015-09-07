#include <executables.h>
#include <filesystems.h>
#include <arch.h>
#include <kernel.h>

// Check if the file IS a ELF one...
int is_elf(char *file)
{
	Elf32_Ehdr *hdr;

	hdr = (Elf32_Ehdr *) file;
	return (hdr->e_ident[0] == 0x7f && hdr->e_ident[1] == 'E' && hdr->e_ident[2] == 'L' && hdr->e_ident[3] == 'F');
}

uint32_t load_elf(char *file, struct process *proc)
{
	char *p;
	uint32_t v_begin, v_end;
	Elf32_Ehdr *hdr;
	Elf32_Phdr *p_entry;
	int i, pe;

	hdr = (Elf32_Ehdr *) file;
	p_entry = (Elf32_Phdr *) (file + hdr->e_phoff);

	if (!is_elf(file))
	{
		k_log(warning, "ELF // Not an ELF file: %s\n", file);
		return 0;
	}

	for (pe = 0; pe < hdr->e_phnum; pe++, p_entry++)
	{
		if (p_entry->p_type == PT_LOAD)
		{
			v_begin = p_entry->p_vaddr;
			v_end = p_entry->p_vaddr + p_entry->p_memsz;

			// To the user space
			if (v_begin < USER_OFFSET)
			{
				k_log(error, "ELF // can't load executable below %p\n", USER_OFFSET);
				return 0;
			}

			if (v_end > USER_STACK)
			{
				k_log(error, "ELF // can't load executable above %p\n", USER_STACK);
				return 0;
			}

			// Exec + RoData
			if (p_entry->p_flags == PF_X + PF_R)
			{
				proc->b_exec = (char*) v_begin;
				proc->e_exec = (char*) v_end;
			}

			// BSS
			if (p_entry->p_flags == PF_W + PF_R)
			{
				proc->b_bss = (char*) v_begin;
				proc->e_bss = (char*) v_end;
			}

			k_mem_copy((char *) v_begin, (char *) (file + p_entry->p_offset), p_entry->p_filesz);

			if (p_entry->p_memsz > p_entry->p_filesz)
				for (i = p_entry->p_filesz, p = (char *) p_entry->p_vaddr; i < p_entry->p_memsz; i++)
					p[i] = 0;
		}
	}
	return hdr->e_entry;
}
