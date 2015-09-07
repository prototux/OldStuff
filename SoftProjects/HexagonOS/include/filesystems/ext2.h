#ifndef _EXT2_H_
#define _EXT2_H_

#include "../tools.h"

// Logical ext2 disk (Aka. partition) structure...
struct ext2_disk
{
	int device;						// Device ID
	uint32_t blocksize;					// It's block size
	uint16_t groups;						// It's groups count
	struct ide_partition *part;		// Logic disk partition information
	struct ext2_superblock *sb;		// It's superblock
	struct ext2_group_desc *gd;		// It's groups descriptors
};

// The supernode struct... yeah, it contains a lot of stuff
struct ext2_superblock
{
	uint32_t s_inodes_count;				// Total number of inodes
	uint32_t s_blocks_count;				// Total number of blocks
	uint32_t s_r_blocks_count;			// Total number of superuser blocks
	uint32_t s_free_blocks_count;		// Total number of free blocks
	uint32_t s_free_inodes_count;		// Total number of free inodes
	uint32_t s_first_data_block;			// Superblock block ID (try saying that fast :p)
	uint32_t s_log_block_size;			// Used to compute block size (= 1024 << s_log_block_size)
	uint32_t s_log_frag_size;			// Used to compute fragment size
	uint32_t s_blocks_per_group;			// Total number of block per group
	uint32_t s_frags_per_group;			// Total number of fragments per group
	uint32_t s_inodes_per_group;			// Total number of inodes per group
	uint32_t s_mtime;					// Last time it was mounted (does anyone really care?)
	uint32_t s_wtime;					// Last time something was wrote to the FS
	uint16_t s_mnt_count;				// How many times it was mounted since the last fsck
	uint16_t s_max_mnt_count;			// Max mounts before forced fsck
	uint16_t s_magic;					// 0xEF53
	uint16_t s_state;					// FS state so at least he say it when your HD is fucked :3
	uint16_t s_errors;					// Behavious when detecting errors (Keep calm and do FSCK)
	uint16_t s_minor_rev_level;			// Minor revision level
	uint32_t s_lastcheck;				// Last check
	uint32_t s_checkinterval;			// Max time between checks    /* Max. time between checks */
	uint32_t s_creator_os;				// 5 (why not 42? why not zoidberg)
	uint32_t s_rev_level;				// Revision level... always 1
	uint16_t s_def_resuid;				// Default UID for blocks
	uint16_t s_def_resgid;				// Default GID for blocks
	uint32_t s_first_ino;				// First useable inode
	uint16_t s_inode_size;				// Inode size
	uint16_t s_block_group_nr;			// Block group that has the bomb erhm.. the superblock
	uint32_t s_feature_compat;			// Compatible features (Features the OS can use or not without damage)
	uint32_t s_feature_incompat;			// Incompatible features (The OS should refuse the features he can't support)
	uint32_t s_feature_ro_compat;		// Read-only features (The OS should mount the volume only in RO mode)
	uint8_t s_uuid[16];					// Volume ID
	char s_volume_name[16];			// Volume name
	char s_last_mounted[64];		// Path where it was last mounted
	uint32_t s_algo_bitmap;				// Compression algorithm
	uint8_t s_padding[820];				// Padding stuff...
} __attribute__ ((packed));

// A group descriptor...
struct ext2_group_desc
{
	uint32_t bg_block_bitmap;			// ID of the first block-bitmap block
	uint32_t bg_inode_bitmap;			// ID of the first inode-bitmap block
	uint32_t bg_inode_table;				// ID of the first inode-table block
	uint16_t bg_free_blocks_count;		// Total number of free blocks
	uint16_t bg_free_inodes_count;		// Total Number of free inodes
	uint16_t bg_used_dirs_count;			// Total number of directory inodes
	uint16_t bg_pad;						// For padding purposes...
	uint32_t bg_reserved[3];				// Reserved... Aka, useless...
} __attribute__ ((packed));

// A group directory entry...
struct ext2_directory_entry
{
	uint32_t inode;						// Inode number
	uint16_t rec_len;					// Offset to the next entry
	uint8_t name_len;					// File Name lenght
	uint8_t file_type;					// File type
	char name;						// File name
} __attribute__ ((packed));

// An Inode AKA. a file...
struct ext2_inode
{
	uint16_t i_mode;						// File type + UID/GID/Sticky + Chmod
	uint16_t i_uid;						// File user ID
	uint32_t i_size;						// File size
	uint32_t i_atime;					// Last access timestamp
	uint32_t i_ctime;					// Creation timestamp
	uint32_t i_mtime;					// Last Modification timestamp
	uint32_t i_dtime;					// Deletion timestamp
	uint16_t i_gid;						// File group ID
	uint16_t i_links_count;				// Number of hard links to it...
	uint32_t i_blocks;					// Number of 512bytes blocks
	uint32_t i_flags;					// What to do with that file
	uint32_t i_osd1;						// OS custom stuff
	uint32_t i_block[15];				// Blocks pointers
	uint32_t i_generation;				// NFS file version
	uint32_t i_file_acl;					// Extented attributes
	uint32_t i_dir_acl;					// Kamoulox
	uint32_t i_faddr;					// Pointer to the file fragment
	uint8_t i_osd2[12];					// Another OS custom stuff
} __attribute__ ((packed));

// Superblock errors
#define EXT2_ERRORS_CONTINUE    1
#define EXT2_ERRORS_RO          2
#define EXT2_ERRORS_PANIC       3
#define EXT2_ERRORS_DEFAULT     1

// Inodes mode: file type
#define EXT2_S_IFMT     0xF000  // Format mask
#define EXT2_S_IFSOCK   0xC000  // Socket
#define EXT2_S_IFLNK    0xA000  // Symbolic link
#define EXT2_S_IFREG    0x8000  // Regular
#define EXT2_S_IFBLK    0x6000  // Block device
#define EXT2_S_IFDIR    0x4000  // Directory
#define EXT2_S_IFCHR    0x2000  // Character device
#define EXT2_S_IFIFO    0x1000  // Fifo

// Inodes mode: rights
#define EXT2_S_ISUID    0x0800  // SUID
#define EXT2_S_ISGID    0x0400  // SGID
#define EXT2_S_ISVTX    0x0200  // sticky bit
#define EXT2_S_IRWXU    0x01C0  // user access rights mask
#define EXT2_S_IRUSR    0x0100  // read
#define EXT2_S_IWUSR    0x0080  // write
#define EXT2_S_IXUSR    0x0040  // execute
#define EXT2_S_IRWXG    0x0038  // group access rights mask
#define EXT2_S_IRGRP    0x0020  // read
#define EXT2_S_IWGRP    0x0010  // write
#define EXT2_S_IXGRP    0x0008  // execute
#define EXT2_S_IRWXO    0x0007  // others access rights mask
#define EXT2_S_IROTH    0x0004  // read
#define EXT2_S_IWOTH    0x0002  // write
#define EXT2_S_IXOTH    0x0001  // execute

#define EXT2_INUM_ROOT	2

struct ext2_disk *ext2_get_disk_info(int, struct ide_partition *);
struct ext2_superblock *ext2_read_sb(struct ext2_disk *, int);
struct ext2_group_desc *ext2_read_gd(struct ext2_disk *, int);
struct ext2_inode *ext2_read_inode(struct ext2_disk *, int);
char *ext2_read_file(struct ext2_disk *, struct ext2_inode *);

#endif