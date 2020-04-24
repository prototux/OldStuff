<?php
	require('init.php');

	if (getVar('category'))
	{
		// RIP monster query: $articlesQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.description, blog_categories.name AS category, comments.nb_comments, UNIX_TIMESTAMP(blog_articles.timestamp) AS timestamp FROM blog_articles INNER JOIN blog_categories ON blog_articles.category_id = blog_categories.id LEFT JOIN (SELECT blog_comments.article_id, COUNT(blog_comments.id) AS nb_comments FROM blog_comments WHERE blog_articles.id == blog_comments.article_id) AS comments ON comments.article_id = blog_articles.id WHERE blog_articles.category_id = :category ORDER BY blog_articles.id DESC LIMIT :start,:nb_articles");
		$articlesQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.description, blog_categories.name AS category, COUNT(blog_comments.id) AS nb_comments, UNIX_TIMESTAMP(blog_articles.timestamp) AS timestamp FROM blog_articles INNER JOIN blog_categories ON blog_articles.category_id = blog_categories.id LEFT JOIN blog_comments ON blog_comments.article_id = blog_articles.id WHERE blog_articles.category_id = :category GROUP BY blog_articles.id ORDER BY blog_articles.id DESC LIMIT :start,:nb_articles");
		$articlesQuery->execute(array(':category'=>getVar('category'), ':start' => getVar('page')*4, ':nb_articles' => 4));
		$articles = $articlesQuery->fetchAll();

		$allArticlesQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.description, blog_categories.name AS category, blog_articles.timestamp FROM blog_articles JOIN blog_categories ON blog_articles.category_id = blog_categories.id WHERE blog_articles.category_id = :category");
		$allArticlesQuery->execute(array('category' => getVar('category')));
	}
	else
	{
		// RIP monster query jr: $articlesQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.description, blog_categories.name AS category, comments.nb_comments, UNIX_TIMESTAMP(blog_articles.timestamp) AS timestamp FROM blog_articles INNER JOIN blog_categories ON blog_articles.category_id = blog_categories.id LEFT JOIN (SELECT blog_comments.article_id AS article_id, COUNT(blog_comments.id) AS nb_comments FROM blog_comments) AS comments ON comments.article_id = blog_articles.id ORDER BY blog_articles.id DESC LIMIT :start,4");
		$articlesQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.description, blog_categories.name AS category, COUNT(blog_comments.id) AS nb_comments, UNIX_TIMESTAMP(blog_articles.timestamp) AS timestamp FROM blog_articles INNER JOIN blog_categories ON blog_articles.category_id = blog_categories.id LEFT JOIN blog_comments ON blog_comments.article_id = blog_articles.id GROUP BY blog_articles.id ORDER BY blog_articles.id DESC LIMIT :start,:nb_articles");

		$articlesQuery->execute(array(':start' => getVar('page')*4, ':nb_articles' => 4));
		$articles = $articlesQuery->fetchAll();

		$allArticlesQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.description, blog_categories.name AS category, blog_articles.timestamp FROM blog_articles JOIN blog_categories ON blog_articles.category_id = blog_categories.id");
		$allArticlesQuery->execute();
	}

	$maxPages = round($allArticlesQuery->rowCount()/4);

	// This avoid having a last empty page if there's exactly ARTICLES_PER_PAGE articles on the last page.
	if (!($allArticlesQuery->rowCount()%4))
		$maxPages--;

	$categoriesQuery = $dbh->prepare("SELECT id, name FROM blog_categories ");
	$categoriesQuery->execute();
	$categories = $categoriesQuery->fetchAll();
	$categoriesQuery->closeCursor();

	$tagsQuery = $dbh->prepare("SELECT id, name FROM blog_tags ");
	$tagsQuery->execute();
	$tags = $tagsQuery->fetchAll();
	$tagsQuery->closeCursor();

	$page = (getVar('page'))? getVar('page') : 0;

	render('blog', array('articles' => $articles, 'categories' => $categories, 'tags' => $tags, 'npage' => $page, 'maxPages' => $maxPages));
?>
