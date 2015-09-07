<?php
	require('init.php');

	if (getVar('name') && getVar('email') && getVar('subject') && getVar('message'))
	{
		$headers = 'From: '.getVar('name').'<'.getVar('email').'>'."\r\n".'Reply-To: '.getVar('email')."\r\n";
		mail(MAIL_ADDR, '[CONTACT] '.getVar('message'), 'De la part de '.getVar('name').' ('.getVar('email').'):'."\r\n".getVar('message'));
		render('contact', array('messageSent' => true));
	}

	render('contact', array('messageSent' => false));
?>
