#!/bin/bash


if [[ -z "$1" || "$1" == "v4" ]]; then
	echo $(nslookup myip.opendns.com 208.67.222.222 | grep "Address" | grep -v "#" | sed 's/Address: //')
else
	echo $(nslookup myip.opendns.com 2620:0:ccc::2 | grep "Address" | grep -v "#" | sed 's/Address: //')
fi
