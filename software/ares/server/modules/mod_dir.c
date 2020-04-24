#include <stdlib.h>
#include <stdio.h>
#include <dlfcn.h>
#include <dirent.h>

#include "common.c"

json_object *test(json_object *jobj)
{
	printf("Function called\n");
	return 0;
}

char *parsedir(json_object * jobj)
{
    enum json_type type;
    json_object_object_foreach(jobj, key, val)
    {
        type = json_object_get_type(val);
        switch (type)
        {
            case json_type_string:
                return json_object_get_string(val);
            break;
        }
    }
}

json_object *list_simple(json_object *jobj)
{
	if (jobj != NULL)
	{
		char *path = parsedir(jobj);
		struct dirent *pDirent;
		DIR *pDir;

		pDir = opendir(path);
		if (pDir == NULL)
		{
				printf ("Cannot open directory '%s'\n", path);
				return 0;
		}

		json_object *robj = json_object_new_object();

		// Create dir array
		json_object *jdirs = json_object_new_array();

		// In case of, add the path too
		json_object *jpath = json_object_new_string(path);
		json_object_object_add(robj, "path", jpath);

		while ((pDirent = readdir(pDir)) != NULL)
		{
			if (pDirent->d_name[0] != '.')
			{
				json_object *jdirent = json_object_new_string(pDirent->d_name);
				json_object_array_add(jdirs, jdirent);      
			}
		}
		json_object_object_add(robj, "contents", jdirs);
		printf ("The json object created: %sn", json_object_to_json_string(robj));
		closedir (pDir);
		return robj;
	}
	else
	{
		return 0;
	}	
}

module *init()
{
	module *mod_dir = register_module("mod_dir", "Test module");

	register_action(mod_dir, "test", test);
	register_action(mod_dir, "list_simple", list_simple);

	return mod_dir;

/*
	struct module *infos = (struct module*) malloc(sizeof(struct module));
	char *name = "mod_dir";
	void * (*fptr)(void) = function;

	printf("[MOD] Function addr: %x\n", fptr);
	
	infos->name = name;
	infos->function = fptr;

	return infos;
*/
}

