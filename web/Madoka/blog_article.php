<?php
	require('init.php');

	if (getVar('name') && getVar('email') && getVar('comment'))
	{
		$addCommentQuery = $dbh->prepare("INSERT INTO blog_comments (article_id, nickname, email, content, timestamp) VALUES (:article_id, :name, :email, :comment, NOW())");
		$addCommentQuery->execute(array(':article_id' => getVar('id'), ':name' => getVar('name'), ':email' => getVar('email'), ':comment' => getVar('comment')));
		$addCommentQuery->closeCursor();
	}

	$articleQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.content, blog_categories.name AS category, COUNT(blog_comments.id) AS nb_comments, UNIX_TIMESTAMP(blog_articles.timestamp) AS timestamp FROM blog_articles LEFT JOIN blog_categories ON blog_articles.category_id = blog_categories.id LEFT JOIN blog_comments ON blog_comments.article_id = blog_articles.id WHERE blog_articles.id = :id");
	$articleQuery->execute(array('id' => getVar('id')));
	$article = $articleQuery->fetch();
	$articleQuery->closeCursor();

	$articleTagsQuery = $dbh->prepare("SELECT blog_tags.name FROM blog_tags INNER JOIN blog_articles_tags ON blog_articles_tags.tag_id = blog_tags.id WHERE blog_articles_tags.article_id = :id");
	$articleTagsQuery->execute(array('id' => getVar('id')));
	$articleTags = $articleTagsQuery->fetchAll();
	$articleTagsQuery->closeCursor();

	$commentsQuery = $dbh->prepare("SELECT nickname, email, content, UNIX_TIMESTAMP(timestamp) AS timestamp FROM blog_comments WHERE article_id = :id");
	$commentsQuery->execute(array('id' => getVar('id')));
	$comments = $commentsQuery->fetchAll();
	$commentsCount = $commentsQuery->rowCount();

	$categoriesQuery = $dbh->prepare("SELECT id, name FROM blog_categories ");
	$categoriesQuery->execute();
	$categories = $categoriesQuery->fetchAll();
	$categoriesQuery->closeCursor();

	$tagsQuery = $dbh->prepare("SELECT id, name FROM blog_tags ");
	$tagsQuery->execute();
	$tags = $tagsQuery->fetchAll();
	$tagsQuery->closeCursor();

	render('blog-article', array('article' => $article, 'articleTags' => $articleTags, 'categories' => $categories, 'tags' => $tags, 'comments' => $comments, 'commentsCount' => $commentsCount));
?>
