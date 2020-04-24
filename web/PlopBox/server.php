<?php
	require('init.php');

	if (!$_SESSION['id'] || $_SESSION['level'] < 2)
		render('forbidden');

	if (getVar('action'))
	{
		switch (getVar('action'))
		{
			//Start the servers
			case 'start':
				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_start.sh 2>&1');
				if ($return == 'Starting server...ok')
					$script = 'toastr.success(\'Serveurs demarre\', \'Serveurs\');';
				else if ($return == 'Server is already running')
					$script = 'toastr.warning(\'Les serveurs sont deja allume\', \'Serveurs\');';
				else
					$script = 'toastr.error(\'Erreur: '.$return.' \', \'Serveurs\');';
			break;

			//Stop the servers
			case 'stop':
				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_stop.sh 2>&1');
				if ($return == 'Killing server...ok')
					$script = 'toastr.success(\'Serveurs etein\', \'Serveurs\');';
				else if ($return == 'Servers are already stopped')
					$script = 'toastr.warning(\'Les serveurs sont deja arretes\', \'Serveurs\');';
				else
					$script = 'toastr.error(\'Erreur: '.$return.' \', \'Serveurs\');';
			break;

			//Restart the servers
			case 'restart':
				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_stop.sh 2>&1');
				if ($return == 'Killing server...ok')
					$script = 'toastr.success(\'Serveurs eteint\', \'Serveurs\');';
				else if ($return == 'Servers are already stopped')
					$script = 'toastr.warning(\'Les serveurs sont deja arretes\', \'Serveurs\');';
				else
					$script = 'toastr.error(\'Erreur: '.$return.' \', \'Serveurs\');';

				sleep(2);
				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_start.sh 2>&1');
				if ($return == 'Starting server...ok')
					$script .= 'toastr.success(\'Serveurs demarre\', \'Serveurs\');';
				else if ($return == 'Server is already running')
					$script .= 'toastr.warning(\'Les serveurs sont deja allume\', \'Serveurs\');';
				else
					$script .= 'toastr.error(\'Erreur: '.$return.' \', \'Serveurs\');';
			break;

			//Update srcds
			case 'check':
				$return = shell_exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_check.sh 2>&1');
				//debug($return);
				if (strpos($return, 'rok'))
					$script .= 'toastr.success(\'Regular est OK\', \'Serveurs\');';
				else if (strpos($return, 'rko'))
					$script .= 'toastr.error(\'Regular est KO\', \'Serveurs\');';
				if (strpos($return, 'sok'))
					$script .= 'toastr.success(\'Supertanks est OK\', \'Serveurs\');';
				else if (strpos($return, 'sko'))
					$script .= 'toastr.error(\'Supertanks est KO\', \'Serveurs\');';
			break;

			//List all installed maps
			case 'list':
				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_list.sh '.((getVar('server') == 'regular')?'regular':'supertanks').' 2>&1');
				if ($return)
				{
					$i = 0;
					$mapsList = explode(' ', $return);
					foreach($mapsList as $mapName)
					{
						$mapsQuery = $dbh->prepare("SELECT id, name, type, file_rar, file_vpk, size_rar, size_vpk, date FROM maps WHERE file_vpk = :file_vpk");
						$mapsQuery->execute(array(':file_vpk' => $mapName));
						$maps[$i++] = $mapsQuery->fetch();
					}
					render('maps', array('maps' => $maps, 'server' => getVar('server')));
				}
				$script .= 'toastr.warning(\'Aucune map sur ce serveur\', \'Serveurs\');';
			break;

			//Delete all installed maps
			case 'clean':
				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_clean.sh 2>&1');
				if ($return == 'Clean maps...ok')
					$script = 'toastr.success(\'Serveurs nettoyes\', \'Serveur\');';
				else
					$script = 'toastr.error(\'Erreur: '.$return.' \', \'Serveur\');';
			break;

			//Install a map and restart the server.
			case 'install':
				$mapQuery = $dbh->prepare("SELECT file_vpk FROM maps WHERE id = :id");
				$mapQuery->execute(array(':id' => getVar('mapid')));
				$mapName = $mapQuery->fetch()['file_vpk'];

				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_install.sh '.((getVar('server') == 'regular')?'regular':'supertanks').' '.$mapName.' 2>&1');
				if ($return == 'Install map...ok')
					$script = 'toastr.success(\'Map installee\', \'Serveur\');';
				else
					$script = 'toastr.error(\'Erreur: '.$return.' \', \'Serveur\');';

				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_stop.sh 2>&1');
				if ($return == 'Killing server...ok')
					$script .= 'toastr.success(\'Serveurs eteint\', \'Serveurs\');';
				else if ($return == 'Servers are already stopped')
					$script .= 'toastr.warning(\'Les serveurs sont deja arretes\', \'Serveurs\');';
				else
					$script .= 'toastr.error(\'Erreur: '.$return.' \', \'Serveurs\');';

				sleep(2);
				$return = exec('sudo -b -n -u "'.UNIX_USER.'" '.GAME_PATH.'server_start.sh 2>&1');
				if ($return == 'Starting server...ok')
					$script .= 'toastr.success(\'Serveurs demarre\', \'Serveurs\');';
				else if ($return == 'Server is already running')
					$script .= 'toastr.warning(\'Les serveurs sont deja allume\', \'Serveurs\');';
				else
					$script .= 'toastr.error(\'Erreur: '.$return.' \', \'Serveurs\');';
			break;

			//Reload maps folder and update DB
			case 'reload':
				$i = 0;
				set_time_limit(120);
				$insertQuery = $dbh->prepare("INSERT INTO maps (name, file_rar, file_vpk, size_rar, size_vpk, date) VALUES (:name, :file_rar, :file_vpk, :size_rar, :size_vpk, NOW())");
				$updateQuery = $dbh->prepare("UPDATE maps SET file_vpk = :file_vpk, size_rar = :size_rar, size_vpk = :size_vpk, date = NOW() WHERE file_rar = :file_rar LIMIT 1");
				$compareQuery = $dbh->prepare("SELECT size_rar FROM maps WHERE file_rar = :file_rar LIMIT 1");
				if ($handle = opendir(RAR_PATH))
				{
				    $handle2 = opendir(TMP_PATH);
				    while ($rarName = readdir($handle))
				        if (substr($rarName, 0, 1) != '.')
				        {
				                //Clean the tmp folder, the non-clean way, OH THE IRONY!
				                exec('rm -f '.TMP_PATH.'*');

				                // I need to get the rarSize to compare against DB stored one.
				                $rarSize = filesize(RAR_PATH.$rarName);

				                // Get map info in db is alreay installed
				                $compareQuery->execute(array(':file_rar' => $rarName));
				            	$mapInfos = $compareQuery->fetchAll()[0];

				                //Installing the map if new or update
				                if (!$compareQuery->rowCount() || $mapInfos['size_rar'] != $rarSize)
				                {
				                    //Get base name and extract
				                    $basename = basename(RAR_PATH.$rarName, '.rar');
				                    exec('7z x -o "'.TMP_PATH.'" "'.RAR_PATH.$rarName.'" 2>&1');

				                    //Get vpk file name and move it
				                    $vpkName = basename(reset(glob(TMP_PATH.'*.vpk')));
						    		if ($vpkName)
				                            rename(TMP_PATH.$vpkName, VPK_PATH.$vpkName);

				                    //Calculate size and update (or insert).
				                    $vpkSize = filesize(VPK_PATH.$vpkName);
				                    if ($mapInfos['size_rar'])
				                            $updateQuery->execute(array(':file_rar' => $basename.'.rar', ':file_vpk' => $basename.'.vpk', ':size_rar' => $rarSize, ':size_vpk' => $vpkSize));
				                    else
				                            $insertQuery->execute(array(':name' => $basename, ':file_rar' => $basename.'.rar', ':file_vpk' => $basename.'.vpk', ':size_rar' => $rarSize, ':size_vpk' => $vpkSize));
				                    $i++;
				                }
				        }
				    closedir($handle);
				    closedir($handle2);
				}
				if ($i)
					$script .= 'toastr.success(\''.$i.' maps ont ete mises a jour\', \'Serveurs\');';
				else
					$script .= 'toastr.warning(\'Aucune nouvelle map\', \'Serveurs\');';
			break;

			case 'sync':
			break;
		}
	}

	$mapsQuery = $dbh->prepare("SELECT id, name, file_rar FROM maps ORDER BY name");
	$mapsQuery->execute();
	$maps = $mapsQuery->fetchAll();
	$mapsQuery->closeCursor();
	render('server', array('script' => str_replace(array("\r", "\r\n", "\n"), ' ', $script), 'maps' => $maps));
?>
