<div id="page-wrapper" class="gray-bg">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Liste des commentaires</h2>
		</div>
		<div class="col-lg-2">

		</div>
	</div>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-content">
				<table class="table">
					<thead>
					<tr>
						<th>Auteur</th>
						<th>Email</th>
						<th>Contenu</th>
						<th>Actions</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($comments as $comment) { ?>
						<tr>
							<td><?php echo $comment['nickname']; ?></td>
							<td><?php echo $comment['email']; ?></td>
							<td><?php echo substr($comment['content'], 0, 100); ?></td>
							<td><a href="articles.php?comments=<?php echo getVar('comments'); ?>&delete=<?php echo $comment['id']; ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
