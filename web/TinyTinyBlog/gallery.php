<?php
	require('init.php');

	$galleryQuery = $dbh->prepare("SELECT id, private, title, description FROM galleries WHERE id = :id");
	$galleryQuery->execute(array(':id' => getVar('id')));
	$gallery = $galleryQuery->fetch();

	$picturesQuery = $dbh->prepare("SELECT id, number, gallery_id, name FROM pictures WHERE gallery_id = :id");
	$picturesQuery->execute(array(':id' => getVar('id')));
	$pictures = $picturesQuery->fetchAll();

	if ($gallery['private'] && !isGuest())
		render('403');
	else
		render('gallery', array('gallery' => $gallery, 'pictures' => $pictures));
?>
