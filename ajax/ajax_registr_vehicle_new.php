<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='INSERT INTO vehicle (GUID,rz) VALUES (UUID(),"'.$_GET['rz'].'")';
	echo $sql;
	mysqli_query ($db_connect, $sql);

	
?>