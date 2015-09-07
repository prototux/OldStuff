<div class="main">
	<div class="container">
		<section class="hgroup">
			<h1><?php echo $page['name']; ?> <span class="text-warning">(modification)</span></h1>
			<ul class="breadcrumb pull-right">
				<li><a href="index.php"><span class="fa fa-home"></span></a> </li>
				<?php if ($page['url'] == 'home'){ ?>
					<li class="active">Wiki</li>
				<?php } else { ?>
					<li><a href="wiki.php">Wiki</a></li>
					<li class="active"><?php echo $page['name']; ?></li>
				<?php } ?>
			</ul>
		</section>
		<section class="article-text">
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<form action="wiki.php?edit=<?php echo (getVar('edit')? getVar('edit') : getVar('page')); ?>" method="post">
						<textarea name="content" data-provide="markdown" rows="10"><?php echo $page['content']; ?></textarea>
						<input type"text" class="form-control" name="description" placeholder="Description de la modification" required>
						<button class="btn btn-success">Enregistrer</button>
					</form>
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