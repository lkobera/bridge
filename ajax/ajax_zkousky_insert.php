<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='INSERT INTO zkousky_terminy (loc, datum) VALUES ("'.$_SESSION['loc'].'","'.$_GET['datum'].'")';
	mysqli_query ($db_connect, $sql);
?>