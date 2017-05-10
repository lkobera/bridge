<?
	session_start();

	include_once '../inc_db_connect.php';

	$sql='DELETE FROM registrace WHERE GUID="'.$_GET['GUID'].'"';
	mysqli_query ($db_connect, $sql);
?>