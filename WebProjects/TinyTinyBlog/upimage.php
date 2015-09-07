<?php
	require('init.php');

	if (getVar('key') != UPLOAD_KEY)
		die('BADKEY');

	if (!isAdmin())
		die('NOADMIN');


	if (getVar('delete_picture') && getVar('gallery_id'))
	{
		$delPicture = $dbh->prepare("DELETE FROM pictures WHERE gallery_id = :id AND number = :number");
		$delPicture->execute(array(':id' => getVar('gallery_id'), ':number' => getVar('delete_picture')));
		unlink('images/galleries/'.getVar('gallery_id').'/'.getVar('delete_picture').'.jpg');
		die('OK');
	}
	if (getVar('data') && getVar('gallery_id') && getVar('file_number'))
	{
        //Write the image
  		$titleData = substr(getVar('data'), strpos(getVar('data'), ',')+1);
  		$titleData = str_replace(' ', '+', $titleData);
		$titleData = base64_decode($titleData);
		file_put_contents('images/galleries/'.getVar('gallery_id').'/'.getVar('file_number').'.jpg', $titleData);

		//Save it in SQL
		$addPicture = $dbh->prepare("INSERT INTO pictures (number, name, gallery_id) VALUES (:number, \" \", :id)");
		$addPicture->execute(array(':number' => getVar('file_number'), ':id' => getVar('gallery_id')));
		die('OK');
	}
	else
		die('KO')
?>
