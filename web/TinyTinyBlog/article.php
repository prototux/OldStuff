<?php
	require('init.php');

	$articleQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.content, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id WHERE articles.id = :id");
	$articleQuery->execute(array('id' => getVar('id')));
	$article = $articleQuery->fetch();

	$commentsQuery = $dbh->prepare("SELECT nickname, email, comment, date FROM comments WHERE article_id = :id");
	$commentsQuery->execute(array('id' => getVar('id')));
	$comments = $commentsQuery->fetchAll();
	$commentsCount = $commentsQuery->rowCount();

	$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
	$categoriesQuery->execute();
	$categories = $categoriesQuery->fetchAll();

	render('article', array('article' => $article, 'categories' => $categories, 'comments' => $comments, 'commentsCount' => $commentsCount));
?>