<?php
	include('init.php');
	if (!getVar('id'))
		render('error', array('error'=>'No page selected'));

	//Get products
	$cmsQuery = $dbh->prepare("SELECT * FROM cms WHERE id = :id");
	$cmsQuery->execute(array(':id'=>getVar('id')));
 	$cms = $cmsQuery->fetchAll()[0];
	render('cms', $cms);
?>