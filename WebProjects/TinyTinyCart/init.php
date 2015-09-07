<?php
	require('config.php');
	session_start();

	//Connect to DB and set some parameters...
	$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	//Debug settings
	if (TC_DEBUG)
	{
		ini_set('display_errors', 'On');
		error_reporting(E_ALL ^ E_NOTICE);
	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	//Token init
	if (!$_SESSION['token'])
		$_SESSION['token'] = initToken();

	//Useful functions.
	require('lib.php');
?>