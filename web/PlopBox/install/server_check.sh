#!/bin/sh

echo -n "Status: "

#Regular server status
if ! screen -list | grep -q "l4d2_regular"; then
        echo -n "rko "
else
        echo -n "rok "
fi

#Supertanks server status
if ! screen -list | grep -q "l4d2_supertanks"; then
        echo "sko"
else
        echo "sok"
fi

