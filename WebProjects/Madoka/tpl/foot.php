	<footer>
		<section id="footer_teasers_wrapper">
			<div class="container">
				<div class="row">
					<div class="footer_teaser col-sm-3 col-md-3">
						<h3>A Propos</h3>
						<p>Ouvert tous les samedi, de 14h a 18h</p>
						<p><i class="fa fa-map-marker"></i> 39D Allée des Pins</p>
						<p><i class="fa fa-phone"></i> 02 54 42 42 42</p>
						<p><i class="fa fa-print"></i> 02 42 42 42 42</p>
						<p><i class="fa fa-envelope"></i> contact@fablab-robert-houdin.org</p>
					</div>
					<div class="footer_teaser col-sm-5 col-md-5">
						<h3>Derniers articles</h3>
						<ul class="media-list">
							<?php foreach ($lastArticles as $article) { ?>
								<li class="media">
									<h5 class="media-heading"><a href="blog_article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></h5>
									<p><?php echo $article['description']; ?></p>
								</li>
							<?php } ?>
						</ul>
					</div>
					<div class="footer_teaser col-sm-4 col-md-4">
						<h3>Liens</h3>
						<?php
							foreach ($linksLists as $links)
							{
								echo '<ul class="media-list col-sm-6 col-md-6">';
								foreach($links as $link)
									echo '<li class="media"><a href="'.$link['url'].'">'.$link['name'].'</a></li>';
								echo '</ul>';
							}
						?>
					</div>
				</div>
			</div>
		</section>
		<section class="copyright">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-md-6"> Copyright &copy;<?php echo date('Y'); ?> FabLab Robert-Houdin</div>
					<div class="text-right col-sm-6 col-md-6">With <a href="http://www.github.com/fablab-robert-houdin/magicalcms">Madoka</a>'s magic</div>
				</div>
			</div>
		</section>
	</footer>
</div>
</body>
</html>