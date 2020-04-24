<div class="container">
	<div class="row">
		<div class="span12">
			<?php
				if (!$server)
				{
					echo '<form action="maps.php" method="post" style="display: inline;">';
					echo '<input type="submit" class="btn pull-right" value="Ok" />';
					echo '<input type="text" name="search" class="pull-right" placeholder="Rechercher" />';
					echo '</form>';
				}
			?>
			<h4 class="header">Maps <?php if($server) echo 'on '.$server; ?></h4>
			<table class="table table-striped sortable">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Nom du rar</th>
						<th>Taille du rar</th>
						<th>Taille du vpk</th>
						<th>Type</th>
						<th>Note</th>
						<th>Derniere MaJ</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($maps as $map)
						{
							echo '<tr>';
							if ($_SESSION['id'] && $_SESSION['level'] >= 2)
									echo '<td><a href="map.php?id='.$map['id'].'">'.$map['name'].'</a></td>';
							else
									echo '<td>'.$map['name'].'</td>';

							echo '<td>'.$map['file_rar'].'</td>';
							echo '<td>'.formatSize($map['size_rar']).'</td>';
							echo '<td>'.formatSize($map['size_vpk']).'</td>';
							echo '<td>'.(($map['type'])?$map['type']:'--').'</td>';

							if ($map['note'] >= 4)
									echo '<td><span class="label label-success">'.$map['note'].'</span></td>';
							else if ($map['note'] == 3)
									echo '<td><span class="label label-warning">'.$map['note'].'</span></td>';
							else if ($map['note'] >= 1)
									echo '<td><span class="label label-important">'.$map['note'].'</span></td>';
							else
								echo '<td>--</td>';

							echo '<td>'.date('d/m/Y', $map['date']).'</td>';
							echo '<td>';
							echo '<div class="btn-group">';
							echo '<a href="maps-rar/'.$map['file_rar'].'" class="btn">Telecharger (.rar)</a>';
							echo '<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>';
							echo '<ul class="dropdown-menu">';
							if ($_SESSION['id'])
								echo '<li><a href="server.php?action=install&mapid='.$map['id'].'&server=regular">Jouer sur Regular</a><a href="server.php?action=install&mapid='.$map['id'].'&server=supertanks">Jouer sur SuperTanks</a></li>';
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
