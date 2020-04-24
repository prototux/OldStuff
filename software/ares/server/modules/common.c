#include "../include/module.h"


module_action *new_action(char *name, json_object *(*function)(json_object*))
{
	module_action *action = (module_action*) malloc(sizeof(module_action));

	action->name = name;
	action->function = function;
	action->next = 0;

	return action;
}

void register_action(module *module, char *name, json_object *(*function)(json_object*))
{
	printf("[SYS] Registering action %s\n", name);
	module_action *action = new_action(name, function);

	if (module->actions)
	{
		module_action *tmp = module->actions;
		while(tmp->next)
			tmp = tmp->next;
		tmp->next = action;
	}
	else
		module->actions = action;

	printf("[SYS] Action %s registered\n", name);
}

module *register_module(char *name, char *desc)
{
	printf("[SYS] Registering module %s\n", name);
	module *new_module = (module*) malloc(sizeof(module));

	new_module->name = name;
	new_module->description = desc;
	new_module->actions = 0;

	printf("[SYS] Module %s registered\n", name);

	return new_module;
}

