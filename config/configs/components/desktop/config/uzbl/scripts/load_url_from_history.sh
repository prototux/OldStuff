#!/bin/sh

readonly DMENU_SCHEME="history"
readonly DMENU_OPTIONS="xmms vertical resize"

. "$UZBL_UTIL_DIR/dmenu.sh"
. "$UZBL_UTIL_DIR/uzbl-dir.sh"
. "$UZBL_UTIL_DIR/uzbl-util.sh"

[ -r "$UZBL_HISTORY_FILE" ] || exit 1

# choose from all entries, sorted and uniqued
if $DMENU_HAS_VERTICAL; then
    # choose an item in reverse order, showing also the date and page titles
    # pick the last field from the first 3 fields. this way you can pick a url (prefixed with date & time) or type just a new url.
    goto="$( tac "$UZBL_HISTORY_FILE" | $DMENU | cut -d ' ' -f -3  | awk '{ print $NF }' )"
else
    readonly current="$( tail -n 1 "$UZBL_HISTORY_FILE" | cut -d ' ' -f 3 )"
    goto="$( ( print "$current\n"; cut -d ' ' -f 3 < "$UZBL_HISTORY_FILE" | grep -v -e "^$current\$" | sort -u ) | $DMENU )"
fi
readonly goto

[ -n "$goto" ] && uzbl_control "uri $goto\n"
