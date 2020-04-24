<?php
	function parseUrl($url)
	{
		$url = str_ireplace( "www.", "http://www.", $url );
		$url = str_ireplace( "http://http://www.", "http://www.", $url );
		$url = str_ireplace( "https://http://www.", "https://www.", $url );
		$occurences = preg_match_all('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $url, $match);
		if ($occurences)
		{
			$links = $match[0];
			for ($i=0;$i<$occurences;$i++)
				$url = str_replace($links[$i],'<a href="'.$links[$i].'" rel="nofollow" target="_blank">'.$links[$i].'</a>',$url);
		}
		return $url;
	}

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
		include('tpl/footer.php');
		die();
	}

	function getVar($varName, $noHtml = true)
	{
		if ($noHtml)
			return htmlentities(strip_tags(($_POST[$varName])?$_POST[$varName]:$_GET[$varName]));
		else
			return ($_POST[$varName])?$_POST[$varName]:$_GET[$varName];
	}

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
		return hash('sha1', SALT.$plain);
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

	function formatSize($size)
	{
	    $units = explode(' ','o Ko Mo Go To Po');
	    for ($i = 0; $size > 1024; $i++)
	        $size /= 1024;
	    return round($size, 2).' '.$units[$i];
	}

?>
