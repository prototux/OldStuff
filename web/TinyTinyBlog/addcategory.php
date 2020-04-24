<?php
	require('init.php');

	if (!isAdmin())
		render('403');

	if (getVar('name'))
	{
		//Insert and get the ID
		$addCategory = $dbh->prepare("INSERT INTO categories (name) VALUES (:name)");
		$addCategory->execute(array(':name' => getVar('name')));

		$articlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id ORDER BY articles.id DESC LIMIT 4 ");
		$articlesQuery->execute();
		$articles = $articlesQuery->fetchAll();

		$allArticlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id");
		$allArticlesQuery->execute();

		$maxPages = round($allArticlesQuery->rowCount()/4);

		$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
		$categoriesQuery->execute();
		$categories = $categoriesQuery->fetchAll();

		$page = 1;

		render('index', array('articles' => $articles, 'categories' => $categories, 'url' => 'index.php',  'npage' => $page, 'maxPages' => $maxPages));
	}

	$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
	$categoriesQuery->execute();
	$categories = $categoriesQuery->fetchAll();

	render('addcategory', array('categories' => $categories));
?>
