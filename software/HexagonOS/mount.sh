#!/bin/sh
#Mount $1 disk to /mnt
sudo losetup /dev/loop0 $1
sudo losetup /dev/loop1 -o 1048576 $1
sudo mount -t ext2 /dev/loop1 /mnt

