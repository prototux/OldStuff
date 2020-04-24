#include <filesystems.h>
#include <devices.h>
#include <kernel.h>

// Init logic disk struct...
struct ext2_disk *ext2_get_disk_info(int device, struct ide_partition *part)
{
	int i, j;
	struct ext2_disk *hd;

	hd = (struct ext2_disk*) k_mem_alloc(sizeof(struct ext2_disk));

	hd->device = device;
	hd->part = part;
	hd->sb = ext2_read_sb(hd, part->s_lba * 512);
	hd->blocksize = 1024 << hd->sb->s_log_block_size;

	i = (hd->sb->s_blocks_count / hd->sb->s_blocks_per_group) + ((hd->sb->s_blocks_count % hd->sb->s_blocks_per_group)? 1 : 0);
	j = (hd->sb->s_inodes_count / hd->sb->s_inodes_per_group) + ((hd->sb->s_inodes_count % hd->sb->s_inodes_per_group)? 1 : 0);
	hd->groups = (i > j)? i : j;

	hd->gd = ext2_read_gd(hd, part->s_lba * 512);
	return hd;
}

// Init fs' superblock struct
struct ext2_superblock *ext2_read_sb(struct ext2_disk *hd, int s_part)
{
	struct ext2_superblock *sb;

	sb = (struct ext2_superblock*) k_mem_alloc(sizeof(struct ext2_superblock));
	disk_read(hd->device, s_part + 1024, (char*) sb, sizeof(struct ext2_superblock));
	return sb;
}

// Read all group descriptors
struct ext2_group_desc *ext2_read_gd(struct ext2_disk *hd, int s_part)
{
	struct ext2_group_desc *gd;
	int offset, gd_size;

	// Bloc offset
	offset = (hd->blocksize == 1024)? 2048 : hd->blocksize;

	// Read descriptor size and init it...
	gd_size = hd->groups * sizeof(struct ext2_group_desc);
	gd = (struct ext2_group_desc*) k_mem_alloc(gd_size);

	disk_read(hd->device, s_part + offset, (char*) gd, gd_size);
	return gd;
}

// Get a inode struct from it's number
struct ext2_inode *ext2_read_inode(struct ext2_disk *hd, int i_num)
{
	int gr_num, index, offset;
	struct ext2_inode *inode;

	inode = (struct ext2_inode*) k_mem_alloc(sizeof(struct ext2_inode));

	// Get inode's groupe and index, then, calculate it's offset (in byte)
	gr_num = (i_num - 1) / hd->sb->s_inodes_per_group;
	index = (i_num - 1) % hd->sb->s_inodes_per_group;
	offset = hd->gd[gr_num].bg_inode_table * hd->blocksize + index * hd->sb->s_inode_size;

	// Well... read and return it :p
	disk_read(hd->device, (hd->part->s_lba * 512) + offset, (char *) inode, hd->sb->s_inode_size);
	return inode;
}

// Read a file from it's inode number
char *ext2_read_file(struct ext2_disk *hd, struct ext2_inode *inode)
{
	char *mmap_base, *mmap_head, *buf;
	int *p, *pp, *ppp;
	int i, j, k;
	int n, size;

	buf = (char*) k_mem_alloc(hd->blocksize);
	p = (int*) k_mem_alloc(hd->blocksize);
	pp = (int*) k_mem_alloc(hd->blocksize);
	ppp = (int*) k_mem_alloc(hd->blocksize);

	// Get file size and k_mem_alloc the corresponding value...
	size = inode->i_size;
	mmap_head = mmap_base = k_mem_alloc(size);

	for (i = 0; i < 12 && inode->i_block[i]; i++)
	{
		disk_read(hd->device, (hd->part->s_lba * 512) + inode->i_block[i] * hd->blocksize, buf, hd->blocksize);
		n = ((size > hd->blocksize)? hd->blocksize : size);
		k_mem_copy(mmap_head, buf, n);
		mmap_head += n;
		size -= n;
	}

	if (inode->i_block[12])
	{
		disk_read(hd->device, (hd->part->s_lba * 512) + inode->i_block[12] * hd->blocksize, (char*) p, hd->blocksize);
		for (i = 0; i < hd->blocksize / 4 && p[i]; i++)
		{
			disk_read(hd->device, (hd->part->s_lba * 512) + p[i] * hd->blocksize, buf, hd->blocksize);
			n = ((size > hd->blocksize)? hd->blocksize : size);
			k_mem_copy(mmap_head, buf, n);
			mmap_head += n;
			size -= n;
		}
	}

	if (inode->i_block[13])
	{
		disk_read(hd->device, (hd->part->s_lba * 512) + inode->i_block[13] * hd->blocksize, (char*) p, hd->blocksize);
		for (i = 0; i < hd->blocksize / 4 && p[i]; i++)
		{
			disk_read(hd->device, (hd->part->s_lba * 512) + p[i] * hd->blocksize, (char*) pp, hd->blocksize);
			for (j = 0; j < hd->blocksize / 4 && pp[j]; j++)
			{
				disk_read(hd->device, (hd->part->s_lba * 512) + pp[j] * hd->blocksize, buf, hd->blocksize);
				n = ((size > hd-> blocksize)? hd->blocksize : size);
				k_mem_copy(mmap_head, buf, n);
				mmap_head += n;
				size -= n;
			}
		}
	}

	if (inode->i_block[14])
	{
		disk_read(hd->device, (hd->part->s_lba * 512) + inode->i_block[14] * hd->blocksize, (char*) p, hd->blocksize);
		for (i = 0; i < hd->blocksize / 4 && p[i]; i++)
		{
			disk_read(hd->device, (hd->part->s_lba * 512) + p[i] * hd->blocksize, (char*) pp, hd->blocksize);
			for (j = 0; j < hd->blocksize / 4 && pp[j]; j++)
			{
				disk_read(hd->device, (hd->part->s_lba * 512) + pp[j] * hd->blocksize, (char*) ppp, hd->blocksize);
				for (k = 0; k < hd->blocksize / 4 && ppp[k]; k++)
				{
					disk_read(hd->device, (hd->part->s_lba * 512) + ppp[k] * hd->blocksize, buf, hd->blocksize);
					n = ((size > hd->blocksize)? hd->blocksize : size);
					k_mem_copy(mmap_head, buf, n);
					mmap_head += n;
					size -= n;
				}
			}
		}
	}
	k_mem_free(buf);
	k_mem_free(p);
	k_mem_free(pp);
	k_mem_free(ppp);
	return mmap_base;
}