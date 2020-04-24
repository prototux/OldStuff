#!/bin/sh

# Connect to network
APN="webmobil1"
OUT=$(mbimcli -d /dev/cdc-wdm0 -p --connect="$APN")
IFACE=wwp0s20u10

echo $OUT

# Get IP and gateway from telco
IP=$(echo "$OUT" | grep "/24" | sed "s/     IP \[0\]: //;s/\/24//;s/'//g")
GATEWAY=$(echo "$OUT" | grep "Gateway" | sed "s/    Gateway: //;s/'//g")

if [[ -z $IP || -z $GATEWAY ]]; then
	echo "Error: no IP nor gateway"
	exit 1
fi
echo "Connected with ip $IP and gateway $GATEWAY"

# Configure the network
ifconfig $IFACE $IP
ifconfig $IFACE netmask 255.255.255.0
route add default gw "$GATEWAY"
echo "nameserver 193.189.244.206\nnameserver 193.189.244.255" > /etc/resolv.conf
