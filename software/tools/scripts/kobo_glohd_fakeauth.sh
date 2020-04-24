#!/bin/sh

# Kobo Glo HD fake auth on 3.19+ firmwares

NICKNAME="your_nick"
DISPLAY="Sir please, what name to display"
EMAIL="root@example.org"

echo "insert into user values('$NICKNAME', '', '$DISPLAY', '$EMAIL', '', '', 0, 0, 0, '', '', '', '');" | sqlite3 ./KoboReader.sqlite
