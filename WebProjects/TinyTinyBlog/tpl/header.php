<header>
    <div class="widewrapper masthead">
        <div class="container">
            <a href="index.php" id="logo">
                <img src="img/tales-logo.png" alt="<?php echo BLOG_NAME; ?>">
            </a>

            <div id="mobile-nav-toggle" class="pull-right">
                <a href="#" data-toggle="collapse" data-target=".nav-collapse">
                    <i class="icon-reorder"></i>
                </a>
            </div>

            <nav class="nav-collapse collapse pull-right tales-nav">
                <ul class="nav nav-pills">
                    <li>
                        <a href="index.php">Blog</a>
                    </li>
                    <li>
                        <a href="pictures.php">Pictures</a>
                    </li>
                    <li>
                        <a href="private.php<?php if ((isGuest() || $private_access) && !$_GET['deco']) echo '?deco=true'; ?>"><?php echo ((isGuest() || $private_access) && !$_GET['deco'])? 'Logout': 'Login'; ?></a>
                    </li>
                    <li>
                        <a href="about.php">Who are we?</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="widewrapper subheader">
        <div class="container">
            <div class="tales-breadcrumb">
                <a href="#"><?php echo BLOG_DESCRIPTION; ?></a>
            </div>

            <div class="tales-searchbox">
                <form action="search.php" method="get" accept-charset="utf-8">
                    <button class="searchbutton" type="submit">
                        <i class="icon-search"></i>
                    </button>
                    <input class="searchfield" id="searchbox" name="search" type="text" placeholder="Search">
                </form>
            </div>
        </div>
    </div>
</header>