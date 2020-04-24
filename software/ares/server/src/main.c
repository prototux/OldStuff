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
#include "../include/url.h"
#include "../include/http.h"

ad_server_t *init_server(void)
{
    char *list[] = {"mod_dir.so.1.0.1", "nope.so", 0};
    mods = init_modules(list);

    #ifdef DEBUG
        list_modules();
        ad_log_level(AD_LOG_DEBUG);
    #endif
    ad_server_t *server = ad_server_new();
    ad_server_set_option(server, "server.port", "8888");
    //ad_server_set_option(server, "server.thread", "1");

    ad_server_register_hook(server, ad_http_handler, NULL);
    ad_server_register_hook_on_method(server, "GET", my_http_get_handler, NULL);
    ad_server_register_hook_on_method(server, "POST", my_http_get_handler, NULL);

    ad_server_register_hook(server, my_http_default_handler, NULL);	

	return server;
}

int main(int argc, char *argv[])
{
	ad_server_t *server = init_server();
	return ad_server_start(server);
}
