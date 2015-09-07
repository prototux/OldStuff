<?php
    require('init.php');

    if (!isLogged() || !haveRole('admin'))
        header('Location: login.php');

    // File upload failed
    $error = false;
    if (getVar('badfile') || getVar('upfail'))
    {
        $id = getVar('badfile') + getVar('upfail');
        $deleteUserQuery = $dbh->prepare("DELETE FROM name WHERE id = :id");
        $deleteUserQuery->execute(array(':id' => $id));
        $deleteUserQuery->closeCursor();
        if (getVar('badfile'))
            $error = 'Mauvais fichier!';
        else
            $error = 'Erreur d\'upload';
    }

    if (getVar('add'))
    {
        $addUserQuery = $dbh->prepare("INSERT INTO tools (name, smalldesc, description) VALUES (:name, :smalldesc, :description)");
        $addUserQuery->execute(array(':name' => getVar('name'), ':smalldesc' => getVar('smalldesc'), ':description' => getVar('description')));
        $addUserQuery->closeCursor();
        $elementId = $dbh->lastInsertId();
        $newname = $_SERVER["DOCUMENT_ROOT"].'/fablab/img/tools/'.$elementId.'.jpg';

        //debug($_FILES);

        if((!empty($_FILES['picture'])) && ($_FILES['picture']['error'] == 0))
        {
            $filename = basename($_FILES['picture']['name']);
            $ext = substr($filename, strrpos($filename, '.') + 1);
            if (getimagesize($_FILES['picture']['tmp_name']) && ($_FILES["picture"]["size"] < 4000000))
            {
                if (!file_exists($newname) && move_uploaded_file($_FILES['picture']['tmp_name'], $newname))
                    header('Location: hardware.php');
                else
                    header('Location: hardware.php?upfail='.$elementId);
            }
            else
                header('Location: hardware.php?badfile='.$elementId);
        }
    }

    if (getVar('edit'))
    {
        $editUserQuery = $dbh->prepare("UPDATE tools SET name = :name, smalldesc = :smalldesc, description = :description WHERE id = :id");
        $editUserQuery->execute(array(':name' => getVar('name'), ':smalldesc' => getVar('smalldesc'), ':description' => getVar('description'), ':id' => getVar('edit')));
        $editUserQuery->closeCursor();
        header('Location: hardware.php');
    }

    if (getVar('delete'))
    {
        $deleteUserQuery = $dbh->prepare("DELETE FROM tools WHERE id = :id");
        $deleteUserQuery->execute(array(':id' => getVar('delete')));
        $deleteUserQuery->closeCursor();
        // TODO: delete element's picture
        header('Location: hardware.php');
    }

    $elementsQuery = $dbh->prepare("SELECT id, name, smalldesc, description FROM tools ORDER BY id DESC");
    $elementsQuery->execute();
    $elements = $elementsQuery->fetchAll();
    $elementsQuery->closeCursor();

    render('hardware', array('elements' => $elements));
?>
