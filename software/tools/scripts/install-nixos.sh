#!/usr/bin/env bash
#######################################################
# Install script for my nixos setup // 06/2016 // ptx #
# WARNINGS:                                           #
# * This is *NOT* a generic install script for NixOS! #
#   This is custom made for my own setup              #
# * This is experimental, no warranty at all          #
# * If you have a setup like me, you should have      #
#   skills to make your own script, think about it    #
# * If you just run it, you're very likely to destroy #
#   your data, as it delete partitions without asking #
# * This is designed for encrypted partitions         #
# That being said, feel free to fork it, have fun! :) #
#######################################################


# Install script for my nixos setup

DISK="sda"
LOGFILE="/tmp/install.log"
LUKS_KEY="/tmp/boot.bin"
GIT_REPO="ssh://git@prototux.net/nixos"

function panic() {
	echo -e "[\e[31;5mFATAL\e[0m] Aborting, check $LOGFILE for more details"
	exit 1;
}

function assert() {
	echo -n "[...] $1"
	echo $1 >> $LOGFILE
	${@:2} &>> $LOGFILE
	
	if [[ $? -eq 0 ]]; then
		echo -e "\e[2K\r[\e[32mOK\e[0m] $1"
	else
		echo -e "\e[2K\r[\e[31mERROR\e[0m] $1"
		panic
	fi
}

function get_size() {
	echo $(echo $1 | sed 's/.*/\L\0/;s/t/Xg/;s/g/Xm/;s/m/Xk/;s/k/X/;s/b//;s/X/ *1024/g')
}

function get_sectors() {
	SIZE=$(get_size $1)
	SECT=$(cat /sys/block/$DISK/queue/hw_sector_size)
	echo $(($SIZE/$SECT))
}

function create_part() {
	# First sector of new partition
	START=$(sgdisk -F /dev/$DISK)

	# Last sector for new partition
	if [[ $2 == "MAX" ]]; then
		END=$(sgdisk -E /dev/$DISK)
	else # Partition using a specific size
		END=$(( $START + $(get_sectors $2) ))
	fi

	# Create the partition with correct size, name and type
	sgdisk --new 0:$START:$END --change-name 0:$1 --typecode 0:$3 /dev/$DISK &>> $LOGFILE
}

function get_uuid() {
	blkid $1 | awk '{ print $3; }' | sed -n 's/.*UUID=\"\([^\"]*\)\".*/\1/p'
}

#####################################
# Check if it was already installed #
#####################################

if [[ $(pvdisplay 2>> /dev/null | wc -l) -ne 0 ]]; then
	echo -e "[\e[33mWARN\e[0m] Are you reinstalling?"
	rm -rf /tmp/nixos
	umount /mnt/boot
	umount /mnt
	swapoff /dev/vg/swap
	lvremove -f vg &>> /dev/null
	vgremove vg &>> /dev/null
	pvremove /dev/mapper/enc-pv &>> /dev/null
	cryptsetup luksClose enc-pv >> /dev/null
fi

#######################
# Host OS preparation #
#######################

# Clean the install log, in case of
echo "New install" > $LOGFILE

# Setup temp nixos configuration for live system
cat << EOF > /etc/nixos/configuration.nix
{ config, pkgs, ... }:
{
  imports = [ <nixpkgs/nixos/modules/installer/cd-dvd/installation-cd-graphical.nix> ];
  environment.systemPackages = with pkgs; [ firefox vim git openssl pavucontrol profanity ];
  hardware.pulseaudio.enable = true;
}
EOF

assert "Configuring live NixOS" nixos-rebuild switch

# Get git repos

#############################
# Partitioning + LUKS setup #
#############################

# Clears previous GPT and create a new one
assert "Clearing disk" sgdisk --zap-all -N 1 -d 1 /dev/$DISK

# Create partitions
assert "Creating EFI partition" create_part EFI 100M ef00
assert "Creating LVM partition" create_part linux MAX 8e00

# Setup LUKS
assert "Creating LUKS container" cryptsetup -q luksFormat /dev/"$DISK"2 $LUKS_KEY
cryptsetup --key-file $LUKS_KEY luksAddKey /dev/"$DISK"2
assert "Mounting LUKS container" cryptsetup -q --key-file $LUKS_KEY luksOpen /dev/"$DISK"2 enc-pv
assert "Creating LVM volume" pvcreate /dev/mapper/enc-pv &>> $LOGFILE && vgcreate vg /dev/mapper/enc-pv &>> $LOGFILE
assert "Creating swap partition" lvcreate --size 22G --name swap vg
assert "Creating root partition" lvcreate --extents "100%FREE" --name root vg

# Format partitions
assert "Formating EFI partition" mkfs.vfat -n EFI /dev/"$DISK"1
assert "Formating root partition" mkfs.ext4 -O dir_index -L root /dev/vg/root
assert "Formaing swap partition" mkswap -L swap /dev/vg/swap

############################
# Mount, fetch and install #
############################

# Mount partitions, / on /mnt + /boot on /mnt/boot
assert "Mounting root partition" mount /dev/vg/root /mnt
assert "Mounting boot partition" mkdir /mnt/boot &>> $LOGFILE && mount /dev/"$DISK"1 /mnt/boot
assert "Activating swap partition" swapon /dev/vg/swap

# Create nixos config dir, and create partitions.nix
assert "Preparing nixos configuration" mkdir /mnt/etc/ && echo -n "pixie" > /mnt/etc/hostname

# Get git repo and install
assert "Fetching nixos configuration" git clone $GIT_REPO /mnt/etc/nixos
nixos-install

# Done!
echo -e "[\e[32mOK\e[0m] Done\c"
