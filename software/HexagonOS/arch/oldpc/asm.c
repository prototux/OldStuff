#include <tools.h>
#include <arch.h>
// These functions re-implement some missing x86 assembly stuff

// Stop and start interruptions
void k_int_disable_all(void)
{
	asm("cli");
}

void k_int_enable_all(void)
{
	asm("sti");
}

// Write a byte, a word or a long to a certain port
inline void k_hard_write_int8(uint32_t port, uint8_t value)
{
    asm volatile ("outb %%al, %%dx" :: "d" (port), "a" (value));
}

inline void k_hard_write_int16(uint16_t port, uint16_t value)
{
    asm volatile ("outw %1, %0" :: "d" (port), "a" (value));
}

inline void k_hard_write_int32(uint16_t port, long value)
{
    asm volatile ("outl %%eax, %w1" :: "a" (value), "Nd" (port));
}

// Read a byte, a word or a long from a certain port
inline uint8_t k_hard_read_int8(uint32_t port)
{
	uint8_t value;
    asm volatile ("inb %%dx, %%al" : "=a" (value) : "d" (port));
    return value;
}

inline int8_t k_hard_read_sint8(uint32_t port)
{
    int8_t value;
    asm volatile ("inb %%dx, %%al" : "=a" (value) : "d" (port));
    return value;
}

inline uint16_t k_hard_read_int16(uint16_t port)
{
	uint16_t value;
    asm volatile ("inw %%dx, %%ax" : "=a" (value) : "d" (port));
    return value;
}

inline uint32_t k_hard_read_int32(uint16_t port)
{
    uint32_t value;
    asm volatile ("inl %%dx, %%eax" : "=a" (value) : "d" (port));
    return value;
}