<?php
	include('init.php');
	if (!getVar('id'))
		render('error', array('error'=>'No category selected'));

	//Get products and category name
	$vars = array();
	$productsQuery = $dbh->prepare("SELECT * FROM products WHERE category_id = :id");
	$productsQuery->execute(array(':id'=>getVar('id')));
 	$products = $productsQuery->fetchAll();
 	$vars['products'] = $products;
 	$categoryQuery = $dbh->prepare("SELECT name FROM categories WHERE id = :id");
	$categoryQuery->execute(array(':id'=>getVar('id')));
 	$category = $categoryQuery->fetchAll()[0]['name'];

 	$vars['name'] = $category;
	render('category', $vars);
?>