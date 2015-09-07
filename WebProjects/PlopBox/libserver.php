<?php
	require('srcds.class.php');
	require("ts3.class.php");

	//Get L4D2 servers infos
	$regular = new srcds(L4D2_SERVER, (int)L4D2_REGULAR);
	$supertanks = new srcds(L4D2_SERVER, (int)L4D2_SUPERTANKS);
	if ($regular->ping() && $supertanks->ping())
	{
	    $regularInfos = $regular->getInfos();
	    $regularPlayers = $regular->getPlayers();
	    $supertanksInfos = $supertanks->getInfos();
	    $supertanksPlayers = $supertanks->getPlayers();
	}
	else
	{
	    $regularInfos = null;
	    $regularPlayers = null;
	    $supertanksInfos = null;
	    $supertanksPlayers = null;
	}

	//Get TS3 server infos
	$voice = new ts3(TEAMSPEAK_CONSOLE_SERVER, TEAMSPEAK_CONSOLE_PORT, 1, TEAMSPEAK_CONSOLE_LOGIN, TEAMSPEAK_CONSOLE_PASSWORD);
	$voiceUsers = $voice->getUsers();
?>
