#ifndef _VA_ARG_H_
#define _VA_ARG_H_

typedef __builtin_va_list va_list;
#define va_start(ap, var) __builtin_va_start(ap, var)
#define va_arg(ap, type) __builtin_va_arg(ap, type)
#define va_end(ap) __builtin_va_end(ap)
#define va_copy(dest, src) __builtin_va_copy(dest, src)

//typedef char *va_list;
//#define intsizeof(n)    ((sizeof(n) + sizeof(int) - 1) &~(sizeof(int) - 1))
//#define va_start(ap, v) (ap = (va_list)&(v) + intsizeof(v))
//#define va_arg(ap, t)   (*(t *) ((ap += intsizeof(t)) - intsizeof(t)))
//#define va_end(ap)      (ap = (va_list)0)

#endif