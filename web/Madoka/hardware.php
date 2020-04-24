<?php
	require('init.php');

	$toolsQuery = $dbh->prepare("SELECT id, name, smalldesc, description FROM tools ");
	$toolsQuery->execute();
	$tools = $toolsQuery->fetchAll();
	$toolsQuery->closeCursor();

	render('hardware', array('tools' => $tools));
?>
