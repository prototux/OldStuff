<?php
	require('init.php');

	if (!$_SESSION['id'])
		render('forbidden');

    $checkQuery = $dbh->prepare('SELECT COUNT(id) AS count FROM messages WHERE id = :id AND user_id = :user_id');
    $checkQuery->execute(array(':id' => getVar('id'), ':user_id' => $_SESSION['id']));
    $check = $checkQuery->fetch()['count'];
    $checkQuery->closeCursor();
    if (!$check)
    	render('forbidden');


	$forumsQuery = $dbh->prepare("SELECT id, name, description FROM forums");
	$forumsQuery->execute();
	$forums = $forumsQuery->fetchAll();
	$forumsQuery->closeCursor();

	$messageQuery = $dbh->prepare("SELECT id, content FROM messages WHERE id = :id");
	$messageQuery->execute(array(':id' => getVar('id')));
	$message = $messageQuery->fetch();
	$messageQuery->closeCursor();

	render('message', array('forums' => $forums, 'message' => $message));
?>
