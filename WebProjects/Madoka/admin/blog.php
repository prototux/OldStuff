<?php
    require('init.php');

    if (!isLogged() || !haveRole('admin'))
        header('Location: login.php');

    if (getVar('addCategory'))
    {
        $addUserQuery = $dbh->prepare("INSERT INTO blog_categories (name) VALUES (:name)");
        $addUserQuery->execute(array(':name' => getVar('name')));
        $addUserQuery->closeCursor();
        header('Location: blog.php');
    }

    if (getVar('editCategory'))
    {
        $editUserQuery = $dbh->prepare("UPDATE blog_categories SET name = :name WHERE id = :id");
        $editUserQuery->execute(array(':name' => getVar('name'), ':id' => getVar('editCategory')));
        $editUserQuery->closeCursor();
        header('Location: blog.php');
    }

    if (getVar('deleteCategory'))
    {
        $deleteUserQuery = $dbh->prepare("DELETE FROM blog_categories WHERE id = :id");
        $deleteUserQuery->execute(array(':id' => getVar('deleteCategory')));
        $deleteUserQuery->closeCursor();
        header('Location: blog.php');
    }

    if (getVar('addTag'))
    {
        $addUserQuery = $dbh->prepare("INSERT INTO blog_tags (name) VALUES (:name)");
        $addUserQuery->execute(array(':name' => getVar('name')));
        $addUserQuery->closeCursor();
        header('Location: blog.php');
    }

    if (getVar('editTag'))
    {
        $editUserQuery = $dbh->prepare("UPDATE blog_tags SET name = :name WHERE id = :id");
        $editUserQuery->execute(array(':name' => getVar('name'), ':id' => getVar('editTag')));
        $editUserQuery->closeCursor();
        header('Location: blog.php');
    }

    if (getVar('deleteTag'))
    {
        $deleteUserQuery = $dbh->prepare("DELETE FROM blog_tags WHERE id = :id");
        $deleteUserQuery->execute(array(':id' => getVar('deleteTag')));
        $deleteUserQuery->closeCursor();
        header('Location: blog.php');
    }

    $categoriesQuery = $dbh->prepare("SELECT id, name FROM blog_categories");
    $categoriesQuery->execute();
    $categories = $categoriesQuery->fetchAll();
    $categoriesQuery->closeCursor();

    $tagsQuery = $dbh->prepare("SELECT id, name FROM blog_tags");
    $tagsQuery->execute();
    $tags = $tagsQuery->fetchAll();
    $tagsQuery->closeCursor();

    render('blog', array('categories' => $categories, 'tags' => $tags));
?>
