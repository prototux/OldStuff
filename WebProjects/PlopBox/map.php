<?php
	require('init.php');

	if (!$_SESSION['id'] || $_SESSION['level'] < 2)
		render('forbidden');

	//Updating map name // type
	if (getVar('name'))
	{
		$type = '';
		if (getVar('typeCampaign'))
			$type .= '<span class="label label-success">Campagne</span> ';
		if (getVar('typeSurvival'))
			$type .= '<span class="label label-warning">Survie</span> ';
		if (getVar('typeSpecial'))
			$type .= '<span class="label label-info">Speciale</span> ';
		$sth = $dbh->prepare("UPDATE maps SET name = :name, type = :type, note = :note WHERE id = :id LIMIT 1");
		$sth->execute(array(':name' => getVar('name'), ':type' => $type, ':note' => getVar('note'), ':id' => getVar('id')));
	}

	$mapQuery = $dbh->prepare("SELECT id, name, type, file_rar, file_vpk, size_rar, size_vpk, note, date FROM maps WHERE id = :id");
	$mapQuery->execute(array(':id' => getVar('id')));
	$map = $mapQuery->fetch();
	render('map', array('map' => $map));
?>
