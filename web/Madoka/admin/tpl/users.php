


<div id="page-wrapper" class="gray-bg">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-10">
			<h2>Liste des utilisateurs</h2>
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
					<a href="#addUser" data-toggle="modal" class="btn btn-sm btn-primary" title="Ajouter utilisateur"><span class="fa fa-plus"></span></a>
					<div id="addUser" class="modal fade" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">
									<div class="row">
										<div class="col-sm-12"><h3 class="m-t-none m-b">Ajouter un utilisateur</h3>
											<form role="form" action="users.php?add=true" method="post">
												<div class="form-group"><label>Nom</label> <input type="text" name="name" placeholder="J Random Hacker" class="form-control"></div>
												<div class="form-group"><label>Email</label> <input type="email" name="email" placeholder="example@fablab-robert-houdin.org" class="form-control"></div>
												<div class="form-group">
													<label>Roles</label>
													<div class="modal-chosen" style="min-width: 100%!important;">
													<select data-placeholder="" name="roles[]" class="chosen-select" multiple tabindex="4">
														<option value="admin">Administrateur</option>
														<option value="moderator">Moderateur</option>
														<option value="writer">Redacteur</option>
													</select>
													</div>
												</div>
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
						<th class="col-sm-4">Nom</th>
						<th class="col-sm-3">Email</th>
						<th class="col-sm-4">Roles</th>
						<th class="col-sm-1">Actions</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($users as $user) { ?>
						<tr>
							<td><?php echo $user['name']; ?></td>
							<td><?php echo $user['email']; ?></td>
							<td>
								<?php if ($user['is_admin']) echo '<span class="label label-success">Administrateur</span> '; ?>
								<?php if ($user['is_moderator']) echo '<span class="label label-success">Moderateur</span> '; ?>
								<?php if ($user['is_writer']) echo '<span class="label label-success">Redacteur</span> '; ?>
							</td>
							<td><a href="#editUser<?php echo $user['id']; ?>" data-toggle="modal" class="btn btn-warning"><span class="fa fa-edit"></span></a> <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a></td>
						</tr>
						<div id="editUser<?php echo $user['id']; ?>" class="modal fade" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-12"><h3 class="m-t-none m-b">Editer un utilisateur</h3>
												<form role="form" action="users.php?edit=<?php echo $user['id']; ?>" method="post">
													<div class="form-group"><label>Nom</label> <input type="text" name="name" value="<?php echo $user['name']; ?>" class="form-control"></div>
													<div class="form-group"><label>Email</label> <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control"></div>
													<div class="form-group">
														<label>Roles</label>
														<div class="modal-chosen" style="min-width: 100%!important;">
														<select data-placeholder="" name="roles[]" class="chosen-select" multiple tabindex="4">
															<option value="admin" <?php if ($user['is_admin']) echo 'selected="selected"'; ?>>Administrateur</option>
															<option value="moderator" <?php if ($user['is_moderator']) echo 'selected="selected"'; ?>>Moderateur</option>
															<option value="writer" <?php if ($user['is_writer']) echo 'selected="selected"'; ?>>Redacteur</option>
														</select>
														</div>
													</div>
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
