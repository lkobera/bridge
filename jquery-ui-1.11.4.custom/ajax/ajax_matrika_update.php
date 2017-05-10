<?
	session_start();
	include '../inc_db_connect.php';

	$sql='UPDATE matrika SET '.$_GET['column'].'="'.$_GET['value'].'" WHERE GUID="'.$_GET['GUID'].'"';
	mysqli_query ($db_connect, $sql);
	
?>