# This Source Code Form is subject to the terms of the Mozilla Public
# License, v. 2.0. If a copy of the MPL was not distributed with this file,
# You can obtain one at http://mozilla.org/MPL/2.0/.

# Configuration
NAME = hexakernel
ARCH = oldpc
DEBUG = true

# Debug Configuration
KERNEL_PATH = /mnt/boot/$(NAME)
VM_DEVICE = /dev/loop0

# Automatic setup using the configuration
# Set warning flags and defines for debug
ifeq ($(DEBUG),true)
	WARNS = -Wall -Wno-pointer-sign
	DEFINES = -D CONF_ARCH_$(ARCH) -D DEBUG
else
	WARNS = -w
	DEFINES = -D CONF_ARCH_$(ARCH)
endif

# Select the devices with the ARCH var
ifeq ($(ARCH),oldpc)
	DEVICES = hid/mouse hid/keyboard storage/pciata video/qemu audio/ac97 network/rtl8139
else ifeq ($(ARCH),raspi)
	DEVICES = storage/sdcard network/raspi video/raspi
else # newpc
	DEVICES = hid/mouse storage/pciata network/rtl8139 video/vga
endif

# C compilation stuff
CC = gcc
CFLAGS = -I./include -O0 -m32 -std=gnu99 -fno-pack-struct -fno-stack-protector -fno-builtin -nostdlib -nostdinc -fno-diagnostics-show-caret -fno-diagnostics-show-option
CFLAGS += $(DEFINES) $(WARNS)

# Final linking stuff
LD = ld
LDFLAGS = -Ttext 100000 -melf_i386 --entry=_start

# x86 assembly stuff
ASM = nasm
ASMFLAGS = -f elf

# Static analysis stuff
CHECK = cppcheck
CHECK_VERBOSITY = --suppress=variableScope --suppress=arithOperationsOnVoidPointer --suppress=unusedVariable --enable=warning,style,performance,missingInclude
CKFLAGS = $(CHECK_VERBOSITY) --language=c --std=c99 -I ./include --template='\t[{severity}] Line {line}: {message}' $(DEFINES) -q

# Random stuff (for debug)
SUDO = sudo

# VM stuff (for debug)
VM = qemu-system-i386
VMDEVICES = -vga std -soundhw ac97 -serial stdio -netdev user,id=unet0,net=192.168.76.0/24 -device rtl8139,netdev=unet0
VMFLAGS = -no-kvm $(VMDEVICES) -boot c -hda

# Color... it's better to read with them.
CRED =		$(shell echo "\033[0;31m")
CGREEN =	$(shell echo "\033[0;32m")
CYELLOW =	$(shell echo "\033[0;33m")
CBLUE =		$(shell echo "\033[0;34m")
CMAGENTA =	$(shell echo "\033[0;35m")
CCYAN =		$(shell echo "\033[0;36m")
CEND =		$(shell echo "\033[0m")

# Error/warning highlight command for GCC, NASM and CppCheck
# NB: it also re-format GCC/NASM wmessages to be easier to read,
# CppCheck formating is already set in it's --template argument
HIGHLIGHT_START =  2>&1 | sed
HIGHLIGHT_COLOR = -e "s/\[error\]/\[$(CRED)error$(CEND)\]/g" -e "s/\[style\]/\[$(CYELLOW)style$(CEND)\]/g" -e "s/\[warning\]/\[$(CYELLOW)warning$(CEND)\]/g"
CCHIGHLIGHT = $(HIGHLIGHT_START) -e "/^\(.*\)In function \(.*\):/d" -e "s/^\(.*\):\([0-9]\+\):\([0-9]\+\): \(warning\|error\): \(.*\)/\t\[\4\] Line \2: \5/g" $(HIGHLIGHT_COLOR)
ASHIGHLIGHT = $(HIGHLIGHT_START) -e "s/^\(.*\).s:\([0-9]\+\): \(warning\|error\): \(.*\)/\t\[\3\] Line \2: \4/g" $(HIGHLIGHT_COLOR)
CKHIGHLIGHT = $(HIGHLIGHT_START) -e "/^\(.*\)Please report this to Cppcheck developers/d" $(HIGHLIGHT_COLOR)

# Make the source files list
SRC = kmain.c
SRC += $(wildcard kernel/*.c)
SRC += $(foreach DDIR,$(DEVICES),$(wildcard devices/$(DDIR)/*.c))
SRC += $(wildcard arch/$(ARCH)/*.c)
SRC += $(wildcard filesystems/*/*.c)
SRC += $(wildcard executables/*/*.c)
SRC += $(wildcard bus/*/*.c)
SRC += $(wildcard syscalls/*.c)

ASMSRC = $(wildcard arch/$(ARCH)/*.s)

# Make the objects files list, from the sources
OBJ = $(SRC:.c=.o)
ASMOBJ = $(ASMSRC:.s=.o)

# Make methods
ifeq ($(DEBUG),true)
all:		infos fclean $(NAME) run
else
all:		infos $(NAME)
endif

infos:
			@echo "$(CCYAN)Building $(NAME) (arch=$(ARCH), debug=$(DEBUG)) $(CEND)"

$(NAME):	$(OBJ) $(ASMOBJ)
			@echo "$(CCYAN)LD $(NAME) $(CEND)"
			@$(LD) $(LDFLAGS) $(ASMOBJ) $(OBJ) -o $(NAME)

%.o:		%.c
			@echo "$(CGREEN)CC $< $(CEND)"
			@$(CC) $(CFLAGS) -o $@ -c $< $(CCHIGHLIGHT)
ifeq ($(DEBUG),true)
			@$(CHECK) $(CKFLAGS) $< $(CKHIGHLIGHT)
endif

%.o:		%.s
			@echo "$(CBLUE)AC $< $(CEND)"
			@$(ASM) $(ASMFLAGS) $< $(ASHIGHLIGHT)

clean:
			@echo "$(CRED)RM *.o $(CEND)"
			@rm -f $(OBJ) $(ASMOBJ)

fclean:		clean
			@echo "$(CRED)RM $(NAME) $(CEND)"
			@rm -f $(NAME)

re:			fclean all

ifeq ($(DEBUG),true)
run:
			@echo "$(CMAGENTA)CP $(NAME) $(CEND)"
			@$(SUDO) cp $(NAME) $(KERNEL_PATH); $(SUDO) sync
			@echo "$(CMAGENTA)VM $(NAME) $(CEND)"
			@echo "$(CYELLOW)Everything below is the OS's debug messages: $(CEND)"
			@$(SUDO) $(VM) $(VMFLAGS) $(VM_DEVICE)
else
run:
			@echo "$(CRED)Cannot run if DEBUG is set to false! $(CEND)"
endif

.PHONY: all clean fclean re run