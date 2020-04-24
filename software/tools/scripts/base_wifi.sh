#!/bin/zsh

# Run a wifi scan and return ESSIDs

sudo /sbin/iwlist wlan0 scan | grep ESSID | sed -e "s/.*ESSID://g;s/\"//g;/(\\x00)+/d"
