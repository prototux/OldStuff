#!/bin/zsh
DEVICE="wlan0"
/sbin/iw dev $DEVICE link | grep SSID | awk '{ print $2; }'
