<?php
	require('init.php');

	if (!isAdmin())
		render('403');

	if (getVar('delete'))
	{
		//Delete gallery and all related pictures
		$delGallery = $dbh->prepare("DELETE FROM galleries WHERE id = :id");
		$delGallery->execute(array(':id' => getVar('delete')));
		$delPictures = $dbh->prepare("DELETE FROM pictures WHERE gallery_id = :id");
		$delPictures->execute(array(':id' => getVar('delete')));

		//Delete files
		delDir('images/galleries/'.getVar('delete'));

		//Render pictures like pictures.php
		$galleriesQuery = $dbh->prepare("SELECT id, title, description FROM galleries WHERE private = 0 OR private = :private");
		$galleriesQuery->execute(array('private' => isGuest()));
		$galleries = $galleriesQuery->fetchAll();

		render('pictures', array('galleries' => $galleries));
	}

	$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
	$categoriesQuery->execute();
	$categories = $categoriesQuery->fetchAll();

	render('delgallery', array('categories' => $categories));
?>
