<?php
	require('config.php');
	session_start();
	ini_set('memory_limit', '-1');

	//Connect to DB and set some parameters...
	$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	//Debug settings
	if (DEBUG)
	{
		ini_set('display_errors', 'On');
		error_reporting(E_ALL ^ E_NOTICE);
	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	//Private management
	function isGuest()
	{
		return ($_COOKIE['privateKey'] == PRIVATE_KEY_GUEST || $_COOKIE['privateKey'] == PRIVATE_KEY_ADMIN);
	}

	function isAdmin()
	{
		return ($_COOKIE['privateKey'] == PRIVATE_KEY_ADMIN);
	}

	//Useful functions.
	require('lib.php');
?>
