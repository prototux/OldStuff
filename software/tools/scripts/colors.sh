#!/bin/zsh

# This script print the 8 colors + with attributes

for attr in 0 1 4 5 7 8; do
	for fg in $(seq 30 39); do
		for bg in $(seq 40 49); do
			echo -ne "\e[$attr;$fg;$bg""m LOREM IPSUM "
		done
		echo "\e[$attr;37;40m"
	done
done
