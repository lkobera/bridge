<?
	session_start();
	include '../inc_db_connect.php';

	$sql='UPDATE zkousky_terminy SET locked=NOT locked WHERE id="'.$_GET['ZKid'].'"';
	mysqli_query ($db_connect, $sql);
	
?>