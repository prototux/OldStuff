#include <stdlib.h>
#include <stdio.h>
#include <strings.h>

#define TAPE_SIZE 30000
#define LOOP_MAX 42

// Do i need to write something here?
void die(char *str)
{
	printf(str);
	exit(-1);
}

// Count the number of '[', remove the number of ']' and return the result...
char check_syntax(unsigned char *code)
{
	int i = 0;
	int even = 0;

	while (code[i])
	{
		if (code[i] == '[')
			even++;
		if (code[i] == ']')
			even--;
		i++;
	}
	return even;
}

// The interpreter itself
// (NOTE: loop_mark and loop_deep store the current i when it encounter a [)
// and increments loop_deep, when you exit the loop, it decrement loop_deep
void execute(unsigned char *code, unsigned int code_size)
{
    unsigned char *tape = (unsigned char*) malloc(TAPE_SIZE);
    unsigned char *ptr = tape;
	unsigned int i = 0;
	unsigned int loop_mark[LOOP_MAX];
	int loop_deep = -1;

	// Init the memory
	bzero(tape, TAPE_SIZE);

	// Execute the code
	while (code[i] && i <= code_size)
	{
		switch (code[i])
		{
			case '<':
				if (ptr > tape)
					ptr--;
				else
					ptr = ptr + TAPE_SIZE;
			break;
			case '>':
				if ((ptr - TAPE_SIZE) != tape)
					ptr++;
				else
					ptr = tape;
			break;
			case '+':
				(*ptr)++;
			break;
			case '-':
				(*ptr)--;
			break;
			case '.':
				putchar(*ptr);
			break;
			case ',':
				*ptr = getchar();
			break;
			case '[':
				if (!(*ptr))
				{
					loop_mark[loop_deep--] = 0;
					while (code[++i] != ']');
				}
				else
					loop_mark[++loop_deep] = i;
			break;
			case ']':
				if (*ptr)
					i = loop_mark[loop_deep];
				else
					loop_mark[loop_deep--] = 0;
			break;
		}
		i++;
	}
}

// Init/Read the file/Execute
int main(int argc, char **argv)
{
	//	Code loading vars
	FILE *code_file;
	unsigned char *code;
	unsigned int code_size;

	// If there's no arguments, well.. fuck
	if (argc < 2)
		die("Usage: sbbf <file>\n");

	// Open the bf file
	code_file = fopen(argv[1], "r");
	if (!code_file)
		die("Can't open file!\n");

	// Copy the entire content into a char*
	fseek(code_file, 0, SEEK_END);
	code_size = ftell(code_file);
	rewind(code_file);
	code = malloc(code_size + 1);
	fread(code, code_size, 1, code_file);
	fclose(code_file);
	code[code_size] = 0;

	// Check syntax (even number of [ and ]), then die or execute
	if (check_syntax(code))
		die("Syntax error!\n");
	else
		execute(code, code_size);

	return 0;
}
