<?php
	require('init.php');

	if (!isAdmin())
		render('403');

	if (getVar('privacy') || getVar('content'))
	{
		//Edit the article...
		$editArticle = $dbh->prepare("UPDATE articles SET title = :title, category_id = :category_id, private = :private, description = :description, content = :content");
		$editArticle->execute(array(':title' => getVar('title'), ':category_id' => getVar('category_id'), ':private' => getVar('privacy'), 'description' => getVar('description'), ':content' => getVar('content', false)));

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
	else
	{
		$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
		$categoriesQuery->execute();
		$categories = $categoriesQuery->fetchAll();

		$articleQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.private, articles.description, articles.content, articles.category_id, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id WHERE articles.id = :id");
		$articleQuery->execute(array('id' => getVar('id')));
		$article = $articleQuery->fetch();

		render('editarticle', array('categories' => $categories, 'article' => $article));
	}
?>
