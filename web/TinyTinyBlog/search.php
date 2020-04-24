<?php

	require('init.php');

	$articlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id WHERE (articles.title LIKE CONCAT('%', :searchTitle ,'%') OR articles.description LIKE CONCAT('%', :searchDescription ,'%') OR articles.content LIKE CONCAT('%', :searchContent ,'%')) AND articles.private = 0 OR articles.private = :private LIMIT :start,4 ");
	$articlesQuery->execute(array(':start' => getVar('page')*4, ':private' => isGuest(), ':searchTitle' => getVar('search'), ':searchDescription' => getVar('search'), ':searchContent' => getVar('search')));
	$articles = $articlesQuery->fetchAll();

	$allArticlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id WHERE (articles.title LIKE CONCAT('%', :searchTitle ,'%') OR articles.description LIKE CONCAT('%', :searchDescription ,'%') OR articles.content LIKE CONCAT('%', :searchContent ,'%')) AND articles.private = 0 OR articles.private = :private");
	$allArticlesQuery->execute(array('private' => isGuest(), ':searchTitle' => getVar('search'), ':searchDescription' => getVar('search'), ':searchContent' => getVar('search')));

	$maxPages = round($allArticlesQuery->rowCount()/4);

	$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
	$categoriesQuery->execute();
	$categories = $categoriesQuery->fetchAll();

	$page = (getVar('page'))? getVar('page') : 1;

	render('index', array('articles' => $articles, 'categories' => $categories, 'url' => 'search.php', 'npage' => $page, 'maxPages' => $maxPages));
?>