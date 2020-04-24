<?php
	require('init.php');

	if (!$_SESSION['id'])
		render('forbidden');

	$sth = $dbh->prepare("SELECT email FROM users WHERE id = :id");
	$sth->execute(array(':id' => $_SESSION['id']));
	$email = $sth->fetch()['email'];

	//Profil change
	if (getVar('email'))
	{
		//Password change
		if (getVar('oldPassword'))
		{
			if (getVar('newPassword1') != getVar('newPassword2'))
				render('profil', array('script' => 'toastr.error(\'Les deux mots de passe sont differents\', \'Erreur\');', 'email' => $email));

			$sth = $dbh->prepare("SELECT id FROM users WHERE id = :id AND password = :password");
			$sth->execute(array(':id' => $_SESSION['id'], ':password' => passwordHash(getVar('oldPassword'))));
			$user = $sth->fetch();
		 	if (!$sth->rowCount())
				render('profil', array('script' => 'toastr.error(\'Le mot de passe est errone\', \'Erreur\');', 'email' => $email));
			else
			{
				$sth = $dbh->prepare("UPDATE users SET email = :email, password = :password WHERE id = :id LIMIT 1");
				$sth->execute(array(':id' => $_SESSION['id'], ':email' => getVar('email'), ':password' => passwordHash(getVar('newPassword1'))));
				render('profil', array('script' => 'toastr.success(\'Le mot de passe a bien ete change\', \'Profil modifie\');', 'email' => $email));
			}
		}
		else
		{
			$sth = $dbh->prepare("UPDATE users SET email = :email WHERE id = :id LIMIT 1");
			$sth->execute(array(':id' => $_SESSION['id'], ':email' => getVar('email')));
			render('profil', array('script' => 'toastr.success(\'L\'email a bien ete change\', \'Profil modifie\');', 'email' => $email));

		}
	}

	render('profil', array('script' => null, 'email' => $email));
?>
