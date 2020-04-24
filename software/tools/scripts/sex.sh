#!/bin/zsh
GF_JABBER="your_wife@jabber.id"
MESSAGE="[auto] Wanna have some fun tonight"

LINK=" with"
while getopts ":1 :a :b :c :C :f :j :l :o :O :q -S -w -W" opt
do
	case $opt in
		1 ) MESSAGE="[auto] Handjob";;
		a ) MESSAGE+="$LINK some aphrodisiacs";;
		b ) MESSAGE+="$LINK buggery";;
		c ) MESSAGE+="$LINK chocolate";;
		C ) MESSAGe+="$LINK cuffs";;
		f ) MESSAGE+="$LINK foreplay";;
		l ) MESSAGE+="$LINK leather";;
		o ) MESSAGE+="$LINK oral";;
		O ) MESSAGE+="$LINK orgy";;
		q ) MESSAGE="[auto] Wanna have a quickie";;
		S ) MESSAGE+="$LINK sundae";;
		w ) MESSAGE+="$LINK whipped cream";;
		W ) MESSAGE+="$LINK whips";;
	esac
	LINK=" and"
done

MESSAGE+="?"

lua /path/to/scripts/send_jabber.lua $GF_JABBER "$MESSAGE"
