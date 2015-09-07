<?php
require_once('init.php');
if (!$_SESSION['id'] && getVar('action') == 'edit')
	render('error', array('error'=>'You must be logged first'));

//Just rendering a page...
switch (getVar('action'))
{
	case 'signup':
		if (getVar('email'))
		{
			$addUser= $dbh->prepare("INSERT INTO users (username, password, email, firstname, lastname, address, postalcode, city, phone) VALUES (:username, :password, :email, :firstname, :lastname, :address, :postalcode, :city, :phone)");
			$addUser->execute(array(':username' => getVar('username'), ':password' => passwordHash(getVar('password')), ':email' => getVar('email'), ':firstname' => getVar('firstname'), ':lastname' => getVar('lastname'), ':address' => getVar('address'), ':postalcode' => getVar('postalcode'), ':city' => getVar('city'), ':phone' => getVar('phone')));
			renderHome('accountCreated', true);
		}
		else
			render('user-signup');
	break;
	case 'reset':
		if(getVar('email'))
		{
			$userQuery = $dbh->prepare("SELECT id FROM users WHERE email = :email");
			$userQuery->execute(array(':email' => getVar('email')));
		 	if ($userQuery->rowCount())
		 	{
				$newPass = genPassword();
				mail(getVar('email'), 'Your new password on '.getConfigKey('title'), 'Your new password is '.$newPass);
				$resetQuery = $dbh->prepare("UPDATE users SET password = :password WHERE email = :email LIMIT 1");
				$resetQuery->execute(array(':password' => passwordHash($newPass),':email' => getVar('email')));
				render('user-reset', array());
			}
			else
				render('error', array('error' => 'No account was found.'));
		}
		else
			render('user-reset');
	break;
	case 'login':
		$loginQuery = $dbh->prepare("SELECT id, firstname, lastname, username, email, address, city, postalcode, phone FROM users WHERE username = :username AND password = :password");
		$loginQuery->execute(array(':username' => getVar('username'), ':password' => passwordHash(getVar('password'))));
		$user = $loginQuery->fetchAll()[0];
	 	if ($loginQuery->rowCount())
	 	{
	 		foreach($user as $key=>$value)
				$_SESSION[$key] = $value;
	 		renderHome();
	 	}
	 	else
	 		renderHome('loginFailed', true);
	break;
	case 'edit':
		if (getVar('email'))
		{
			$userQuery = $dbh->prepare("UPDATE users SET email = :email, firstname = :firstname, lastname = :lastname, address = :address, city = :city, postalcode = :postalcode, phone = :phone WHERE id = :id LIMIT 1");
			$userQuery->execute(array(':id' => $_SESSION['id'], ':email' => getVar('email'), ':firstname' => getVar('firstname'), ':lastname' => getVar('lastname'), ':address' => getVar('address'), ':city' => getVar('city'), ':postalcode' => getVar('postalcode'), ':phone' => getVar('phone')));
			renderHome('userUpdated', true);
		}
		else
			render('user-edit', $_SESSION);
	break;
	case 'logout':
		session_unset();
		renderHome();
	break;
	default:
		render('error', array('error' => 'No action selected'));
	break;
}
?>