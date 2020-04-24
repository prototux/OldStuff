#!/bin/sh
xrandr \
--output DP1 --mode 1920x1080 --pos 1920x0 \
--output eDP1 --mode 1920x1080 --pos 960x1080 \
--output VGA1 --mode 1920x1080 --pos 0x0
# VGA1 DP1
#   eDP1
