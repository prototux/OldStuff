<?php
	require('init.php');

	if (!$_SESSION['id'])
		render('forbidden');

	$id = (getVar('id'))?getVar('id'):1;

        if (getVar('deleteTopic'))
        {
                $checkQuery = $dbh->prepare('SELECT COUNT(id) AS count FROM threads WHERE id = :id AND creator_id = :user_id');
                $checkQuery->execute(array(':id' => getVar('deleteTopic'), ':user_id' => $_SESSION['id']));
                $check = $checkQuery->fetch()['count'];
                $checkQuery->closeCursor();

                if (!$check)
                        render('forbidden');
                else
                {
                        $deleteQuery= $dbh->prepare('DELETE FROM threads WHERE id = :id');
                        $deleteQuery->execute(array(':id' => getVar('deleteTopic')));
                        $deleteQuery->closeCursor();

                        $deleteMessagesQuery= $dbh->prepare('DELETE FROM messages WHERE thread_id = :id');
                        $deleteMessagesQuery->execute(array(':id' => getVar('deleteTopic')));
                        $deleteMessagesQuery->closeCursor();
                }
        }

	if (getVar('message') && getVar('title'))
	{
		$addThreadQuery = $dbh->prepare("INSERT INTO threads (category_id, creator_id, title) VALUES (:id, :uid, :title)");
		$addThreadQuery->execute(array(':id' => $id, ':uid' => $_SESSION['id'], ':title' => getVar('title')));
		$addThreadQuery->closeCursor();

		$threadId = $dbh->lastInsertId();
		$addMessageQuery = $dbh->prepare("INSERT INTO messages (thread_id, user_id, content, date) VALUES (:id, :uid, :content, NOW())");
		$addMessageQuery->execute(array(':id' => $threadId, ':uid' => $_SESSION['id'], ':content' => nl2br(getVar('message'))));
		$addMessageQuery->closeCursor();
	}

	$forumsQuery = $dbh->prepare("SELECT id, name, description FROM forums");
	$forumsQuery->execute();
	$forums = $forumsQuery->fetchAll();
	$forumsQuery->closeCursor();

	$threadsQuery = $dbh->prepare("SELECT threads.id as id, threads.title as title, users.login as creator, UNIX_TIMESTAMP(MAX(messages.date)) AS lastDate FROM threads JOIN users ON threads.creator_id = users.id JOIN messages ON messages.thread_id = threads.id JOIN users AS lastuser ON lastuser.id = messages.user_id WHERE threads.category_id = :id GROUP BY threads.id ORDER BY lastDate DESC");
	$threadsQuery->execute(array(':id' => $id));
	$threads = $threadsQuery->fetchAll();
	$threadsQuery->closeCursor();

	//Get last message's user
	$lastMessageQuery = $dbh->prepare("SELECT users.login as user FROM messages JOIN users ON messages.user_id = users.id WHERE messages.thread_id = :id ORDER BY messages.id DESC LIMIT 1");
	foreach($threads as $key => $thread)
	{
		$lastMessageQuery->execute(array(':id' => $thread['id']));
		$lastMessage = $lastMessageQuery->fetch();
		$threads[$key]['lastUser'] = $lastMessage['user'];
		$lastMessageQuery->closeCursor();
	}

	render('forum', array('forums' => $forums, 'threads' => $threads, 'forumid' => $id));
?>
