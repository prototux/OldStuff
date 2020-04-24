#ifndef _MODULES_H_
#define _MODULES_H_

#include "module.h"
#include <json/json.h>

typedef struct modules
{
	module *module;
	struct modules *next;
} modules;

module *load_module(char *name);
json_object *execute_action(char *module, char *action, json_object *data);
void list_modules(void);
modules *init_modules(char *list[]);

#endif /* _MODULES_H_ */
