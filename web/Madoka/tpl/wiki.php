<div class="main">
	<div class="container">
		<section class="hgroup">
			<h1><?php echo str_replace('_', ' ', $page['name']); ?></h1>
			<ul class="wiki-actions">
				<li><a href="wiki.php?edit=<?php echo $page['name']; ?>" class="text-warning"><span class="fa fa-edit"></span></a></li>
				<li><a href="wiki.php?edits=<?php echo $page['name']; ?>" class="text-success"><span class="fa fa-list"></span></a></li>
			</ul>
			<ul class="breadcrumb pull-right">
				<li><a href="index.php"><span class="fa fa-home"></span></a> </li>
				<?php if ($page['name'] == 'Acceuil'){ ?>
					<li class="active">Wiki</li>
				<?php } else { ?>
					<li><a href="wiki.php">Wiki</a></li>
					<li class="active"><?php echo str_replace('_', ' ', $page['name']); ?></li>
				<?php } ?>
			</ul>
		</section>
		<section class="article-text">
			<div class="row">
			    <div class="col-sm-12 col-md-12">
			    	<?php echo $mk->text($page['content']); ?>
			    </div>
			</div>
		</section>
		<section class="hgroup">
			<ul class="wiki-categories">
				<?php
					foreach ($categories AS $category)
						echo '<li><a href="wiki.php?category='.$category['url'].'">'.$category['name'].'</a></li>';
				?>
			</ul>
		</section>
	</div>