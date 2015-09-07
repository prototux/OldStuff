<?php
    require('init.php');

    if (!isLogged() || !haveRole('admin'))
        header('Location: login.php');

    // File upload failed
    $error = false;
    if (getVar('badfile') || getVar('upfail'))
    {
        $id = getVar('badfile') + getVar('upfail');
        $deleteUserQuery = $dbh->prepare("DELETE FROM carousel WHERE id = :id");
        $deleteUserQuery->execute(array(':id' => $id));
        $deleteUserQuery->closeCursor();
        if (getVar('badfile'))
            $error = 'Mauvais fichier!';
        else
            $error = 'Erreur d\'upload';
    }

    if (getVar('add'))
    {
        $addUserQuery = $dbh->prepare("INSERT INTO carousel (title, description) VALUES (:title, :description)");
        $addUserQuery->execute(array(':title' => getVar('title'), ':description' => getVar('description')));
        $addUserQuery->closeCursor();
        $elementId = $dbh->lastInsertId();
        $newname = $_SERVER["DOCUMENT_ROOT"].'/fablab/img/carousel/'.$elementId.'.jpg';

        //debug($_FILES);

        if((!empty($_FILES['picture'])) && ($_FILES['picture']['error'] == 0))
        {
            $filename = basename($_FILES['picture']['name']);
            $ext = substr($filename, strrpos($filename, '.') + 1);
            if (getimagesize($_FILES['picture']['tmp_name']) && ($_FILES["picture"]["size"] < 4000000))
            {
                if (!file_exists($newname) && move_uploaded_file($_FILES['picture']['tmp_name'], $newname))
                    header('Location: carousel.php');
                else
                    header('Location: carousel.php?upfail='.$elementId);
            }
            else
                header('Location: carousel.php?badfile='.$elementId);
        }
    }

    if (getVar('edit'))
    {
        $editUserQuery = $dbh->prepare("UPDATE carousel SET title = :title, description = :description WHERE id = :id");
        $editUserQuery->execute(array(':title' => getVar('title'), ':description' => getVar('description'), ':id' => getVar('edit')));
        $editUserQuery->closeCursor();
        header('Location: carousel.php');
    }

    if (getVar('delete'))
    {
        $deleteUserQuery = $dbh->prepare("DELETE FROM carousel WHERE id = :id");
        $deleteUserQuery->execute(array(':id' => getVar('delete')));
        $deleteUserQuery->closeCursor();
        // TODO: delete element's picture
        header('Location: carousel.php');
    }

    $elementsQuery = $dbh->prepare("SELECT id, title, description FROM carousel ORDER BY id DESC");
    $elementsQuery->execute();
    $elements = $elementsQuery->fetchAll();
    $elementsQuery->closeCursor();

    render('carousel', array('elements' => $elements));
?>
