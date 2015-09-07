<div id="wrapper">
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="profile-element">
                    <span><a href="index.php"><img alt="image" src="../img/restart_logo.png"/></a></span>
                </div>
            </li>
            <li <?php if($page == 'index') echo 'class="active"'; ?>>
                <a href="index.php"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>
            <?php if (haveRole('writer')) { ?>
            <li>
                <p><i class="fa fa-edit"></i> <span class="nav-label">Blog</span></p>
                <ul class="nav nav-second-level collapse in">
                    <li <?php if($page == 'articles' || $page == 'editor' || $page == 'comments') echo 'class="active"'; ?>><a href="articles.php">Articles</a></li>
                    <li <?php if($page == 'blog') echo 'class="active"'; ?>><a href="blog.php">Categories et Tags</a></li>
                </ul>
            </li>
            <?php } ?>
            <?php if (haveRole('admin')) { ?>
            <li>
                <p><i class="fa fa-files-o"></i> <span class="nav-label">CMS</span></p>
                <ul class="nav nav-second-level collapse in">
                    <li <?php if($page == 'carousel') echo 'class="active"'; ?>><a href="carousel.php">Accueil</a></li>
                    <li <?php if($page == 'hardware') echo 'class="active"'; ?>><a href="hardware.php">Materiel</a></li>
                    <li <?php if($page == 'links') echo 'class="active"'; ?>><a href="links.php">Liens</a></li>
                </ul>
            </li>
            <li <?php if($page == 'users') echo 'class="active"'; ?>>
                <a href="users.php"><span class="fa fa-users"></span> <span class="nav-label">Utilisateurs</span></a>
                <!--p><i class="fa fa-user"></i> <span class="nav-label">Utilisateurs</span></p>
                <ul class="nav nav-second-level collapse in">
                    <li><a href="adduser.php">Ajouter</a></li>
                    <li><a href="users.php">Liste</a></li>
                </ul-->
            </li>
            <?php } ?>
            <li class="special_link">
                <a href="logout.php"><i class="fa fa-log-out"></i> <span class="nav-label">Se deconnecter</span></a>
            </li>
        </ul>

    </div>
</nav>
