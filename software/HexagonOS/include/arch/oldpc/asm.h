#ifndef _ASM_H_
#define _ASM_H_

#include "../../tools.h"

void k_int_disable_all(void);
void k_int_enable_all(void);
void k_hard_write_int8(uint32_t port, uint8_t value);
void k_hard_write_int16(uint16_t port, uint16_t value);
void k_hard_write_int32(uint16_t port, long value);
uint8_t k_hard_read_int8(uint32_t port);
uint16_t k_hard_read_int16(uint16_t port);
uint32_t k_hard_read_int32(uint16_t port);

#define readslong(port, buffer, count) asm volatile("cld; rep; insl" :: "D" (buffer), "d" (port), "c" (count))

#endif