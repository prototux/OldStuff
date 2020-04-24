<div class="main">
	<div class="container">
		<section class="hgroup">
			<h1><?php echo $article['title']; ?></h1>
			<h2>
				<span class="fa fa-clock-o"></span> <?php echo date('d/m/Y', $article['timestamp']); ?>
				<span class="fa post_icon fa-bookmark-o"></span> <?php echo $article['category']; ?>
				<span class="fa post_icon fa-flag-o"></span> <?php foreach ($articleTags as $tag) echo '<span class="label label-primary">'.$tag['name'].'</span>'; ?>
				<span class="fa post_icon fa-comments-o"></span> <?php echo (int)$article['nb_comments']; ?> commentaire<?php echo ($article['nb_comments'] > 1)?'s':''; ?>
			</h2>
			<ul class="breadcrumb pull-right">
				<li><a href="index.php"><span class="fa fa-home"></span></a></li>
				<li><a href="blog.php">Blog</a></li>
				<li class="active">Article #<?php echo $article['id']; ?></li>
			</ul>
		</section>
		<section>
			<div class="row">
				<div id="leftcol" class="col-sm-9 col-md-9">
					<article class="post">
						<div class="post_content">
							<?php echo $article['content']; ?>
						</div>
					</article>
					<div id="post_comments">
						<h4>Commentairess</h4>
						<div class="comment">
							<?php foreach ($comments as $comment) { ?>
								<div class="row">
									<figure class="col-sm-2 col-md-2"> <img class="img-circle" src="<?php echo 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($comment['email']))).'?d=mm&r=pg'; ?>" alt=""> </figure>
									<div class="col-sm-10 col-md-10">
										<div class="comment_name"> <?php echo $comment['nickname']; ?></div>
										<div class="comment_date"><span class="fa fa-clock-o"></span> <?php echo date('d/m/Y G:i', $comment['timestamp']); ?></div>
										<div class="the_comment">
											<p><?php echo $comment['content']; ?></p>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="new_comment">
						<h4>Commenter l'article</h4>
						<form method="post" action="blog_article.php?id=<?php echo getVar('id'); ?>">
							<div class="row">
								<div class="col-sm-6 col-md-6">
									<input class="form-control" name="name" placeholder="Nom" type="text">
								</div>
								<div class="col-sm-6 col-md-6">
									<input class="form-control" name="email" placeholder="Email" type="email">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-12"><br/>
									<textarea name="comment" rows="7" placeholder="Votre commentaire" class="form-control"></textarea>
								</div>
							</div>
							<div class="row"><br/>
								<div class="col-sm-12 col-md-12"> <button class="btn send btn-primary">Commenter</button> </div>
							</div>
						</form>
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