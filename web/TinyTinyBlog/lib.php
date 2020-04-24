<?php
	/********************************************
	* Common and Template related functions     *
	********************************************/
	function debug($var, $die = true)
	{
		echo '<pre>';
		print_r($var);
		echo '</pre>';
		if ($die)
			die();
	}

	function render($page, $vars = null)
	{
		if ($vars)
			extract($vars);
		include('tpl/head.php');
		include('tpl/header.php');
		include('tpl/'.$page.'.php');
		include('tpl/foot.php');
		die();
	}

	function getVar($varName, $noHtml = true)
	{
		if ($noHtml)
			return htmlentities(strip_tags(($_POST[$varName])?$_POST[$varName]:$_GET[$varName]));
		else
			return ($_POST[$varName])?$_POST[$varName]:$_GET[$varName];
	}

	/********************************************
	* Folders/files related functions           *
	********************************************/

	function delDir($dir)
	{
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file)
			(is_dir($dir.'/'.$file))? delDir($dir.'/'.$file) : unlink($dir.'/'.$file);
		return rmdir($dir);
	}

	/********************************************
	* Crypt/Password related functions          *
	********************************************/
	function genPassword($length = 9)
	{
		$password = '';
		$possible = '012346789abcdfghjkmnpqrtvwxyzABCDFGHJKLMNPQRTVWXYZ!@#$^&*()+=[]{}|.,';
		for ($i = 0;$i < $length;$i++)
		{
		  $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		  $password .= $char;
		}
		return $password;
	}

	function passwordHash($plain)
	{
		return hash('sha512', SALT.$plain);
	}

	// Thanks Derek Woods for this one
	function aesEncrypt($var)
	{
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, AES_KEY, str_pad($val, (16*(floor(strlen($val) / 16)+1)), chr(16-(strlen($var) % 16))),
			MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM));
	}

	function aesDecrypt($var)
	{
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, AES_KEY, $var, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,
			MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM)), "\0..\16");
	}

	/********************************************
	* Network related functions                 *
	********************************************/
	function getCurl($url)
	{
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	  $data = curl_exec($ch);
	  curl_close($ch);
	  return $data;
	}

	/********************************************
	* Text/Date formating functions             *
	********************************************/
	// Thanks Zachary Johnson for this one
	function timeAgo($time)
	{
	    $timestamp = time()-$time;
	    if ($timestamp < 1)
	        return 'Now';
	    foreach (array(31104000 => 'year', 2592000 => 'month', 86400 => 'day', 3600 => 'hour', 60 => 'minute', 1 => 'second') as $secs => $str)
	        if ($timestamp/$secs >= 1)
	            return round($timestamp/$secs).' '.$str.(($r > 1)?'s':'').' ago';
	}

	function formatSize($size)
	{
	    $units = array('o', 'Ko', 'Mo', 'Go', 'To', 'Po');
	    for ($i = 0; $size > 1024; $i++)
	        $size /= 1024;
	    return round($size, 2).' '.$units[$i];
	}
?>
