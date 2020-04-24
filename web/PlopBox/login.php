<?php
	require('init.php');

	//User already logged
	if (isset($_SESSION['id']))
		render('dashboard', array('regularInfos' => $regularInfos, 'regularPlayers' => $regularPlayers, 'supertanksInfos' => $supertanksInfos, 'supertanksPlayers' => $supertanksPlayers, 'voiceUsers' => $voiceUsers));

	if (getVar('login') && getVar('password'))
	{
		$sth = $dbh->prepare("SELECT id, login, email, level FROM users WHERE login = :login AND password = :password");
		$sth->execute(array(':login' => getVar('login'), ':password' => passwordHash(getVar('password'))));
		$user = $sth->fetchAll()[0];
	 	if ($sth->rowCount())
	 	{
	 		foreach($user as $key=>$value)
	 			$_SESSION[$key] = $value;
			render('dashboard', array('regularInfos' => $regularInfos, 'regularPlayers' => $regularPlayers, 'supertanksInfos' => $supertanksInfos, 'supertanksPlayers' => $supertanksPlayers, 'voiceUsers' => $voiceUsers));
	 	}
	 	else
	 		render('login', Array('logfailed'=>true));
	}
	else
		render('login', Array('logfailed'=>false));
?>