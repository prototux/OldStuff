<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madoka | Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="gray-bg">
    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>
            	<img src="../img/restart_logo.png" alt="logo" style="width: 280px; padding-left: 10px;"/>
            </div>
            <h3>Veuillez vous connecter.</h3>
            <?php if ($failedLogin) echo '<h3 class="label label-danger">Login ou mot de passe invalide.</h3>'; ?>
            <form class="m-t" role="form" action="login.php" method="post">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="">
                </div>
                <button type="submit" class="btn btn-success block full-width m-b">Login</button>
            </form>
            <p class="m-t"><small>Madoka CMS // FabLab Robert-Houdin &copy; <?php echo date('Y'); ?></small></p>
        </div>
    </div>
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
