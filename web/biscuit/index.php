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
	************************************************************************
	* Example of use...                                                    *
	***********************************************************************/

	require('init.php');

	$who = 'world';
	render('index', array('who' => $who));
?>
