<?php
	require('init.php');

	if (!isLogged())
		header('Location: login.php');

	render('index');
?>
