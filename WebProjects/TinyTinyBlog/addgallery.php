<?php
	require('init.php');

	if (getVar('title'))
	{
		$addGallery = $dbh->prepare("INSERT INTO galleries (title, description, private) VALUES (:title, :description, :private)");
		$addGallery->execute(array(':title' => getVar('title'), ':description' => getVar('description'), ':private', getVar('privacy')));
		$galleryId = $dbh->lastInsertId();
		mkdir('images/galleries/'.$galleryId);
		render('addpictures', array('galleryId' => $galleryId));
	}
	else if (getVar('gallery_id'))
	{
		$galleriesQuery = $dbh->prepare("SELECT id, title, description FROM galleries WHERE private = 0 OR private = :private");
		$galleriesQuery->execute(array('private' => isGuest()));
		$galleries = $galleriesQuery->fetchAll();

		render('pictures', array('galleries' => $galleries));
	}
	else
		render('addgallery');
?>
