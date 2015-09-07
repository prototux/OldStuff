<div class="full_page_photo"><div id="map"></div></div>
<div class="main">
	<div class="container">
		<section class="hgroup">
			<h1>Nous contacter</h1>
			<ul class="breadcrumb pull-right">
				<li><a href="index.php"><span class="fa fa-home"></span></a> </li>
				<li class="active">Contact</li>
			</ul>
		</section>
		<section>
			<div class="row">
				<div class="office_address col-sm-4 col-md-4">
					<div class="team_member">
						<img src="img/restart_logo.png" height="100" alt="logo">
						<h5>Contacter le lab</h5>
						<small>Reseaux sociaux & IRL</small><br><br>
						<div class="team_social">
							<a href="https://www.facebook.com"><i class="fa fa-facebook"></i></a>
							<a href="https://twitter.com/leonartgr"><i class="fa fa-twitter"></i></a>
							<a href="contact.php#pinterest"><i class="fa fa-google-plus"></i></a>
							<a href="https://github.com/PlethoraThemes"><i class="fa fa-github-alt"></i></a>
						</div>
						<address>
							39D Allee des Pins<br>
							41000 Blois, Centre<br>
							<span class="fa fa-phone"></span> 02 54 42 42 42
						</address>
						<address>
							<span class="fa fa-envelope"></span> <a href="mailto:#">contact@fablab-robert-houdin.org</a>
						</address>
					</div>
				</div>
				<div class="contact_form col-sm-8 col-md-8">
					<?php if ($messageSent) echo '<div class="alert alert-success">Message envoye!</div>'; ?>
					<form name="contact_form" id="contact_form" method="post" action="contact.php">
						<div class="row">
							<div class="col-sm-6 col-md-6">
								<label>Nom</label>
								<input name="name" id="name" class="form-control" type="text">
							</div>
							<div class="col-sm-6 col-md-6">
								<label>Email</label>
								<input name="email" id="email" class="form-control" type="email">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<label>Sujet</label>
								<input name="subject" id="subject" class="form-control" type="text">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<label>Message</label>
								<textarea name="message" id="message" rows="8" class="form-control"></textarea>
							</div>
							<div class="col-sm-12 col-md-12">
								<br><button id="submit_btn" class="btn btn-primary" name="submit">Envoyer</button> <span id="notice" class="alert alert-warning alert-dismissable hidden" style="margin-left:20px;"></span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</section>
	</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALpLVDoxXOhZE38XY85g47nm7ZaTJvcbU&sensor=false"></script>
<script type="text/javascript" src="js/contact_form.js"></script>
</body>
</html>