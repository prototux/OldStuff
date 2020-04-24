#include <string.h>
#include <util/delay.h>
#include <avr/io.h>
#include <stdio.h>

#include "uart.h"
#include "esp8266.h"

int esp8266_init()
{
	// Test connection with ESP
	if (!esp8266_command("AT"))
		return 0;

	// Reset the ESP and wait for the "ready"
	esp8266_command("AT+RST");
	while (strcmp(uart_rl(), "ready\r") != 0);

	// Disable echo... just because
	// (easier to read on the logic analyzer)
	esp8266_command("ATE0");
	return 1;
}

int esp8266_commandf(char *cmd, ...)
{
	va_list args;
	char *scmd = malloc(strlen(cmd)+64);
	va_start(args, cmd);
	vsprintf(scmd, cmd, args);
	va_end(args);
	int ret = esp8266_command(scmd);
	free(scmd);
	return ret;
}

int esp8266_command(char *cmd)
{
	char *ret;
	printf("%s\r\n", cmd);
	while (1)
	{
		ret = uart_rl();
		if (!strcmp(ret, "ERROR\r") || !strcmp(ret, "SEND FAIL\r"))
			return 0;
		else if (!strcmp(ret, "OK\r") || !strcmp(ret, "SEND OK\r"))
			return 1;
	}
}

int esp8266_connect(char *essid, char *password)
{
	esp8266_command("AT+CWMODE=1");
	return esp8266_commandf("AT+CWJAP=\"%s\",\"%s\"", essid, password);
}

int esp8266_tcp_server(int port)
{
	esp8266_command("AT+CIPMUX=1");
	return esp8266_commandf("AT+CIPSERVER=1,%d", port);
}

int esp8266_tcp_get_req(char *cmd)
{
	int id, length;
	char str[64];
	sscanf(cmd, "+IPD,%i,%i:%s", &id, &length, &str);
	requests[id].length = length;
	requests[id].str = &str;
	return id;
}

int esp8266_tcp_reply(int id, char *str)
{
	esp8266_commandf("AT+CIPSEND=%i,%i", id, strlen(str));
	return esp8266_command(str);
}

int esp8266_tcp_close(int id)
{
	return esp8266_commandf("AT+CIPCLOSE=%i", id);
}

int esp8266_while()
{
	char *cmd = uart_rl();
	int r,g,b, id;
	if (strstr(cmd, "+IPD"))
	{
		id = esp8266_tcp_get_req(cmd);
		// Parse command
		sscanf(requests[id].str, "LEDS:%i/%i/%i", &r, &g, &b);
		pwm_set_color(r, g, b);
		if (!(esp8266_tcp_reply(id, "OK\n") && esp8266_tcp_close(id)))
			panic();

	}
	return 1;
}
