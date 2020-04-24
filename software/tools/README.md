# tools
Random common tool scripts.

makefiles/*: some makefiles (software, avr, etc)

scripts/*: some random scripts:
* base_wifi.sh: run a wifi scan and return ESSIDs
* colors.sh: print the base colors (useful when fine-tuning a color scheme)
* connect_4g.sh: uses mbimcli to connect to a 3g/4g network
* scan_wifi.sh: some experiment using base_wifi.sh to detect where i am
* wifi_current.sh: get the ESSID you're currently connected to
* smack-my-bitch-up.sh: warn the gf (via jabber) that i'm still at work
* send_jabber.lua: send a xmpp message (uses libverse)
* sex.sh: i was bored, this is a implementation of the joke manpage, using lua to actually ask the gf.
* count_pkgs.sh: get how much cra... debian packages are installed

The smack-my-bitch-up.sh is a reimplementation of the smack_my_bitch_up.sh from https://www.jitbit.com/alexblog/249-now-thats-what-i-call-a-hacker/
