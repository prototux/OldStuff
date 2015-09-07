<section id="slider_wrapper" class="slider_wrapper full_page_photo">
	<div id="main_flexslider" class="flexslider">
		<ul class="slides">
			<?php foreach ($carousel as $element) { ?>
				<li class="item" style="background-image: url(img/carousel/<?php echo $element['id'];?>.jpg)">
					<div class="container">
						<div class="carousel-caption">
							<h1><?php echo $element['title']; ?></h1>
							<p class="lead skincolored"><?php echo $element['description']; ?></p>
					</div>
				</li>
			<?php } ?>
		</ul>
	</div>
</section>
<div class="main">
	<div class="container">
		<section class="call_to_action">
			<h3>Un FabLab pour tout prototyper</h3>
			<h4>mais aussi pour apprendre, partager, s'entraider!</h4>
		<section class="features_teasers_wrapper">
			<div class="row">
				<div class="feature_teaser col-sm-4 col-md-4"> <img alt="responsive" src="img/responsive.png">
					<h3>Des outils a disposition...</h3>
					<p>Le FabLab Robert-Houdin dispose de nombreux outils, Imprimantes 3D, Fraiseuses CNC, Labo d'electronique, Decoupeuse vinyl, Scanner 3D, tout est disponible pour fabriquer n'importe quoi.</p>
				</div>
				<div class="feature_teaser col-sm-4 col-md-4"> <img alt="responsive" src="img/git.png">
					<h3>...Ainsi que des connaissances...</h3>
					<p>Le Lab est aussi un lieu de partage, on croise au FabLab des personnes de tous horizons, qui peuvent partager leur savoir, que ce soit en electronique de precision ou en soudure de structures en metal.</p>
				</div>
				<div class="feature_teaser col-sm-4 col-md-4"> <img alt="responsive" src="img/less.png">
					<h3>...Tous les samedis, de 14 a 18h</h3>
					<p>Les locaux sont ouvert tous les samedis de 14h a 18h, et il s'y deroule tres souvent des ateliers, specifiques aux machines ou non, hesitez pas a proposer un atelier!</p>
				</div>
			</div>
		</section>
	</div>
