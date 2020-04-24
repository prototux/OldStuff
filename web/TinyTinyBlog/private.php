<?php
	require('init.php');

	$passwordFailed = false;

	if (getVar('deco'))
		setcookie('privateKey', '', time()-3600);

	if (getVar('password'))
	{
		if (getVar('password') == PASSWORD_GUEST || getVar('password') == PASSWORD_ADMIN)
			$private_access = true;
		else
			$passwordFailed = true;
		if (getVar('password') == PASSWORD_ADMIN)
			$admin_access = true;

		if ($private_access)
		{
			setcookie('privateKey', ($admin_access)? PRIVATE_KEY_ADMIN : PRIVATE_KEY_GUEST, time()+3600*24*30*365);
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

			render('index', array('articles' => $articles, 'categories' => $categories, 'url' => 'index.php', 'npage' => $page, 'maxPages' => $maxPages, 'private_access' => $private_access, 'admin_access' => $admin_access));
		}
	}

	$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
	$categoriesQuery->execute();
	$categories = $categoriesQuery->fetchAll();

	render('private', array('categories' => $categories, 'passwordFailed' => $passwordFailed));
?>