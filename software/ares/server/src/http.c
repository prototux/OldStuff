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
#include "../include/module.h"
#include "../include/modules.h"

char *get_params(ad_conn_t *conn)
{
    ad_http_t *http = (ad_http_t*)ad_conn_get_extra(conn);
    return http->request.query;
}

char *get_uri(ad_conn_t *conn)
{
    ad_http_t *http = (ad_http_t*)ad_conn_get_extra(conn);
    return http->request.path;
}

char *get_json_data(char *query)
{
    char *data = strstr(query, "data=")+5;
    char *dend = strchr(data, '&');

    if (data == NULL)
    {
        printf("WARN: Data not found in query %s\n", query);
        return NULL;
    }

    if (dend != NULL)
    {
        char *datacut = (char*) malloc(dend-data+1);
        strncpy(datacut, data, dend-data);
        datacut[dend-data] = 0;
        return datacut;
    }
    return data;
}

int my_http_get_handler(short event, ad_conn_t *conn, void *userdata)
{
    if (ad_http_get_status(conn) == AD_HTTP_REQ_DONE)
    {
        // Get URI and return 404 for favicon.ico
        char *uri = get_uri(conn);
        if (!strcmp(uri, "/favicon.ico"))
        {
            ad_http_response(conn, 404, "text/html", "404", 3);
            return 0;
        }

        // Get module and action
        char *module = strtok(uri, "/");
        char *action = strtok(0, "/");

        if (!action)
            return AD_OK;

        printf("MODULE=%s ACTION=%s\n", module, action);

        json_object *jobj;

        if (strlen(get_params(conn)))
        {
            printf("PARAMS=%s DATA=%s URLDECODE=%s\n", get_params(conn), get_json_data(get_params(conn)), url_decode( get_json_data(get_params(conn))));
            jobj = json_tokener_parse(url_decode(get_json_data(get_params(conn))));
            if (!jobj)
                printf("Malformed json!\n");
        }

        json_object *ret = execute_action(module, action, jobj);
        ad_http_response(conn, 200, "text/html", json_object_to_json_string(ret), strlen(json_object_to_json_string(ret)));

        return ad_http_is_keepalive_request(conn) ? AD_DONE : AD_CLOSE;
    }
    return AD_OK;
}

int my_http_default_handler(short event, ad_conn_t *conn, void *userdata)
{
    if (ad_http_get_status(conn) == AD_HTTP_REQ_DONE)
    {
        ad_http_response(conn, 501, "text/html", "Not implemented", 15);
        return AD_CLOSE;
    }
    return AD_OK;
}
