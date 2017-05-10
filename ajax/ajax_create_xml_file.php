<?php
	include '../inc_db_connect.php';
	include '../inc_library.php';

	/*tady se naplni XMLcontent + filename*/
	if(isset($_GET['seznam'])) include '../download/seznam.php';
	if(isset($_GET['hlasenka'])) include '../download/hlasenka.php';
	if(isset($_GET['evidencni_kniha'])) include '../download/evidencni_kniha.php';


	/*sestaveni a odeslani souboru*/
	$handle = fopen($filename, 'w');
	fwrite($handle, $XMLcontent);
	fclose($handle);
	
	
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Length: ". filesize("$filename").";");
	header("Content-Disposition: attachment; filename=$filename");
	header("Content-Type: application/octet-stream; "); 
	header("Content-Transfer-Encoding: binary");
	
	readfile($filename);

?>