<?php
	include('init.php');
	if (!getVar('search'))
		render('error', array('error'=>'You must search something'));
	else
	{
		$configQuery = $dbh->prepare("SELECT * FROM products WHERE upper(name) LIKE concat('%', upper(:searchName), '%') OR upper(description) LIKE concat('%', upper(:searchDesc), '%')");
		$configQuery->execute(array(':searchName'=>getVar('search'),':searchDesc'=>getVar('search')));
	 	$productInfos = $configQuery->fetchAll();
		render('search', $productInfos);
	}
?>