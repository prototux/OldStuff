#ifndef _HTTP_H_
#define _HTTP_H_

char *get_params(ad_conn_t *conn);
char *get_uri(ad_conn_t *conn);
char *get_json_data(char *query);
int my_http_get_handler(short event, ad_conn_t *conn, void *userdata);
int my_http_default_handler(short event, ad_conn_t *conn, void *userdata);

#endif /* _HTTP_H_ */
