#include <tools.h>
#include <kernel.h>
#include <arch.h>

static void itoa(char *buf, unsigned long int n, int base)
{
	uint32_t tmp = n;
	uint32_t i = 0, j = 0;

	do
	{
		tmp = n % base;
		buf[i++] = (tmp < 10)? (tmp + '0') : (tmp + 'a' - 10);
	} while (n /= base);
	buf[i--] = 0;

	for (j = 0; j < i; j++, i--)
	{
		tmp = buf[j];
		buf[j] = buf[i];
		buf[i] = tmp;
	}
}

void init_syslog(void)
{

}

void log_write(char c)
{
   while (!(k_hard_read_int8(0x3F8 + 5) & 0x20));
   k_hard_write_int8(0x3F8, c);
}


//Somewhat printf
void k_log(enum log_type type, char *s, ...)
{
	va_list ap;

	char buf[16];
	int i, j, size, buflen, neg;

	char c;
	int ival;
	unsigned int uival;

	va_start(ap, s);

	// To know what kind of stuff hapenned
	if (type == error)
		k_log(text, "[ERROR] ");
	if (type == success)
		k_log(text, "[OK] ");
	if (type == warning)
		k_log(text, "[WARN] ");
	if (type == info)
		k_log(text, "[INFO] ");
	if (type == panic)
		k_log(text, "[PANIC] ");

	while ((c = *s++))
	{
		size = 0;
		neg = 0;

		if (c == 0)
			break;
		else if (c == '%')
		{
			c = *s++;
			if (c >= '0' && c <= '9')
			{
				size = c - '0';
				c = *s++;
			}
			if (c == 'c')
			{
				char chr[2];
				chr[0] = va_arg(ap, int);
				chr[1] = 0;
				k_log(text, (char*) &chr);
			}
			else if (c == 'd')
			{
				ival = va_arg(ap, int);
				if (ival < 0)
				{
					uival = 0 - ival;
					neg++;
				}
				else
					uival = ival;
				itoa(buf, uival, 10);

				buflen = strlen(buf);
				if (buflen < size)
					for (i = size, j = buflen; i >= 0; i--, j--)
						buf[i] = (j >= 0)? buf[j] : '0';
				if (neg)
					k_log(text, "-%s", buf);
				else
					k_log(text, buf);
			}
			else if (c == 'u')
			{
				uival = va_arg(ap, int);
				itoa(buf, uival, 10);

				buflen = strlen(buf);
				if (buflen < size)
					for (i = size, j = buflen; i >= 0; i--, j--)
						buf[i] = (j >= 0) ? buf[j] : '0';
				k_log(text, buf);
			}
			else if (c == 'x' || c == 'X')
			{
				uival = va_arg(ap, int);
				itoa(buf, uival, 16);

				buflen = strlen(buf);
				if (buflen < size)
					for (i = size, j = buflen; i >= 0; i--, j--)
						buf[i] = (j >= 0) ? buf[j] : '0';

				k_log(text, "0x%s", buf);
			}
			else if (c == 'p')
			{
				uival = va_arg(ap, int);
				itoa(buf, uival, 16);
				size = 8;

				buflen = strlen(buf);
				if (buflen < size)
					for (i = size, j = buflen; i >= 0; i--, j--)
						buf[i] = (j >= 0) ? buf[j] : '0';
				k_log(text, "0x%s", buf);
			}
			else if (c == 's')
				k_log(text, (char *) va_arg(ap, int));
		}
		else
			log_write(c);
	}
	return;
}