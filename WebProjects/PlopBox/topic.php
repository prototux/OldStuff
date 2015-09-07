<?php
	require('init.php');

	if (!$_SESSION['id'])
		render('forbidden');

	if (getVar('deleteMessage'))
	{
		$checkQuery = $dbh->prepare('SELECT COUNT(id) AS count FROM messages WHERE id = :id AND user_id = :user_id');
		$checkQuery->execute(array(':id' => getVar('deleteMessage'), ':user_id' => $_SESSION['id']));
		$check = $checkQuery->fetch()['count'];
		$checkQuery->closeCursor();

		if (!$check)
			render('forbidden');
		else
		{
			$deleteQuery= $dbh->prepare('DELETE FROM messages WHERE id = :id');
			$deleteQuery->execute(array(':id' => getVar('deleteMessage')));
			$deleteQuery->closeCursor();
		}
	}
	else if (getVar('editMessage'))
	{
		$checkQuery = $dbh->prepare('SELECT COUNT(id) AS count FROM messages WHERE id = :id AND user_id = :user_id');
		$checkQuery->execute(array(':id' => getVar('editMessage'), ':user_id' => $_SESSION['id']));
		$check = $checkQuery->fetch()['count'];
		$checkQuery->closeCursor();

		if (!$check)
				render('forbidden');
		else
		{
			$editQuery= $dbh->prepare('UPDATE messages SET content = :content, date = NOW() WHERE id = :id');
			$editQuery->execute(array(':content' => nl2br(parseUrl(getVar('message'))), ':id' => getVar('editMessage')));
			$editQuery->closeCursor();
		}
	}
	else if (getVar('message'))
	{
		$addMessageQuery = $dbh->prepare("INSERT INTO messages (thread_id, user_id, content, date) VALUES (:id, :uid, :content, NOW())");
		$addMessageQuery->execute(array(':id' => getVar('id'), ':uid' => $_SESSION['id'], ':content' => nl2br(parseUrl(getVar('message')))));
	}

	$forumsQuery = $dbh->prepare("SELECT id, name, description FROM forums");
	$forumsQuery->execute();
	$forums = $forumsQuery->fetchAll();
	$forumsQuery->closeCursor();

	$forumQuery = $dbh->prepare("SELECT forums.id FROM forums JOIN threads ON threads.category_id = forums.id WHERE threads.id = :id");
	$forumQuery->execute(array(':id' => getVar('id')));
	$forumid = $forumQuery->fetch()['id'];
	$forumQuery->closeCursor();

	$messagesQuery = $dbh->prepare("SELECT messages.id AS id, messages.content as content, UNIX_TIMESTAMP(messages.date) as date, users.login as user FROM messages JOIN users ON messages.user_id = users.id WHERE messages.thread_id = :id ORDER BY messages.id");
	$messagesQuery->execute(array(':id' => getVar('id')));
	$messages = $messagesQuery->fetchAll();
	$messagesQuery->closeCursor();

	$threadQuery = $dbh->prepare("SELECT id, title, category_id, creator_id FROM threads WHERE id = :id");
	$threadQuery->execute(array(':id' => getVar('id')));
	$thread = $threadQuery->fetch();
	$threadQuery->closeCursor();

	render('topic', array('forums' => $forums, 'forumid' => $forumid, 'messages' => $messages, 'thread' => $thread));
?>
