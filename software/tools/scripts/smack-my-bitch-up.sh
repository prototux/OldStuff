#!/bin/zsh
CURRENT_SSID=$(/path/to/scripts/wifi_current.sh)
WORK_SSID="your_work_ssid"
GF_JABBER="your_wife@jabber.id"
MESSAGE="I'm late at work."

if [[ $CURRENT_SSID == $WORK_SSID ]] then
	lua /path/to/scripts/send_jabber.lua $GF_JABBER "$MESSAGE"
fi
