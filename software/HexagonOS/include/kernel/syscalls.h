#ifndef _SYSCALLS_H_
#define _SYSCALLS_H_

void do_syscalls(struct registers_t *registers);
void sys_exit(int status);
int sys_open(char*);
char* sys_sbrk(int);
int sys_exec(char *, char **);
int sys_console_read(char*);
void sys_sigreturn(void);
int sys_wait(int* status);

#endif