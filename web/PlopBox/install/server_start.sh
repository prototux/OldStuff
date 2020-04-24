#!/bin/sh
if ! screen -list | grep -q "l4d2_regular"; then
	echo -n "Starting server..."
	cd /path/to/l4d2/regular/left4dead2
		screen -dmS l4d2_regular ./srcds_run -game left4dead2 -secure -maxplayers 4 -z_difficulty hard -ip 212.83.147.36 -port 27015
	cd /path/to/l4d2/supertanks/left4dead2
		screen -dmS l4d2_supertanks ./srcds_run -game left4dead2 -insecure -maxplayers 4 -z_difficulty hard -ip 212.83.147.36 -port 27016
	cd ../
	echo "ok"
else
	echo "Server is already running"
fi

