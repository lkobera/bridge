<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='DELETE FROM zkousky_zaci WHERE ID="'.$_GET['ID'].'"';
	mysqli_query ($db_connect, $sql);
	
?>