<?
	session_start();
	include '../inc_db_connect.php';

	$sql='INSERT INTO uzivatel_pausal (UZid, vykon, datum_od,pausal) VALUES ("'.$_GET['UZid'].'","'.$_GET['skupina'].'","'.date ('Y-m-d', strtotime($_GET['datum'])).'","'.$_GET['pausal'].'")';
	mysqli_query ($db_connect, $sql);
	
?>