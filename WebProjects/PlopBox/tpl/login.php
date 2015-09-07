<div class="container">
	<div class="row">
		<div class="span6 offset2">
			<div class="login">
				<form class="form-horizontal" action="login.php" method="post">
					<div class="control-group">
						<div class="controls">
							<h4>Login</h4>
						</div>
					</div>
					<div class="control-group">
						<label for="inputLogin" class="control-label">Login </label>
						<div class="controls">
							<input id="inputLogin" name="login" type="text" placeholder="Login"/>
						</div>
					</div>
					<div class="control-group">
						<label for="inputPassword" class="control-label">Password </label>
						<div class="controls">
							<input id="inputPassword" name="password" type="password" placeholder="Password"/>
						</div>
					</div>
					<div class="control-group">
						<div class="controls"><input type="submit" class="btn" value="login" /></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php if ($logfailed) { ?>
	<script type="text/javascript">
		toastr.options = {positionClass: 'toast-bottom-left'};
		toastr.error('Beau fail, avec un login/pass correct, ca sera surement mieux :)', 'Erreur');
	</script>
<?php } ?>