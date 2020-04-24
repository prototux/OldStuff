<?php
	require('init.php');

	if (isLogged())
		header('Location: index.php');

	if (getVar('email') && getVar('password'))
	{
		$loginQuery = $dbh->prepare("SELECT id FROM users WHERE email = :email AND password = :password");
		$loginQuery->execute(array(':email' => getVar('email'), ':password' => passwordHash(getVar('password'))));
		$login = $loginQuery->fetch();
		$loginQuery->closeCursor();
		if ($loginQuery->rowCount())
		{
			$_SESSION['id'] = $login['id'];
			$rolesQuery = $dbh->prepare("SELECT is_admin, is_moderator, is_writer FROM users_roles WHERE user_id = :id");
			$rolesQuery->execute(array(':id' => $login['id']));
			$roles = $rolesQuery->fetch();
			$rolesQuery->closeCursor();
			$_SESSION['roles']['admin'] = $roles['is_admin'];
			$_SESSION['roles']['moderator'] = $roles['is_moderator'];
			$_SESSION['roles']['writer'] = $roles['is_writer'];
			header('Location: index.php');
		}
		else
			renderSingle('login', array('failedLogin' => true));
	}

	renderSingle('login', array('failedLogin' => false));
?>
