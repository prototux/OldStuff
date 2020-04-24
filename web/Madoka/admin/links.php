<?php
    require('init.php');

    if (!isLogged() || !haveRole('admin'))
        header('Location: login.php');

    if (getVar('add'))
    {
        $addUserQuery = $dbh->prepare("INSERT INTO links (name, url) VALUES (:name, :url)");
        $addUserQuery->execute(array(':name' => getVar('name'), ':url' => getVar('url')));
        $addUserQuery->closeCursor();
        header('Location: links.php');
    }

    if (getVar('edit'))
    {
        $editUserQuery = $dbh->prepare("UPDATE links SET name = :name, url = :url WHERE id = :id");
        $editUserQuery->execute(array(':name' => getVar('name'), ':url' => getVar('url'), ':id' => getVar('edit')));
        $editUserQuery->closeCursor();
        header('Location: links.php');
    }

    if (getVar('delete'))
    {
        $deleteUserQuery = $dbh->prepare("DELETE FROM links WHERE id = :id");
        $deleteUserQuery->execute(array(':id' => getVar('delete')));
        $deleteUserQuery->closeCursor();
        header('Location: links.php');
    }

    $linksQuery = $dbh->prepare("SELECT id, name, url FROM links ORDER BY id DESC");
    $linksQuery->execute();
    $links = $linksQuery->fetchAll();
    $linksQuery->closeCursor();

    render('links', array('links' => $links));
?>
