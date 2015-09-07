<div id="page-wrapper" class="gray-bg">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Gestion des liens</h2>
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
											<div class="col-sm-12"><h3 class="m-t-none m-b">Ajouter un lien</h3>
												<form role="form" action="links.php?add=true" method="post">
													<div class="form-group"><label>Nom</label> <input type="text" name="name" placeholder="Lien" class="form-control"></div>
													<div class="form-group"><label>URL</label> <input type="text" name="url" placeholder="http://www.fablab-robert-houdin.org" class="form-control"></div>
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
							<th class="col-sm-5">Nom</th>
							<th class="col-sm-6">URL</th>
							<th class="col-sm-1">Actions</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($links as $link) { ?>
							<tr>
								<td><?php echo $link['name']; ?></td>
								<td><?php echo $link['url']; ?></td>
								<td><a href="#editlink<?php echo $link['id']; ?>" data-toggle="modal" class="btn btn-warning"><span class="fa fa-edit"></span></a> <a href="links.php?delete=<?php echo $link['id']; ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a></td>
							</tr>
							<div id="editlink<?php echo $link['id']; ?>" class="modal fade" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">
											<div class="row">
												<div class="col-sm-12"><h3 class="m-t-none m-b">Editer un lien</h3>
													<form role="form" action="links.php?edit=<?php echo $link['id']; ?>" method="post">
														<div class="form-group"><label>Nom</label> <input type="text" name="name" value="<?php echo $link['name']; ?>" class="form-control"></div>
														<div class="form-group"><label>url</label> <input type="text" name="url" value="<?php echo $link['url']; ?>" class="form-control"></div>
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
