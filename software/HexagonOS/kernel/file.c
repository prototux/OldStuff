#include <kernel.h>

// Returns true if the fp is a directory
int is_directory(struct file *fp)
{
	if (!fp->inode)
		fp->inode = ext2_read_inode(fp->disk, fp->inum);

	return (fp->inode->i_mode & EXT2_S_IFDIR);
}

// Check if the firectory is cached
struct file *is_cached_leaf(struct file *dir, char *filename)
{
	struct file *leaf;

	list_for_each_entry(leaf, &dir->leaf, sibling)
	{
		if (strcmp(leaf->name, filename) == 0)
			return leaf;
	}
	return (struct file *) 0;
}

// Get subfolders and cache it.
int get_dir_entries(struct file *dir)
{
	struct ext2_directory_entry *dentry;
	struct file *leaf;
	uint32_t dsize;
	int f_toclose;

	// Is cached?
	if (!dir->inode)
		dir->inode = ext2_read_inode(dir->disk, dir->inum);

	// Is a directory?
	if (!is_directory(dir))
	{
		k_log(error, "kernel/file/get_dir_entries() // %s is not a directory\n", dir->name);
		return -1;
	}

	// Read the directory (and open it if needed)
	if (!dir->mmap)
	{
		dir->mmap = ext2_read_file(dir->disk, dir->inode);
		f_toclose = 1;
	}
	else
		f_toclose = 0;

	// Read each entry and create the structure
	dsize = dir->inode->i_size;
	dentry = (struct ext2_directory_entry *) dir->mmap;

	while (dentry->inode && dsize)
	{
		char *filename = (char*) k_mem_alloc(dentry->name_len + 1);
		k_mem_copy(filename, &dentry->name, dentry->name_len);
		filename[dentry->name_len] = 0;

		// Add to the cache, without . and ..
		if (strcmp(".", filename) && strcmp("..", filename))
		{

			if (!(leaf = is_cached_leaf(dir, filename)))
			{
				leaf = (struct file*) k_mem_alloc(sizeof(struct file));
				leaf->name = (char*) k_mem_alloc(dentry->name_len + 1);
				strcpy(leaf->name, filename);

				leaf->disk = dir->disk;
				leaf->inum = dentry->inode;
				leaf->inode = 0;
				leaf->mmap = 0;
				leaf->parent = dir;
				INIT_LIST_HEAD(&leaf->leaf);
				list_add(&leaf->sibling, &dir->leaf);
			}
		}
		k_mem_free(filename);

		// Read next entry
		dsize -= dentry->rec_len;
		dentry = (struct ext2_directory_entry*) ((char*) dentry + dentry->rec_len);
	}

	if (f_toclose == 1)
	{
		k_mem_free(dir->mmap);
		dir->mmap = 0;
	}
	return 0;
}

// Convert a path to a file structure
struct file *path_to_file(char *path)
{
	char *beg_p, *end_p;
	struct file *fp;

	// Absolute or relative path?
	if (path[0] != '/')
		fp = current->pwd;
	 else
		fp = f_root;

	// Path parsing
	beg_p = path;
	while (*beg_p == '/')
		beg_p++;
	end_p = beg_p + 1;

	while (*beg_p != 0)
	{
		char *name;
		// Is a directory?
		if (!fp->inode)
			fp->inode = ext2_read_inode(fp->disk, fp->inum);
		if (!is_directory(fp))
			return (struct file *) 0;

		// Get subfolder name
		while (*end_p != 0 && *end_p != '/')
			end_p++;
		name = (char*) k_mem_alloc(end_p - beg_p + 1);
		k_mem_copy(name, beg_p, end_p - beg_p);
		name[end_p - beg_p] = 0;

		if (strcmp("..", name) == 0)
			fp = fp->parent;
		else if (strcmp(".", name))
		{
			get_dir_entries(fp);
			if (!(fp = is_cached_leaf(fp, name)))
			{
				k_mem_free(name);
				return (struct file *) 0;
			}
		}

		beg_p = end_p;
		while (*beg_p == '/')
			beg_p++;
		end_p = beg_p + 1;

		k_mem_free(name);
	}
	return fp;
}