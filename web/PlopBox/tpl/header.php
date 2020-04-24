<div id="in-nav">
	<div class="container">
		<div class="row">
			<div class="span12">
				<ul class="pull-right">
					<li><?php echo ($_SESSION['id'])?'Bienvenue '.$_SESSION['login'].' <a href="logout.php">(Se deconnecter)</a>':'<a href="login.php">Connection</a>'; ?></li>
				</ul>
				<a id="logo" href="index.php">
					<h4 style="font-size: 19px;">Plop<strong>Box</strong> // now with 42% larger IP</h4>
				</a>
			</div>
		</div>
	</div>
</div>
<?php if ($page != 'login') { ?>
	<div id="in-sub-nav">
		<div class="container">
			<div class="row">
				<div class="span12">
					<ul>
						<li><a href="index.php" <?php if ($page == 'dashboard') echo 'class="active"'; ?>><i class="batch home"></i><br>Dashboard</a></li>
						<?php if ($_SESSION && $_SESSION['level'] >= 2) echo '<li><a href="server.php" '.(($page == 'server')?'class="active"':'').'><i class="batch stream"></i><br>Servers</a></li>'; ?>
						<?php if ($_SESSION) echo '<li><a href="forum.php"  '.(($page == 'forum' || $page == 'topic')?'class="active"':'').'><i class="batch plane"></i><br>Forum</a></li>'; ?>
						<li><a href="maps.php"  <?php if ($page == 'maps') echo 'class="active"'; ?>><i class="batch maps"></i><br>Maps</a></li>
						<li><a href="videos.php"  <?php if ($page == 'videos') echo 'class="active"'; ?>><i class="batch videos"></i><br>Videos</a></li>
						<li><a target="_blank" href="http://steamcommunity.com/groups/plopbox"><i class="batch steam"></i><br>Steam</a></li>
						<?php if ($_SESSION && $_SESSION['level'] == 3) echo '<li><a href="users.php" '.(($page == 'users')?'class="active"':'').'><i class="batch users"></i><br>Users</a></li>'; ?>
						<?php if ($_SESSION) echo '<li><a href="profil.php" '.(($page == 'profil')?'class="active"':'').'><i class="batch settings"></i><br>Profile</a></li>'; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="page">
		<div class="page-container">
<?php } ?>