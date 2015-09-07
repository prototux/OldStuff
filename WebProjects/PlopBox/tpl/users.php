<div class="container">
	<div class="row">
		<div class="span12"><a href="#newUserModal" data-toggle="modal" class="btn pull-right">Ajouter utilisateur</a>
			<h4 class="header">Utilisateurs</h4>
			<table class="table table-striped sortable">
				<thead>
					<tr>
						<th>Login</th>
						<th>Email</th>
						<th>Level</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($users as $user)
						{
							echo '<tr>';
							echo '<td>'.$user['login'].'</td>';
							echo '<td>'.$user['email'].'</td>';
							echo '<td>'.$levels[$user['level']].'</td>';
							echo '<td>';
							echo '<div class="btn-group">';
							echo '<a href="users.php?action=makeAdmin&id='.$user['id'].'" class="btn">Mettre admin</a>';
							echo '<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>';
							echo '<ul class="dropdown-menu">';
							echo '<li><a href="users.php?action=genPassword&id='.$user['id'].'&email='.$user['email'].'">Renvoyer password</a><a href="users.php?action=removeAdmin&id='.$user['id'].'">Enlever admin</a><a href="users.php?action=ban&id='.$user['id'].'">Bannir</a></li>';
							echo '</ul>';
							echo '</div>';
							echo '</td>';
							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="newUserModal" class="modal hide fade">
	<form class="form-horizontal" action="users.php?action=add" method="post">
		<div class="modal-header">
			<button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
			<h3>Ajouter utilisateur</h3>
		</div>
		<div class="modal-body">
				<div class="control-group">
					<label for="inputCurrentLogin" class="control-label">Login </label>
					<div class="controls">
						<input id="inputCurrentLogin" name="login" type="text" placeholder="Login"/>
					</div>
				</div>
				<div class="control-group">
					<label for="inputEmail" class="control-label">Email </label>
					<div class="controls">
						<input id="inputEmail" name="email" type="text" placeholder="Email"/>
					</div>
				</div>
		</div>
		<div class="modal-footer"><a href="#" data-dismiss="modal" class="btn">Fermer</a><input type="submit" value="Ajouter" class="btn btn-primary"/></div>
	</form>
</div>