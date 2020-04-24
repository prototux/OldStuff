#!/bin/zsh
IFS=$'\n'
ESSID=($(./base_wifi.sh))
unset IFS

for t in "${ESSID[@]}"; do
	echo $t
done
