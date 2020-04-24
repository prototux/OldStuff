<?php
	require('init.php');

	if (!isAdmin())
		render('403');

	if (getVar('delete'))
	{
		//Insert and get the ID
		$updateArticles = $dbh->prepare("UPDATE articles SET category_id = 0 WHERE category_id = :id");
		$updateArticles->execute(array(':id' => getVar('delete')));
		$delCategory = $dbh->prepare("DELETE FROM categories WHERE id = :id");
		$delCategory->execute(array(':id' => getVar('delete')));

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

	render('delcategory', array('categories' => $categories));
?>
