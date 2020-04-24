#ifndef _FILE_H_
#define _FILE_H_

#include "../tools.h"
#include "../filesystems/ext2.h"

struct file
{
	struct ext2_disk *disk;
	uint32_t inum;
	char *name;
	struct ext2_inode *inode;
	char *mmap;
	int opened;
	struct file *parent;
	struct list_head leaf;
	struct list_head sibling;
};

struct open_file
{
	struct file *file;
	uint32_t ptr;
	struct open_file *next;
};

struct file *f_root;

int is_directory(struct file *);
struct file *is_cached_leaf(struct file *, char *);
int get_dir_entries(struct file *);
struct file *path_to_file(char *);


#endif