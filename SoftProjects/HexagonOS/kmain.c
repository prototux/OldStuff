// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at http://mozilla.org/MPL/2.0/.
#include <tools.h>
#include <arch.h>
#include <devices.h>
#include <filesystems.h>
#include <bus.h>
#include <kernel.h>

// Init / folder
static struct file *init_root(struct ext2_disk *disk)
{
	struct file *fp;

	fp = (struct file*) k_mem_alloc(sizeof(struct file));

	fp->name = (char*) k_mem_alloc(sizeof("/"));
	strcpy(fp->name, "/");

	fp->disk = disk;
	fp->inum = EXT2_INUM_ROOT;
	fp->inode = ext2_read_inode(disk, fp->inum);
	fp->mmap = 0;
	fp->parent = fp;

	INIT_LIST_HEAD(&fp->leaf);
	get_dir_entries(fp);
	INIT_LIST_HEAD(&fp->sibling);

	return fp;
}


static void init_devices(void)
{
	// Init the syslog
	init_syslog();
	k_log(info, "HexagonOS on x86//oldpc platform\n");

	// Init Qemu VGA
	init_qemu();
	k_log(success, "Loading Video\n");

	// Init HD-Audio
	init_ac97();
	k_log(success, "Loading Audio\n");

	// Init Networking
	//init_net();
	k_log(success, "Loading Networking\n");

	// Init Mouse
	init_keyboard();
	init_mouse();
	k_log(success, "Loading HID\n", 0x02);
}

static void init_bus(void)
{
	// Init PCI Devices
	init_pci();

	// Init USB Devices
	//init_usb();
}

static void init_fs(void)
{
	struct ext2_disk *hd;
	struct ide_partition *p1;

	// Init IDE drive
	init_pciata();

	// Mount root partition
	p1 = (struct ide_partition*) k_mem_alloc(sizeof(struct ide_partition));
	disk_read(0, 0x01BE, (char*) p1, 16);
	hd = ext2_get_disk_info(0, p1);
	f_root = init_root(hd);
}

int kmain(struct multiboot_info *mbi)
{
	k_int_disable_all();

	#ifdef CONF_ARCH_oldpc
		boot_oldpc(mbi);
	#endif

	// Init kernel thread
	current = &p_list[0];
	current->pid = 0;
	current->state = 1;
	current->regs.cr3 = (uint32_t) pd0;
	current->pwd = f_root;
	current->parent = current;
	INIT_LIST_HEAD(&current->child);

	init_bus();
	init_fs();
	init_devices();

	// Load DE
	//struct file *fp;
	//fp = path_to_file("/bin/desktop");
	//fp->inode = ext2_read_inode(fp->disk, fp->inum);
	//load_task(fp->disk, fp->inode, 0, 0);

	// Release the kraken!
	k_int_enable_all();
	while(1)
	{
		// Reload the DE if crash
		//if (n_proc == 0)
		//{
			//k_int_disable_all();
			//k_log(error, "The shell crashed!\n");
			//load_task(fp->disk, fp->inode, 0, 0);
			//k_int_enable_all();
		//}
	}
	return 1;
}