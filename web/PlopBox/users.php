<?php
	require('init.php');

	if (!$_SESSION['id'] || $_SESSION['level'] != 3)
		render('forbidden');
	// Add/Edit an user
	if (getVar('action'))
		switch (getVar('action'))
		{
			case 'add':
				$password = genPassword();
				$addUserQuery = $dbh->prepare("INSERT INTO users (login, email, password) VALUES (:login, :email, :password)");
				$addUserQuery->execute(array(':login' => getVar('login'), ':email' => getVar('email'), ':password' => passwordHash($password)));
				mail(getVar('email'), 'Votre compte sur plopbox.zlock.eu', "Votre compte a ete cree sur plopbox.zlock.eu\nLogin: ".getVar('login')."\nPassword: ".$password, 'From: noreply@zlock.eu');
			break;
			case 'makeAdmin':
				$sth = $dbh->prepare("UPDATE users SET level = 2 WHERE id = :id LIMIT 1");
				$sth->execute(array(':id' => getVar('id')));
			break;
			case 'removeAdmin':
				$sth = $dbh->prepare("UPDATE users SET level = 1 WHERE id = :id LIMIT 1");
				$sth->execute(array(':id' => getVar('id')));
			break;
			case 'ban':
				$sth = $dbh->prepare("UPDATE users SET level = 0 WHERE id = :id LIMIT 1");
				$sth->execute(array(':id' => getVar('id')));
			break;
			case 'genPassword':
				$password = genPassword();
				$sth = $dbh->prepare("UPDATE users SET password = :password WHERE id = :id LIMIT 1");
				$sth->execute(array(':id' => getVar('id'), ':password' => passwordHash($password)));
				mail(getVar('email'), 'Votre nouveau mot de passe sur plopbox.zlock.eu', "Votre nouveau mot de passe sur plopbox.zlock.eu est: ".$password, 'From: noreply@zlock.eu');
			break;
		}

	if (!$_SESSION['id'] && $_SESSION['id'] != 1)
		render('forbidden');

	$userssQuery = $dbh->prepare("SELECT id, login, email, level FROM users");
	$userssQuery->execute();
	$users = $userssQuery->fetchAll();

	// 0 = banned // 1 = user // 2 = admin // 3 = superadmin
	$levels = array('<span class="label label-important">Banni</span>', '<span class="label">Normal</span>', '<span class="label label-success">Admin serveur</span>', '<span class="label label-inverse">Root</span>');

	render('users', array('users' => $users, 'levels' => $levels));
?>