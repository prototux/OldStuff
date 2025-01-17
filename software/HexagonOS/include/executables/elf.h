#include "../tools.h"
#include "../kernel/process.h"

// ELF file header
typedef struct
{
	unsigned char e_ident[16];	/* ELF identification */
	uint16_t e_type;		/* 2 (exec file) */
	uint16_t e_machine;		/* 3 (intel architecture) */
	uint32_t e_version;		/* 1 */
	uint32_t e_entry;		/* starting point */
	uint32_t e_phoff;		/* program header table offset */
	uint32_t e_shoff;		/* section header table offset */
	uint32_t e_flags;		/* various flags */
	uint16_t e_ehsize;		/* ELF header (this) size */

	uint16_t e_phentsize;	/* program header table entry size */
	uint16_t e_phnum;		/* number of entries */

	uint16_t e_shentsize;	/* section header table entry size */
	uint16_t e_shnum;		/* number of entries */

	uint16_t e_shstrndx;		/* index of the section name string table */
} Elf32_Ehdr;

/*
 * ELF identification
 */
#define	EI_MAG0		0
#define	EI_MAG1		1
#define	EI_MAG2		2
#define	EI_MAG3		3
#define	EI_CLASS	4
#define	EI_DATA		5
#define	EI_VERSION	6
#define EI_PAD		7

/* EI_MAG */
#define	ELFMAG0		0x7f
#define	ELFMAG1		'E'
#define	ELFMAG2		'L'
#define	ELFMAG3		'F'

/* EI_CLASS */
#define	ELFCLASSNONE	0	/* invalid class */
#define	ELFCLASS32	1	/* 32-bit objects */
#define	ELFCLASS64	2	/* 64-bit objects */

/* EI_DATA */
#define	ELFDATANONE	0	/* invalide data encoding */
#define	ELFDATA2LSB	1	/* least significant byte first (0x01020304 is 0x04 0x03 0x02 0x01) */
#define	ELFDATA2MSB	2	/* most significant byte first (0x01020304 is 0x01 0x02 0x03 0x04) */

/* EI_VERSION */
#define	EV_CURRENT	1
#define	ELFVERSION	EV_CURRENT

/* 
 * PROGRAM HEADER 
 */
typedef struct {
	uint32_t p_type;		/* type of segment */
	uint32_t p_offset;
	uint32_t p_vaddr;
	uint32_t p_paddr;
	uint32_t p_filesz;
	uint32_t p_memsz;
	uint32_t p_flags;
	uint32_t p_align;
} Elf32_Phdr;

/* p_type */
#define	PT_NULL             0
#define	PT_LOAD             1
#define	PT_DYNAMIC          2
#define	PT_INTERP           3
#define	PT_NOTE             4
#define	PT_SHLIB            5
#define	PT_PHDR             6
#define	PT_LOPROC  0x70000000
#define	PT_HIPROC  0x7fffffff
/* p_flags */
#define PF_X	0x1
#define PF_W	0x2
#define PF_R	0x4
int is_elf(char *);
uint32_t load_elf(char *, struct process *);
