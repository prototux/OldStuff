<?php
	require('init.php');

	$galleriesQuery = $dbh->prepare("SELECT id, title, description FROM galleries WHERE private = 0 OR private = :private");
	$galleriesQuery->execute(array('private' => isGuest()));
	$galleries = $galleriesQuery->fetchAll();

	render('pictures', array('galleries' => $galleries));
?>
