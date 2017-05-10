<?
	session_start();
	include '../inc_db_connect.php';

	$sql='UPDATE registrace SET application1=NOT application1 WHERE GUID="'.$_GET['GUID'].'"';
	mysqli_query ($db_connect, $sql);
	
?>