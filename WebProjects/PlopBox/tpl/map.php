<div class="container">
	<div class="row">
		<div class="span12 header">
			<h4>Editer la map</h4>
		</div>
		<div class="span6">
			<form class="form-horizontal" action="map.php?id=<?php echo $map['id']; ?>" method="post">
				<div class="control-group">
					<label for="inputName" class="control-label">Nom </label>
					<div class="controls">
						<input id="inputName" name="name" type="text" value="<?php echo $map['name']; ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label for="inputCurrentPassword" class="control-label">Type </label>
					<div class="controls" style="margin-top: 6px;">
						<input id="inputCurrentPassword" name="typeCampaign" type="checkbox" style="margin-top: -4px;" <?php if(strstr($map['type'], 'Campagne')) echo 'checked="checked"'; ?>/> Campagne
						<input id="inputCurrentPassword" name="typeSurvival" type="checkbox" style="margin-top: -4px;" <?php if(strstr($map['type'], 'Survie')) echo 'checked="checked"'; ?>/> Survie
						<input id="inputCurrentPassword" name="typeSpecial" type="checkbox" style="margin-top: -4px;" <?php if(strstr($map['type'], 'Speciale')) echo 'checked="checked"'; ?>/> Speciale
					</div>
				</div>
				<div class="control-group">
					<label for="inputNote" class="control-label">Note </label>
					<div class="controls">
						<select id="inputNote" name="note">
							<option <?php if ($map['note'] == 0) echo 'selected'; ?>>0</option>
							<option <?php if ($map['note'] == 1) echo 'selected'; ?>>1</option>
							<option <?php if ($map['note'] == 2) echo 'selected'; ?>>2</option>
							<option <?php if ($map['note'] == 3) echo 'selected'; ?>>3</option>
							<option <?php if ($map['note'] == 4) echo 'selected'; ?>>4</option>
							<option <?php if ($map['note'] == 5) echo 'selected'; ?>>5</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn">Enrengistrer</button>
					</div>
				</div>
			</form>
		</div>
		<div class="span6">
			<p>Quelques petits trucs a se souvenir avant d'editer une map.</p>
			<ul>
				<li>Le nom n'a rien a voir avec le nom des fichiers.</li>
				<li>Si la map est une co-op speciale, merci d'indiquer que special.</li>
				<li>Mettre a jour ici ne met pas a jour la date dans la liste.</li>
			</ul>
		</div>
	</div>
</div>
