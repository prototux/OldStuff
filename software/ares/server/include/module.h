#ifndef _MODULE_H_
#define _MODULE_H_

#include <json/json.h>

typedef struct module_action
{
	char *name;
	json_object *(*function)(json_object*);
	struct module_action *next;
} module_action;

typedef struct module
{
	char *name;
	char *description;
	struct module_action *actions;	
} module;

#endif /* _MODULE_H_ */
