#!/bin/sh

ls "/path/to/l4d2/$1/left4dead2/left4dead2/addons/" | grep vpk | sed ':a;N;$!ba;s/\n/ /g'
