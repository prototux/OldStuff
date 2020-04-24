#include <stdlib.h>
#include <stdio.h>

void log_debug(char *str)
{
	printf("[DEBUG] %s\n", str);
}

void log_error(char *str)
{
	printf("[ERROR] %s\n", str);
}

void log_info(char *str)
{
	printf("[NOTE] %s\n", str);
}

void log_success(char *str)
{
	printf("[OK] %s\n", str);
}
