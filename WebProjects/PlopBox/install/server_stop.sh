#!/bin/sh

if ! screen -list | grep -q "l4d2_regular"; then
	echo "Servers are already stopped"
else
	echo -n "Killing server..."
	killall srcds_run
	echo "ok"
fi
