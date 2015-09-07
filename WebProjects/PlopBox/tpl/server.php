<div class="container">
	<div class="row">
		<div class="span12 header">
			<h4>Configuration du serveur</h4>
		</div>
		<div class="span6">
				<h5>Controle des serveurs</h5>
				<div class="control-group">
					<div class="controls">
						<a href="server.php?action=start" class="btn btn-success">Demarrer</a>
						<a href="server.php?action=stop" class="btn btn-danger">Eteindre</a>
						<a href="server.php?action=restart" class="btn btn-warning">Redemarrer</a>
						<a href="server.php?action=check" class="btn btn-info">Verifier status</a>
					</div>
				</div>
				<h5>Gestion des maps</h5>
				<div class="control-group">
					<div class="controls">
						<a href="server.php?action=reload" class="btn btn-success">Recharger</a>
						<a href="server.php?action=clean" class="btn btn-danger">Nettoyer</a>
						<a href="server.php?action=list&server=regular" class="btn btn-info">Lister/Regular</a>
						<a href="server.php?action=list&server=supertanks" class="btn btn-info">Lister/Supertanks</a>
					</div>
				</div>
		</div>
		<div class="span6">
				<h5>Installer une map sur regular</h5>
				<div class="control-group">
					<form action="server.php?action=install" method="get" />
						<div class="controls">
							<select name="mapid" class="selectpicker">
								<?php foreach($maps as $map) echo '<option value="'.$map['id'].'">'.$map['name'].' // '.$map['file_rar'].'</option>'; ?>
							</select>
							<input type="hidden" name="server" value="regular" />
							<input type="hidden" name="action" value="install" />
							<input type="submit" value="Ok" class="btn btn-green" style="margin-top: -11px!important;">
						</div>
					</form>
				</div>
				<h5>Installer une map sur supertanks</h5>
				<div class="control-group">
					<form action="server.php?action=install" method="get" />
						<div class="controls">
							<select name="mapid" class="selectpicker">
								<?php foreach($maps as $map) echo '<option value="'.$map['id'].'">'.$map['name'].' // '.$map['file_rar'].'</option>'; ?>
							</select>
							<input type="hidden" name="server" value="supertanks" />
							<input type="hidden" name="action" value="install" />
							<input type="submit" value="Ok" class="btn btn-green" style="margin-top: -11px!important;">
						</div>
					</form>
				</div>
			<p>Le serveur redemarre automatiquement apres l'installation.</p>
		</div>
	</div>
</div>
<script type="text/javascript">
	toastr.options = {positionClass: 'toast-bottom-left'};
	<?php echo $script; ?>
</script>
