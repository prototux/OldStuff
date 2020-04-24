<?php
	require('init.php');

	if (!isLogged() || !haveRole('writer'))
		header('Location: login.php');

	if (getVar('add'))
	{
		// Add the article
		if (getVar('content'))
		{
			$addArticleQuery = $dbh->prepare("INSERT INTO blog_articles (title, description, content, category_id, timestamp) VALUES (:title, :description, :content, :category_id, NOW())");
			$addArticleQuery->execute(array(':title' => getVar('title'), ':description' => getVar('description'), ':content' => getVar('content', false), ':category_id' => getVar('category')));
			$addArticleQuery->closeCursor();
			$article_id = $dbh->lastInsertId();

			$addTagQuery = $dbh->prepare("INSERT INTO blog_articles_tags (article_id, tag_id) VALUES (:article_id, :tag_id)");
			foreach($_POST['tags'] as $tag)
				$addTagQuery->execute(array(':article_id' => $article_id, ':tag_id' => $tag));
			$addTagQuery->closeCursor();

			header('Location: articles.php');
		}

		// Else, render the editor
		$categoriesQuery = $dbh->prepare("SELECT id, name FROM blog_categories ORDER BY id");
		$categoriesQuery->execute();
		$categories = $categoriesQuery->fetchAll();
		$categoriesQuery->closeCursor();

		$tagsQuery = $dbh->prepare("SELECT id, name FROM blog_tags ORDER BY id");
		$tagsQuery->execute();
		$tags = $tagsQuery->fetchAll();
		$tagsQuery->closeCursor();
		render('editor', array('action' => 'add', 'categories' => $categories, 'tags' => $tags));
	}

	if (getVar('edit'))
	{
		if (getVar('content'))
		{
			$editArticleQuery = $dbh->prepare("UPDATE blog_articles SET title = :title, description = :description, content = :content, category_id = :category_id, timestamp = NOW() WHERE id = :id");
			$editArticleQuery->execute(array(':title' => getVar('title'), ':description' => getVar('description'), ':content' => getVar('content', false), ':category_id' => getVar('category'), ':id' => getVar('edit')));
			$editArticleQuery->closeCursor();

			// Edit the article's tags
		    $deleteArticleQuery = $dbh->prepare("DELETE FROM blog_articles_tags WHERE article_id = :id");
    	    $deleteArticleQuery->execute(array(':id' => getVar('edit')));
    	    $deleteArticleQuery->closeCursor();

    	    // And re-add them
			$addTagQuery = $dbh->prepare("INSERT INTO blog_articles_tags (article_id, tag_id) VALUES (:article_id, :tag_id)");
			foreach($_POST['tags'] as $tag)
				$addTagQuery->execute(array(':article_id' => getVar('edit'), ':tag_id' => $tag));
			$addTagQuery->closeCursor();

			header('Location: articles.php');
		}
		// Else, render the editor
		$categoriesQuery = $dbh->prepare("SELECT id, name FROM blog_categories ORDER BY id");
		$categoriesQuery->execute();
		$categories = $categoriesQuery->fetchAll();
		$categoriesQuery->closeCursor();

		$tagsQuery = $dbh->prepare("SELECT id, name FROM blog_tags ORDER BY id");
		$tagsQuery->execute();
		$tags = $tagsQuery->fetchAll();
		$tagsQuery->closeCursor();

		$articleQuery = $dbh->prepare("SELECT title, description, category_id, content FROM blog_articles WHERE id = :id");
		$articleQuery->execute(array(':id' => getVar('edit')));
		$article = $articleQuery->fetch();
		$articleQuery->closeCursor();

		$articleTagsQuery = $dbh->prepare("SELECT tag_id FROM blog_articles_tags WHERE article_id = :article_id");
		$articleTagsQuery->execute(array(':article_id' => getVar('edit')));
		$articleTagsRaw = $articleTagsQuery->fetchAll();
		$articleTagsQuery->closeCursor();

		$articleTags = array();
		foreach($articleTagsRaw AS $articleTag)
			$articleTags[$i++] = $articleTag['tag_id'];

		render('editor', array('action' => 'edit', 'categories' => $categories, 'tags' => $tags, 'article' => $article, 'articleTags' => $articleTags));
	}

	if (getVar('comments'))
	{
		if (!haveRole('moderator'))
			header('Location: articles.php');

		// Delete the comment
		if (getVar('delete'))
		{
	        $deleteArticleQuery = $dbh->prepare("DELETE FROM blog_comments WHERE id = :id");
	        $deleteArticleQuery->execute(array(':id' => getVar('delete')));
	        $deleteArticleQuery->closeCursor();
	        header('Location: articles.php?comments='.getVar('comments'));
		}

		// Else, render the list
		$commentsQuery = $dbh->prepare("SELECT id, nickname, email, content FROM blog_comments WHERE article_id = :article_id ORDER BY id DESC");
		$commentsQuery->execute(array(':article_id' => getVar('comments')));
		$comments = $commentsQuery->fetchAll();
		$commentsQuery->closeCursor();
		render('comments', array('comments' => $comments));
	}


    if (getVar('delete'))
    {
        $deleteArticleQuery = $dbh->prepare("DELETE FROM blog_articles WHERE id = :id");
        $deleteArticleQuery->execute(array(':id' => getVar('delete')));
        $deleteArticleQuery->closeCursor();
        header('Location: articles.php');
    }

	$articlesQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.description, blog_categories.name AS category, COUNT(blog_comments.id) AS nb_comments, UNIX_TIMESTAMP(blog_articles.timestamp) AS timestamp FROM blog_articles INNER JOIN blog_categories ON blog_articles.category_id = blog_categories.id LEFT JOIN blog_comments ON blog_comments.article_id = blog_articles.id GROUP BY blog_articles.id ORDER BY blog_articles.id DESC LIMIT :start,:nb_articles");
	$articlesQuery->execute(array(':start' => getVar('page')*20, ':nb_articles' => 20));
	$articles = $articlesQuery->fetchAll();

	$allArticlesQuery = $dbh->prepare("SELECT blog_articles.id, blog_articles.title, blog_articles.description, blog_categories.name AS category, blog_articles.timestamp FROM blog_articles JOIN blog_categories ON blog_articles.category_id = blog_categories.id");
	$allArticlesQuery->execute();

	$maxPages = round($allArticlesQuery->rowCount()/20)-1;
	if (!($allArticlesQuery->rowCount()%20))
		$maxPages--;

	$page = (getVar('page'))? getVar('page') : 0;

	render('articles', array('articles' => $articles, 'npage' => $page, 'maxPages' => $maxPages));
?>
