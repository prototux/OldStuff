#ifndef _SCHEDULER_H_
#define _SCHEDULER_H_

#define KERNELMODE 0
#define USERMODE   1

void switch_to_task(int n, int mode);
void schedule();

#endif