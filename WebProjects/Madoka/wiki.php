<?php
	require('init.php');
	require('parsedown.php');

	if (getVar('edit'))
		$pageUrl = getVar('edit');
	else
		$pageUrl = (getVar('page'))? getVar('page') : 'Acceuil';

	$pageQuery = $dbh->prepare("SELECT wiki_pages.name AS name, wiki_edits.content AS content FROM wiki_pages JOIN wiki_edits ON wiki_edits.page_name = wiki_pages.name WHERE wiki_pages.name = :name ORDER BY wiki_edits.timestamp DESC LIMIT 1");
	$pageQuery->execute(array(':name' => $pageUrl));
	$page = $pageQuery->fetch();
	$pageQuery->closeCursor();

	if (empty($page))
		$page = array('name' => str_replace('_',' ', $pageUrl));

	$categoriesQuery = $dbh->prepare("SELECT wiki_categories.name AS name FROM wiki_categories JOIN wiki_categories_pages ON wiki_categories_pages.category_name = wiki_categories.name WHERE wiki_categories_pages.page_name = :name");
	$categoriesQuery->execute(array(':name' => $pageUrl));
	$categories = $categoriesQuery->fetchAll();
	$categoriesQuery->closeCursor();

	if (getVar('revert'))
	{
		$editQuery = $dbh->prepare('SELECT page_name, description, content, UNIX_TIMESTAMP(timestamp) AS timestamp FROM wiki_edits WHERE id = :id');
		$editQuery->execute(array(':id' => getVar('revert')));
		$edit = $editQuery->fetch();
		$editQuery->closeCursor();

		$revertQuery = $dbh->prepare('INSERT INTO wiki_edits (page_name, description, content, from_ip, timestamp) VALUES (:page_name, :description, :content, :from_ip, NOW())');
		$revertQuery->execute(array(':page_name' => $edit['page_name'], ':description' => 'Copie de #'.$edit['timestamp'].' ('.$edit['description'].')', ':content' => $edit['content'], ':from_ip' => $_SERVER['REMOTE_ADDR']));
	}

	if (getVar('edits') || getVar('revert'))
	{
		$editsQuery = $dbh->prepare("SELECT wiki_edits.id AS id, wiki_edits.description AS description, wiki_edits.from_ip AS from_ip, UNIX_TIMESTAMP(wiki_edits.timestamp) AS timestamp FROM wiki_edits WHERE wiki_edits.page_name = :name ORDER BY wiki_edits.timestamp DESC");
		$editsQuery->execute(array(':name' => $pageUrl));
		$edits = $editsQuery->fetchAll();
		$editsQuery->closeCursor();
		render('wiki-edits', array('page' => $page, 'categories' => $categories, 'edits' => $edits));
	}

	if (getVar('edit'))
	{
		if (getVar('content'))
		{
			if (!isset($page['content']))
			{
				$addPageQuery = $dbh->prepare("INSERT INTO wiki_pages (name) VALUES (:name)");
				$addPageQuery->execute(array(':name' => $pageUrl));
				$addPageQuery->closeCursor();
			}
			$content = preg_replace('/\(\((.*)\)\)/', '(wiki.php?page=$1)', getVar('content'));
			$addPageQuery = $dbh->prepare("INSERT INTO wiki_edits (page_name, from_ip, content, description, timestamp) VALUES (:page, :from_ip, :content, :description, NOW())");
			$addPageQuery->execute(array(':page' => $pageUrl, ':from_ip' => $_SERVER['REMOTE_ADDR'], ':content' => $content, ':description' => getVar('description')));
			$addPageQuery->closeCursor();
			header('Location: wiki.php?page='.$pageUrl);
		}
		render('wiki-editor', array('page' => $page, 'categories' => $categories));
	}

	if (isset($page['content']))
		render('wiki', array('mk' => new Parsedown(), 'page' => $page, 'categories' => $categories));
	else
		render('wiki-editor', array('page' => $page, 'categories' => $categories));
?>
