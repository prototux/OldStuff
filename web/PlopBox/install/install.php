<?php
	require('../init.php');

	// Now untested! i need to setup a test server with it but too tired to do it now.
	if (getVar('login') && getVar('password') && getvar('email'))
	{
		// Create the tables
		$dbh->query('CREATE TABLE IF NOT EXISTS forums (id int(255) NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL, description varchar(255) NOT NULL, PRIMARY KEY (id))');
		$dbh->query('CREATE TABLE IF NOT EXISTS maps (id int(255) NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL, type varchar(255) NOT NULL, file_rar varchar(255) NOT NULL, file_vpk varchar(255) NOT NULL, size_rar int(255) NOT NULL, size_vpk int(255) NOT NULL, note int(255) NOT NULL, date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (id))');
		$dbh->query('CREATE TABLE IF NOT EXISTS messages (id int(255) NOT NULL AUTO_INCREMENT, thread_id int(255) NOT NULL, user_id int(255) NOT NULL, content text NOT NULL, date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (id))');
		$dbh->query('CREATE TABLE IF NOT EXISTS threads (id int(255) NOT NULL AUTO_INCREMENT, title varchar(255) NOT NULL, creator_id int(255) NOT NULL, category_id int(255) NOT NULL, PRIMARY KEY (id))');
		$dbh->query('CREATE TABLE IF NOT EXISTS users (id int(255) NOT NULL AUTO_INCREMENT, login varchar(255) NOT NULL, password varchar(512) NOT NULL, email varchar(255) NOT NULL, level int(255) NOT NULL DEFAULT '1', PRIMARY KEY (id)) ');

		// Create the root user
		$rootQuery = $dbh->prepare('INSERT INTO users (login, password, email, level) VALUES (:login, :password, :email, 3)');
		$rootQuery->execute(array(':login' => getVar('login'), ':password' => passwordHash(getVar('password'), ':email' => getVar('email'))))
	}
	else
	{
		?>
			<html><body><form action="install.php" method="post"><input type="text" name="login" placeholder="login" /><input type="password" name="password" placeholder="password" /><input type="text" name="email" placeholder="email" /><input type="submit" /></form></body></html>
		<?php
	}
?>