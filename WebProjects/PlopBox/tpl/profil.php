<div class="container">
	<div class="row">
		<div class="span12 header">
			<h4>Settings</h4>
		</div>
		<div class="span6">
			<form class="form-horizontal" action="profil.php" method="post">
				<div class="control-group">
					<label for="inputEmail" class="control-label">Email* </label>
					<div class="controls">
						<input id="inputEmail" name="email" type="text" value="<?php echo $email; ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label for="inputCurrentPassword" class="control-label">Current Password </label>
					<div class="controls">
						<input id="inputCurrentPassword" name="oldPassword" type="password" placeholder="Current Password"/>
					</div>
				</div>
				<div class="control-group">
					<label for="inputPassword" class="control-label">Password </label>
					<div class="controls">
						<input id="inputPassword" name="newPassword1" type="password" placeholder="Password"/>
					</div>
				</div>
				<div class="control-group">
					<label for="inputPasswordAgain" class="control-label">Password Again</label>
					<div class="controls">
						<input id="inputPasswordAgain" name="newPassword2" type="password" placeholder="Password Again"/>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn">Save Changes</button>
					</div>
				</div>
			</form>
		</div>
		<div class="span6">
			<p>Quelques petites indications pour modifier le profil.</p>
			<ul>
				<li>Pour changer le mail, les champs par rapport au mot de passe sont optionels</li>
				<li>Pour changer le mot de passe, il faut laisser le mail tel quel.</li>
				<li>Pour les deux, ca marche aussi, pas besoin de changer l'un puis l'autre.</li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
	toastr.options = {positionClass: 'toast-bottom-left'};
	<?php echo $script; ?>
</script>