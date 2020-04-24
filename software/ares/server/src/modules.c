#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dirent.h>
#include <dlfcn.h>

// JSON include
#include <json/json.h>

// ASYNC includes
#include <asyncd/asyncd.h>

// ARES includes
#include "../include/common.h"
#include "../include/module.h"
#include "../include/modules.h"

module *load_module(char *name)
{   
    module *mod;
    void *handle;
    char error;
    
    module *(*mod_init)(void);
    
    char *path = malloc(strlen("/home/jason/Projects/Perso/Private/ares/server/modules/")+strlen(name));
    strcat(path, "/home/jason/Projects/Perso/Private/ares/server/modules/");
    strcat(path, name);
    
    handle = dlopen(path, RTLD_LAZY);
    if (!handle)
    {   
        printf("[ERR] Module not found: %s\n", name);
        return 0;
    }
    
    mod_init = dlsym(handle, "init");
    if ((error = dlerror() != 0))
    {   
        printf("[ERR] Not a module: %s\n", name);
        return 0;
    }
    
    mod = (*mod_init)();
    
    printf("[IFO] Loaded module: %s\n", mod->name);
    
    //dlclose(handle);
    return mod;
}

json_object *execute_action(char *module, char *action, json_object *data)
{   
    modules *tmp = mods;
    
    printf("[DBG] Executing %s/%s\n", module, action);
    
    // Get the module
    while (tmp)
    {   
        if (!strcmp(tmp->module->name, module))
            break;
        tmp = tmp->next;
    }
    
    if (strcmp(tmp->module->name, module))
    {   
        printf("[ERR] Module not found: %s\n", module);
        return 0;
    }
    
    // Get the action
    module_action *atmp = tmp->module->actions;
    while (atmp)
    {   
        if (!strcmp(atmp->name, action))
            return (*atmp->function)(data);
        atmp = atmp->next;
    }
    printf("[WRN] Action not found for module %s: %s\n", module, action);
    return 0;
}

void list_modules(void)
{   
    modules *tmp = mods;
    
    if (!tmp || !tmp->module)
    {   
        printf("[WRN] No modules initialized\n");
        return;
    }
    
    printf("[IFO] Listing modules and actions\n");
    
    // Listing modules
    while (tmp)
    {   
        printf("Module %s\n", tmp->module->name);
        
        // Listing actions
        module_action *atmp = tmp->module->actions;
        while (atmp)
        {   
            printf("\tAction: %s\n", atmp->name);
            atmp = atmp->next;
        }
        tmp = tmp->next;
    }
}

modules *init_modules(char *list[])
{
    modules *mods = 0;

    for (int i = 0; list[i]; i++)
    {
        // Load the module
        modules *new_module = malloc(sizeof(modules));
        new_module->module = load_module(list[i]);
        if (!new_module->module)
            continue;
        new_module->next = 0;


        // Add it to the list
        if (!mods)
            mods = new_module;
        else
        {
            modules *tmp = mods;
            while(tmp->next)
                tmp = tmp->next;
            tmp->next = new_module;
        }
    }

    return mods;
}
