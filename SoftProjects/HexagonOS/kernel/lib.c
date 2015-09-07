//Copy (size) bytes from src to dest
void *k_mem_copy(char *dest, char *src, int size)
{
	char *ptr = dest;
	while (size--)
		*dest++ = *src++;
	return ptr;
}

void *k_mem_copy32(unsigned long *dest, unsigned long *src, int size)
{
	unsigned long *ptr = dest;
	while (size--)
		*dest++ = *src++;
	return ptr;
}

int strcpy(char *dst, char *src)
{
	int i = 0;
	while ((dst[i] = src[i]))
		i++;
	return i;
}

int strcmp(char *dst, char *src)
{
	int i = 0;

	while ((dst[i] == src[i]))
		if (src[i++] == 0)
			return 0;
	return 1;
}

int strlen(char *s)
{
	int i = 0;
	while (*s++)
		i++;
	return i;
}