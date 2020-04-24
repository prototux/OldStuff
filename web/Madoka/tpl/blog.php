<div class="main">
	<div class="container">
		<section class="hgroup">
			<h1>Blog</h1>
			<ul class="breadcrumb pull-right">
				<li><a href="index.php"><span class="fa fa-home"></span></a> </li>
				<li class="active">Blog</li>
			</ul>
		</section>
		<section>
			<div class="row">
				<div id="leftcol" class="col-sm-9 col-md-9">
					<?php foreach ($articles as $article) { ?>
						<article class="post">
							<div class="post_header">
								<h3 class="post_title"><a href="blog_article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></h3>
								<div class="post_sub">
									<span class="fa fa-clock-o"></span> <?php echo date('d/m/Y', $article['timestamp']); ?>
									<span class="fa post_icon fa-bookmark-o"></span> <?php echo $article['category']; ?> <!-- Peut aussi etre fa-flag-o -->
									<!--span class="fa fa-padding fa-bookmark-o"></span> <span class="label label-primary">test</span> <span class="label label-primary">dev</span> <span class="label label-primary">site</span-->
									<span class="fa post_icon fa-comments-o"></span> <?php echo (int)$article['nb_comments']; ?> commentaire<?php echo ((int)$article['nb_comments'] > 1)?'s':''; ?>
								</div>
							</div>
							<div class="post_content post_nofigure">
								<p><?php echo $article['description']; ?></p>
								<a href="blog_article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Lire l'article</a> </div>
						</article>
					<?php } ?>
					<div class="pagination_wrapper">
						<ul class="pagination pagination-centered">
							<?php echo '<li'.(($npage == 0)?' class="disabled"':'').'><a href="blog.php?page='.($npage-1).(($_GET['category'])?('&category='.$_GET['category']):'').'"><i class="fa fa-chevron-left"></i></a></li>'; ?>
							<?php echo '<li'.(($npage == $maxPages || ($npage == 0 && $maxPages == 1))?' class="disabled"':'').'><a href="blog.php?page='.($npage+1).(($_GET['category'])?('&category='.$_GET['category']):'').'" class="older"><i class="fa fa-chevron-right"></i></a></li>'; ?>
						</ul>
					</div>
				</div>
				<div id="sidebar" class="col-sm-3 col-md-3">
					<aside class="widget">
						<h4>Le blog du lab</h4>
						<p>N'hesitez pas a demander l'acces si vous voulez ecrire des articles.</p>
					</aside>

					<aside class="widget">
						<h4>Categories</h4>
						<ul class="nav nav-pills nav-stacked">
							<?php foreach ($categories as $category) echo '<li '.((getVar('category') == $category['id'])?'class="active"':'').'><a href="blog.php?category='.$category['id'].'">'.$category['name'].'</a></li>'; ?>
						</ul>
					</aside>

					<aside class="widget tags">
						<h4>Tags</h4>
						<?php foreach ($tags as $tag) echo '<a href="blog.php?tag='.$tag['id'].'" class="label '.((getVar('tag') == $tag['id'])?'label-primary':'label-default').'">'.$tag['name'].'</a>'; ?>
					</aside>
				</div>
			</div>
		</section>
	</div>