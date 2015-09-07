#ifndef _SYSLOG_H_
#define _SYSLOG_H_

enum log_type
{
	error,
	warning,
	success,
	info,

	// "Hidden" types
	text,
	panic
};

void init_syslog(void);
void k_log(enum log_type type, char *str, ...);

#endif