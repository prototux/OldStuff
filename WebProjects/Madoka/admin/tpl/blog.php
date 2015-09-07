<div id="page-wrapper" class="gray-bg">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Gestion des categories et des tags</h2>
		</div>
		<div class="col-lg-2">

		</div>
	</div>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<div class="btn-group">
						<a href="#addCategory" data-toggle="modal" class="btn btn-sm btn-primary" title="Ajouter une categorie"><span class="fa fa-plus"></span></a>
						<div id="addCategory" class="modal fade" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-12"><h3 class="m-t-none m-b">Ajouter une categories</h3>
												<form role="form" action="blog.php?addCategory=true" method="post">
													<div class="form-group"><label>Nom</label> <input type="text" name="name" placeholder="Categorie" class="form-control"></div>
													<div>
														<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Ajouter</strong></button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox-tools">
						<strong>Categories<strong>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
						<tr>
							<th class="col-sm-10">Nom</th>
							<th class="col-sm-2">Actions</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($categories as $category) { ?>
							<tr>
								<td><?php echo $category['name']; ?></td>
								<td><a href="#editCategory<?php echo $category['id']; ?>" data-toggle="modal" class="btn btn-warning"><span class="fa fa-edit"></span></a> <a href="blog.php?deleteCategory=<?php echo $category['id']; ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a></td>
							</tr>
							<div id="editCategory<?php echo $category['id']; ?>" class="modal fade" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">
											<div class="row">
												<div class="col-sm-12"><h3 class="m-t-none m-b">Editer une categorie</h3>
													<form role="form" action="blog.php?editCategory=<?php echo $category['id']; ?>" method="post">
														<div class="form-group"><label>Nom</label> <input type="text" name="name" value="<?php echo $category['name']; ?>" class="form-control"></div>
														<div>
															<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Editer</strong></button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<div class="btn-group">
						<a href="#addTag" data-toggle="modal" class="btn btn-sm btn-primary" title="Ajouter un Tag"><span class="fa fa-plus"></span></a>
						<div id="addTag" class="modal fade" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-12"><h3 class="m-t-none m-b">Ajouter un Tag</h3>
												<form role="form" action="blog.php?addTag=true" method="post">
													<div class="form-group"><label>Nom</label> <input type="text" name="name" placeholder="Tag" class="form-control"></div>
													<div>
														<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Ajouter</strong></button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox-tools">
						<strong>Tags</strong>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
						<tr>
							<th class="col-sm-10">Nom</th>
							<th class="col-sm-2">Actions</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($tags as $tag) { ?>
							<tr>
								<td><?php echo $tag['name']; ?></td>
								<td><a href="#editTag<?php echo $tag['id']; ?>" data-toggle="modal" class="btn btn-warning"><span class="fa fa-edit"></span></a> <a href="blog.php?deleteTag=<?php echo $tag['id']; ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a></td>
							</tr>
							<div id="editTag<?php echo $tag['id']; ?>" class="modal fade" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">
											<div class="row">
												<div class="col-sm-12"><h3 class="m-t-none m-b">Editer une categorie</h3>
													<form role="form" action="blog.php?editTag=<?php echo $tag['id']; ?>" method="post">
														<div class="form-group"><label>Nom</label> <input type="text" name="name" value="<?php echo $tag['name']; ?>" class="form-control"></div>
														<div>
															<button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Editer</strong></button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
