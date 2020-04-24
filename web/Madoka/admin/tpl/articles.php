<div id="page-wrapper" class="gray-bg">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Gestion des articles</h2>
		</div>
		<div class="col-lg-2">

		</div>
	</div>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<div class="btn-group">
					<a href="articles.php?add=true" class="btn btn-sm btn-primary" title="Ecrire un article"><span class="fa fa-plus"></span></a>
				</div>
				<div class="ibox-tools">
					<strong>Page <?php echo $npage; ?>/<?php echo $maxPages; ?></strong>
					<?php if ($npage) echo '<a href="articles.php?page='.($npage-1).'"><i class="fa fa-chevron-left"></i></a>'; ?>
					<?php if ($npage != $maxPages) echo '<a href="articles.php?page='.($npage+1).'"><i class="fa fa-chevron-right"></i></a>'; ?>
				</div>
			</div>
			<div class="ibox-content">
				<table class="table">
					<thead>
					<tr>
						<th>Titre</th>
						<th class="col-sm-1">Date</th>
						<th class="col-sm-1">Categorie</th>
						<th class="col-sm-1"><span class="fa fa-comments"></span></th>
						<th class="col-sm-1">Actions</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($articles as $article) { ?>
						<tr>
							<td><?php echo $article['title']; ?></td>
							<td><?php echo date('d/m/Y', $article['timestamp']); ?></td>
							<td><?php echo $article['category']; ?></td>
							<td><?php echo (haveRole('moderator'))?'<a href="articles.php?comments='.$article['id'].'">'.$article['nb_comments'].'</a>':$article['nb_comments']; ?></td>
							<td>
								<a href="articles.php?edit=<?php echo $article['id']; ?>" title="Editer l'article" data-toggle="modal" class="btn btn-warning"><span class="fa fa-edit"></span></a>
								<a href="articles.php?delete=<?php echo $article['id']; ?>" title="Supprimer l'article" class="btn btn-danger"><span class="fa fa-trash-o"></span></a>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
