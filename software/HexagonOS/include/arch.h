#ifndef _ARCH_H_
#define _ARCH_H_

#ifdef CONF_ARCH_oldpc
	#include "arch/oldpc/asm.h"
	#include "arch/oldpc/boot.h"
	#include "arch/oldpc/init.h"
	#include "arch/oldpc/interrupts.h"
	#include "arch/oldpc/pagination.h"
	#include "arch/oldpc/segmentation.h"
#endif

#endif