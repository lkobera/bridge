<?
	session_start();
	include '../inc_db_connect.php';

	$sql='UPDATE zkousky_zaci SET '.$_GET['subject'].'= NOT '.$_GET['subject'].' WHERE ID="'.$_GET['ID'].'"';
	mysqli_query ($db_connect, $sql);
	
?>