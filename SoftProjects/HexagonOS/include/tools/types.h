#ifndef _TYPES_H_
#define _TYPES_H_

typedef signed char int8_t;
typedef short int16_t;
typedef long int32_t;
typedef long long int64_t;

typedef unsigned char uint8_t;
typedef unsigned short uint16_t;
typedef unsigned long uint32_t;
typedef unsigned long long uint64_t;

typedef long intptr_t;
typedef unsigned long uintptr_t;

#ifndef __bool_true_false_are_defined
	typedef _Bool bool;
	#define true 1
	#define false 0
	#define __bool_true_false_are_defined 1
#endif

#endif
