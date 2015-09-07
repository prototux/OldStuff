<?php
	//User and passwords related functions
	function initToken()
	{
		global $dbh;
		$token = md5(rand(0, 999999));
		$sth = $dbh->prepare("SELECT token FROM tokens WHERE token = :token");
		$sth->execute(array(':token'=>$token));
		while ($sth->rowCount())
		{
			$token = md5(rand(0, 999999));
			$sth->execute(array(':token'=>$token));
		}
		$sth = $dbh->prepare("INSERT INTO tokens (token) VALUES (:token)");
		$sth->execute(array(':token'=>$token));
		$sth = $dbh->prepare("INSERT INTO carts (token_id) VALUES (:token)");
		$sth->execute(array(':token'=>$dbh->lastInsertId('id')));
		return $token;
	}
	function genPassword()
	{
		$password = '';
		$possible = '012346789abcdfghjkmnpqrtvwxyzABCDFGHJKLMNPQRTVWXYZ!@#$%^&*()+=[]{}|.,';
		for ($i = 0;$i < 9;$i++)
		{
		  $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		  $password .= $char;
		}
		return $password;
	}
	function passwordHash($plain)
	{
		return hash('sha512', DB_SALT.$plain);
	}

	//Rendering related functions
	function debug($var, $die = true)
	{
		echo '<pre>';
		print_r($var);
		echo '</pre>';
		if ($die)
			die();
	}
	function renderHome($varName = null, $varValue = null)
	{
		//Render homepage (same a index.php)
		global $dbh;
		$vars = array();
		$fproductsQuery = $dbh->prepare("SELECT * FROM products WHERE is_featured = 1 LIMIT 4");
		$fproductsQuery->execute();
	 	$fproducts = $fproductsQuery->fetchAll();
	 	$lproductsQuery = $dbh->prepare("SELECT * FROM products ORDER BY date DESC LIMIT 4");
		$lproductsQuery->execute();
	 	$lproducts = $lproductsQuery->fetchAll();
	 	$vars['featured'] = $fproducts;
	 	$vars['latest'] = $lproducts;
	 	if ($varName)
	 		$vars[$varName] = $varValue;
		render('home', $vars);
	}
	function render($page, $vars = null)
	{
		global $dbh;
		$config = getConfig();
		if ($vars)
			extract($vars);
		include('tpl/head.php');
		include('tpl/header.php');
		$menuQuery = $dbh->prepare("SELECT * FROM categories");
		$menuQuery->execute();
	 	$menu = $menuQuery->fetchAll();
		include('tpl/menu.php');
		include('tpl/'.$page.'.php');
		include('tpl/foot.php');
		die();
	}

	//Vars and config related functions
	function getVar($varName)
	{
		return htmlentities(strip_tags(($_POST[$varName])?$_POST[$varName]:$_GET[$varName]));
	}
	function getConfig()
	{
		global $dbh;
		$config = array();
		$configQuery = $dbh->prepare("SELECT name, value FROM config");
		$configQuery->execute();
	 	while ($row = $configQuery->fetch())
	 		$config[$row['name']] = $row['value'];
		return $config;
	}
	function getConfigKey($key)
	{
		global $dbh;
		$configQuery = $dbh->prepare("SELECT name, value FROM config WHERE name = :name LIMIT 1");
		$configQuery->execute(array(':name'=>$key));
		$configKey = $configQuery->fetchAll()[0]['value'];
		return $configKey;
	}

?>