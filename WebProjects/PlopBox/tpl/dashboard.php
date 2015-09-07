<div class="container">
	<div class="row">
		<div class="span12">
			<h4 class="header">Utilisation des serveurs</h4>
			<div class="row-fluid">
				<div class="span12">
					<div class="widget">
						<table style="width:100%">
							<tr>
								<td class="bar-label">Teamspeak</td>
								<td class="bar-number"><?php echo count($voiceUsers); ?></td>
								<td>
									<div class="progress">
										<div style="width: <?php echo ((count($voiceUsers))/10)*100; ?>%" class="bar"></div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="bar-label">L4D2 Regular</td>
								<td class="bar-number"><?php echo $regularInfos['players']; ?> / <?php echo $regularInfos['max_players']; ?></td>
								<td>
									<div class="progress">
										<div style="width: <?php echo ($regularInfos['players']/$regularInfos['max_players'])*100; ?>%;" class="bar"></div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="bar-label">L4D2 SuperTanks</td>
								<td class="bar-number"><?php echo $supertanksInfos['players']; ?> / <?php echo $supertanksInfos['max_players']; ?></td>
								<td>
									<div class="progress">
										<div style="width: <?php echo ($supertanksInfos['players']/$supertanksInfos['max_players'])*100; ?>%;" class="bar"></div>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<hr/>
			<div class="row-fluid">
				<div class="span4">
					<div class="table-panel">
						<h4>L4D2 Regular</h4>
						<h5><b>Map:</b> <?php echo $regularInfos['map']; ?></h5>
						<?php
							if (!$regularPlayers)
								echo '<h5>Personne sur ce serveur</h5>';
							else
							{
								echo '<table class="table table-striped">';
								echo '<tbody>';
								foreach ($regularPlayers as $player)
									echo '<tr><td>'.$player['name'].'</td></tr>';
								echo '</tbody></table>';
							}
						?>
					</div>
				</div>
				<div class="span4">
					<div class="table-panel">
						<h4>L4D2 SuperTanks</h4>
						<h5><b>Map:</b> <?php echo $supertanksInfos['map']; ?></h5>
						<?php
							if (!$supertanksPlayers)
								echo '<h5>Personne sur ce serveur</h5>';
							else
							{
								echo '<table class="table table-striped">';
								echo '<tbody>';
								foreach ($supertanksPlayers as $player)
									echo '<tr><td>'.$player['name'].'</td></tr>';
								echo '</tbody></table>';
							}
						?>
					</div>
				</div>
				<div class="span4">
					<div class="table-panel">
						<h4>Teamspeak</h4>
						<h5><b>URL:</b> <?php echo TEAMSPEAK_SERVER; ?> // <b>Pass:</b> <?php echo TEAMSPEAK_PASSWORD; ?></h5>
						<?php
							if (!$voiceUsers)
								echo '<h5>Personne sur ce serveur</h5>';
							else
							{
								echo '<table class="table table-striped">';
								echo '<tbody>';
								foreach ($voiceUsers as $user)
									echo '<tr><td>'.$user['name'].'</td><td>'.$user['status'].'</td></tr>';
								echo '</tbody></table>';
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
