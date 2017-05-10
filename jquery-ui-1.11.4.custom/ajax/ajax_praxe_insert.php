<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='		INSERT INTO tk_pj (GUID, datum, ucitel, skupina)';
	$sql=$sql.'	VALUES ("'.$_GET['GUID'].'","'.date('Y-m-d', strtotime ($_GET['datum'])).'","'.$_SESSION['user'].'","'.$_GET['skupina'].'")';
	mysqli_query ($db_connect, $sql);

?>