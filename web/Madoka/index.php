<?php
	require('init.php');

	$carouselQuery = $dbh->prepare("SELECT id, title, description FROM carousel ORDER BY id DESC LIMIT 4");
	$carouselQuery->execute();
	$carousel = $carouselQuery->fetchAll();
	$carouselQuery->closeCursor();

	render('index', array('carousel' => $carousel));
?>
