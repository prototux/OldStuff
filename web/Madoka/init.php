<?php
	require('config.php');
	session_start();

	// Connect to DB and set some parameters...
	$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	// Debug settings
	if (DEBUG)
	{
		ini_set('display_errors', 'On');
		error_reporting(E_ALL ^ E_NOTICE);
	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	// Footer queries
	$linksQuery = $dbh->prepare("SELECT id, name, url FROM links ORDER BY RAND() LIMIT 8");
	$linksQuery->execute();
	$links = $linksQuery->fetchAll();
	$linksQuery->closeCursor();
	$linksLists = array_chunk($links, 4);

	$lastArticlesQuery = $dbh->prepare("SELECT id, title, description FROM blog_articles ORDER BY id DESC LIMIT 3");
	$lastArticlesQuery->execute();
	$lastArticles = $lastArticlesQuery->fetchAll();
	$lastArticlesQuery->closeCursor();

	//Useful functions.
	require('lib.php');
?>
