<?php
	require('init.php');

	if (getVar('search'))
	{
		$mapsQuery = $dbh->prepare("SELECT id, name, type, file_rar, file_vpk, size_rar, size_vpk, note, UNIX_TIMESTAMP(date) AS date FROM maps WHERE name LIKE CONCAT('%',:search,'%') ORDER BY name");
		$mapsQuery->execute(array(':search' => getVar('search')));
	}
	else
	{
		$mapsQuery = $dbh->prepare("SELECT id, name, type, file_rar, file_vpk, size_rar, size_vpk, note, UNIX_TIMESTAMP(date) AS date FROM maps ORDER BY name");
		$mapsQuery->execute();
	}
	$maps = $mapsQuery->fetchAll();
	$mapsQuery->closeCursor();
	render('maps', array('maps' => $maps));
?>
