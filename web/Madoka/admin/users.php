<?php
    require('init.php');

    if (!isLogged() || !haveRole('admin'))
        header('Location: login.php');

    if (getVar('add'))
    {
        $addUserQuery = $dbh->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $addUserQuery->execute(array(':name' => getVar('name'), ':email' => getVar('email')));
        $addUserQuery->closeCursor();

        $user_id = $dbh->lastInsertId();
        $roles['is_admin'] = false;
        $roles['is_moderator'] = false;
        $roles['is_writer'] = false;
        foreach($_POST['roles'] as $right)
        {
            if ($right == 'admin')
                $roles['is_admin'] = true;
            if ($right == 'moderator')
                $roles['is_moderator'] = true;
            if ($right == 'writer')
                $roles['is_writer'] = true;
        }

        $addrolesQuery = $dbh->prepare("INSERT INTO users_roles (user_id, is_admin, is_moderator, is_writer) VALUES (:user_id, :is_admin, :is_moderator, :is_writer)");
        $addrolesQuery->execute(array(':user_id' => $user_id, ':is_admin' => $roles['is_admin'], ':is_moderator' => $roles['is_moderator'], ':is_writer' => $roles['is_writer']));
        $addrolesQuery->closeCursor();
        header('Location: users.php');
    }

    if (getVar('edit'))
    {
        $editUserQuery = $dbh->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $editUserQuery->execute(array(':name' => getVar('name'), ':email' => getVar('email'), ':id' => getVar('edit')));
        $editUserQuery->closeCursor();

        $roles['is_admin'] = false;
        $roles['is_moderator'] = false;
        $roles['is_writer'] = false;
        foreach($_POST['roles'] as $right)
        {
            if ($right == 'admin')
                $roles['is_admin'] = true;
            if ($right == 'moderator')
                $roles['is_moderator'] = true;
            if ($right == 'writer')
                $roles['is_writer'] = true;
        }

        $addrolesQuery = $dbh->prepare("UPDATE users_roles SET is_admin = :is_admin, is_moderator = :is_moderator, is_writer = :is_writer WHERE user_id = :id");
        $addrolesQuery->execute(array(':is_admin' => $roles['is_admin'], ':is_moderator' => $roles['is_moderator'], ':is_writer' => $roles['is_writer'], ':id' => getVar('edit')));
        $addrolesQuery->closeCursor();
        header('Location: users.php');
    }

    if (getVar('delete'))
    {
        $deleteUserQuery = $dbh->prepare("DELETE FROM users WHERE id = :id");
        $deleteUserQuery->execute(array(':id' => getVar('delete')));
        $deleteUserQuery->closeCursor();
        header('Location: users.php');
    }

    $usersQuery = $dbh->prepare("SELECT users.id AS id, users.name AS name, users.email AS email, users_roles.is_admin AS is_admin, users_roles.is_moderator AS is_moderator, users_roles.is_writer AS is_writer FROM users LEFT JOIN users_roles ON users.id = users_roles.user_id");
    $usersQuery->execute();
    $users = $usersQuery->fetchAll();
    $usersQuery->closeCursor();

    render('users', array('users' => $users));
?>
