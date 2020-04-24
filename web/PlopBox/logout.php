<?php
	session_start();
	unset($_SESSION['id']);
	unset($_SESSION['login']);
	unset($_SESSION['email']);
	unset($_SESSION['level']);
	session_destroy();
	header('location: index.php');
?>