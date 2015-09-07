<?php
	require('init.php');

	render('dashboard', array('regularInfos' => $regularInfos, 'regularPlayers' => $regularPlayers, 'supertanksInfos' => $supertanksInfos, 'supertanksPlayers' => $supertanksPlayers, 'voiceUsers' => $voiceUsers));
?>