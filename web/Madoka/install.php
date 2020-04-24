<?php
	require('config.php');

	// Tables a inserer
	$insert['blog_articles'] = 'CREATE TABLE blog_articles (id int(255) NOT NULL AUTO_INCREMENT, category_id int(255) NOT NULL, title varchar(255) NOT NULL, description varchar(255) NOT NULL, content text NOT NULL, timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (id))';
	$insert['blog_articles_tags'] = 'CREATE TABLE blog_articles_tags (id int(255) NOT NULL AUTO_INCREMENT, article_id int(255) NOT NULL, tag_id int(255) NOT NULL, PRIMARY KEY (id))';
	$insert['blog_categories'] = 'CREATE TABLE blog_categories (id int(255) NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL, PRIMARY KEY (id))';
	$insert['blog_comments'] = 'CREATE TABLE blog_comments (id int(255) NOT NULL AUTO_INCREMENT, article_id int(255) NOT NULL, nickname varchar(255) NOT NULL, email varchar(255) NOT NULL, content text NOT NULL, timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (id))';
	$insert['blog_tags'] = 'CREATE TABLE blog_tags (id int(255) NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL, PRIMARY KEY (id))';
	$insert['carousel'] = 'CREATE TABLE carousel (id int(255) NOT NULL AUTO_INCREMENT, title varchar(255) NOT NULL, description varchar(255) NOT NULL, PRIMARY KEY (id))';
	$insert['links'] = 'CREATE TABLE links (id int(255) NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL, url varchar(255) NOT NULL, PRIMARY KEY (id))';
	$insert['tools'] = 'CREATE TABLE tools (id int(255) NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL, smalldesc varchar(255) NOT NULL, description text NOT NULL, PRIMARY KEY (id))';
	$insert['users'] = 'CREATE TABLE users (id int(255) NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL, email varchar(255) NOT NULL, password varchar(255) NOT NULL, PRIMARY KEY (id))';
	$insert['users_roles'] = 'CREATE TABLE users_roles (id int(255) NOT NULL AUTO_INCREMENT, user_id int(255) NOT NULL, is_admin tinyint(1) NOT NULL, is_writer tinyint(1) NOT NULL, is_moderator tinyint(1) NOT NULL, PRIMARY KEY (id))';
	$insert['wiki_categories'] = 'CREATE TABLE wiki_categories (name varchar(255) NOT NULL, PRIMARY KEY (name))';
	$insert['wiki_pages'] = 'CREATE TABLE wiki_pages (name varchar(255) NOT NULL, PRIMARY KEY (name))';
	$insert['wiki_edits'] = 'CREATE TABLE wiki_edits (id int(255) NOT NULL AUTO_INCREMENT, page_name varchar(255) NOT NULL, from_ip varchar(255) NOT NULL, content text NOT NULL, description varchar(255) NOT NULL, timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (id), KEY page_name (page_name), CONSTRAINT wiki_edits_ibfk_1 FOREIGN KEY (page_name) REFERENCES wiki_pages (name) ON DELETE CASCADE)';
	$insert['wiki_categories_pages'] = 'CREATE TABLE wiki_categories_pages (id int(11) NOT NULL AUTO_INCREMENT, page_name varchar(255) NOT NULL, category_name varchar(255) NOT NULL, PRIMARY KEY (id), KEY page_name (page_name), KEY category_name (category_name), CONSTRAINT wiki_categories_pages_ibfk_1 FOREIGN KEY (page_name) REFERENCES wiki_pages (name) ON DELETE CASCADE, CONSTRAINT wiki_categories_pages_ibfk_2 FOREIGN KEY (category_name) REFERENCES wiki_categories (name) ON DELETE CASCADE)';

	// User a inserer
	$admin['name'] = 'admin name';
	$admin['email'] = 'email@website.org';
	$admin['password'] = 'password';

	echo '<html><head><title>Installation de Madoka</title><body><h1>Installer Madoka</h1>';

	echo 'Test de la connection... ';
	try
	{
		$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
		$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		ini_set('display_errors', 'On');
		error_reporting(E_ALL ^ E_NOTICE);
	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $ex)
	{
    	die('FAIL<br />Les informations dans config.php sont erronees');
	}
	echo 'OK<br />';


	echo 'Ajout des tables... (';
	foreach($insert AS $name => $content)
	{
		try
		{
			$dbh->query($content);
		}
		catch(PDOException $ex)
		{
			die(') FAIL<br />Erreur lors de l\'ajout de la table '.$name.': '.$ex->getMessage());
		}
		echo $name.' ';
	}
	echo ') OK<br />';

	echo 'Ajout de l\'admin ('.$admin['email'].' // '.$admin['password'].')...';
	try
	{
		$addAdminQuery = $dbh->prepare('INSERT INTO users (id, name, email, password) VALUES (1, :name, :email, :password)');
		$addAdminQuery->execute(array(':name' => $admin['name'], ':email' => $admin['email'], ':password' => hash('sha1', SALT.$admin['password'])));
	}
	catch(PDOException $ex)
	{
		die(') FAIL<br />Erreur lors de l\'ajout de l\'admin: '.$ex->getMessage());
	}
	echo 'OK<br />';

	echo 'Installation finie!<br /><strong>PENSER A SUPPRIMER install.php!</strong></body></html>';
?>
