<div id="page-wrapper" class="gray-bg">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Gestion du carousel</h2>
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
						<a href="#addlink" data-toggle="modal" class="btn btn-primary" title="Ajouter un lien"><span class="fa fa-plus"></span></a>
						<div id="addlink" class="modal fade" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-12"><h3 class="m-t-none m-b">Ajouter un element</h3>
												<form role="form" enctype="multipart/form-data" action="carousel.php?add=true" method="post">
												    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
													<div class="form-group"><label>Titre</label> <input type="text" name="title" placeholder="Nouvel element" class="form-control"></div>
													<div class="form-group"><label>Description</label> <input type="text" name="description" placeholder="Nouvel outil!" class="form-control"></div>
													<div class="form-group"><label>Photo</label> <input type="file" name="picture" placeholder="DESC42.jpg" class="form-control"></div>
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
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
						<tr>
							<th class="col-sm-5">Titre</th>
							<th class="col-sm-6">Description</th>
							<th class="col-sm-1">Actions</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($elements as $element) { ?>
							<tr>
								<td><?php echo $element['title']; ?></td>
								<td><?php echo $element['description']; ?></td>
								<td><a href="#editlink<?php echo $element['id']; ?>" data-toggle="modal" class="btn btn-warning"><span class="fa fa-edit"></span></a> <a href="carousel.php?delete=<?php echo $element['id']; ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a></td>
							</tr>
							<div id="editlink<?php echo $element['id']; ?>" class="modal fade" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">
											<div class="row">
												<div class="col-sm-12"><h3 class="m-t-none m-b">Editer un lien</h3>
													<form role="form" action="carousel.php?edit=<?php echo $element['id']; ?>" method="post">
														<div class="form-group"><label>Titre</label> <input type="text" name="title" value="<?php echo $element['title']; ?>" class="form-control"></div>
														<div class="form-group"><label>Description</label> <input type="text" name="description" value="<?php echo $element['description']; ?>" class="form-control"></div>
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
