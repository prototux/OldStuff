<?php
	require('init.php');

	if (!isAdmin())
		render('403');

	if (getVar('privacy') || getVar('content'))
	{
		//Insert and get the ID
		$addArticle = $dbh->prepare("INSERT INTO articles (title, category_id, private, description, content, date) VALUES (:title, :category_id, :private, :description, :content, NOW())");
		$addArticle->execute(array(':title' => getVar('title'), ':category_id' => getVar('category_id'), ':private' => getVar('privacy'), 'description' => getVar('description'), ':content' => getVar('content', false)));
        $articleId = $dbh->lastInsertId();

        //Write the images
  		$titleData = substr(getVar('image_title'), strpos(getVar('image_title'), ',')+1);
  		$titleData = str_replace(' ', '+', $titleData);
		$titleData = base64_decode($titleData);
		file_put_contents('images/articles/'.$articleId.'-mini.jpg', $titleData);

  		$headerData = substr(getVar('image_header'), strpos(getVar('image_header'), ',')+1);
  		$headerData = str_replace(' ', '+', $headerData);
		$headerData = base64_decode($headerData);
		file_put_contents('images/articles/'.$articleId.'.jpg', $headerData);

		$articlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id ORDER BY articles.id DESC LIMIT 4 ");
		$articlesQuery->execute();
		$articles = $articlesQuery->fetchAll();

		$allArticlesQuery = $dbh->prepare("SELECT articles.id, articles.title, articles.description, categories.name AS category, articles.date FROM articles JOIN categories ON articles.category_id = categories.id");
		$allArticlesQuery->execute();

		$maxPages = round($allArticlesQuery->rowCount()/4);

		$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
		$categoriesQuery->execute();
		$categories = $categoriesQuery->fetchAll();

		$page = 1;

		render('index', array('articles' => $articles, 'categories' => $categories, 'url' => 'index.php',  'npage' => $page, 'maxPages' => $maxPages));
	}
	else
	{
		$categoriesQuery = $dbh->prepare("SELECT id, name FROM categories ");
		$categoriesQuery->execute();
		$categories = $categoriesQuery->fetchAll();

		render('addarticle', array('categories' => $categories));
	}
?>
