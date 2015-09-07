<?php

	require('init.php');

	if (getVar('category'))
	{
		$articlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id WHERE articles.category_id = :category AND articles.private = 0 OR articles.private = :private ORDER BY articles.id DESC LIMIT :start,4 ");
		$articlesQuery->execute(array('category'=>getVar('category'), 'start' => getVar('page')*4, 'private' => isGuest()));
		$articles = $articlesQuery->fetchAll();

		$allArticlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id WHERE articles.category_id = :category AND articles.private = 0 OR articles.private = :private");
		$allArticlesQuery->execute(array('category' => getVar('category'), 'private' => isGuest()));
	}
	else
	{
		$articlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id WHERE articles.private = 0 OR articles.private = :private ORDER BY articles.id DESC LIMIT :start,4 ");
		$articlesQuery->execute(array('start' => getVar('page')*4, 'private' => isGuest()));
		$articles = $articlesQuery->fetchAll();

		$allArticlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id WHERE articles.private = 0 OR articles.private = :private");
		$allArticlesQuery->execute(array('private' => isGuest()));
	}

	$maxPages = round($allArticlesQuery->rowCount()/4);

	$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
	$categoriesQuery->execute();
	$categories = $categoriesQuery->fetchAll();

	$page = (getVar('page'))? getVar('page') : 1;

	render('index', array('articles' => $articles, 'categories' => $categories, 'url' => 'index.php', 'npage' => $page, 'maxPages' => $maxPages));
?>
