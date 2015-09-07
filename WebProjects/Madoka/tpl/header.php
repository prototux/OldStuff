<header>
	<div class="container">
		<div class="navbar navbar-default" role="navigation">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php"><img src="img/restart_logo.png" alt="optional logo" height="90"></a>
				<a class="btn btn-navbar btn-default navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="nb_left pull-left"> <span class="fa fa-reorder"></span></span>
					<span class="nb_right pull-right">menu</span>
				</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav pull-right navbar-nav">
					<li <?php if ($_page == 'index') echo 'class="active"'; ?>><a href="index.php"><span class="fa fa-home"></span></a></li>
					<li <?php if ($_page == 'about') echo 'class="active"'; ?>><a href="about.php">A Propos</a></li>
					<li <?php if ($_page == 'hardware') echo 'class="active"'; ?>><a href="hardware.php">Materiel</a></li>
					<li <?php if ($_page == 'blog' || $_page == 'blog-article') echo 'class="active"'; ?>><a href="blog.php">Blog</a></li>
					<li <?php if ($_page == 'wiki' || $_page == 'wiki-editor') echo 'class="active"'; ?>><a href="wiki.php">Wiki</a></li>
					<li <?php if ($_page == 'contact') echo 'class="active"'; ?>><a href="contact.php">Contact</a></li>
				</ul>
			</div>
		</div>
		<div id="social_media_wrapper">
			<a href="https://www.facebook.com/profile.php?id=100005904677169"><i class="fa fa-facebook"></i></a>
			<a href="https://twitter.com/fablab-robert-houdin"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-google-plus"></i></a>
		</div>
	</div>
</header>