#ifndef _ESP8266_H_
#define _ESP8266_H_

int esp8266_init(void);
int esp8266_commandf(char *cmd, ...);
int esp8266_command(char *cmd);
int esp8266_connect(char *essid, char *password);
int esp8266_tcp_server(int port);
int esp8266_tcp_get_req(char *cmd);
int esp8266_tcp_reply(int id, char *str);
int esp8266_tcp_close(int id);
int esp8266_while(void);

typedef struct s_req
{
	int active;
    int length;
    char *str;
} req;

req requests[10];

#endif // _ESP8266_H_
