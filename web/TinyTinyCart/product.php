<?php
	include('init.php');
	if (!getVar('id'))
		render('error', array('error'=>'No product selected'));

	$vars = array();
	$productsQuery = $dbh->prepare("SELECT * FROM products WHERE id = :id");
	$productsQuery->execute(array(':id'=>getVar('id')));
 	$productsInfos = $productsQuery->fetchAll();
 	$relatedQuery = $dbh->prepare("SELECT * FROM products WHERE category_id = :cid AND id != :pid ORDER BY rand() LIMIT 3");
	$relatedQuery->execute(array(':cid'=>$productsInfos[0]['category_id'], ':pid'=>getVar('id')));
	$relatedProducts = $relatedQuery->fetchAll();

	$vars['product'] = $productsInfos[0];
	$vars['related'] = $relatedProducts;
	render('product', $vars);
?>