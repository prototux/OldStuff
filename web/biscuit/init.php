<?php
	/***********************************************************************
	*  .--.              .-.   .-.  ****************************************
	* / _.-' .-.   .-.  | OO| | OO| *   Prototux's biscuit PHP framework   *
	* \  '-. '-'   '-'  |   | |   | *  2013 -- codeoverflow.org/p/biscuit  *
	*  '--'             '^^^' '^^^' ****************************************
	************************************************************************
	*  This program is free software. It comes without any warranty, to    *
	* the extent permitted by applicable law. You can redistribute it      *
	* and/or modify it under the terms of the Do What The Fuck You Want    *
	* To Public License, Version 2, as published by Sam Hocevar. See       *
	* http://www.wtfpl.net/ for more details.                              *
	***********************************************************************/

	require('config.php');
	session_start();

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

	//Useful functions.
	require('lib.php');
?>
