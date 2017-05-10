<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='INSERT INTO uzivatel (jmeno) VALUES ("'.$_GET['jmeno'].'")';
	echo $sql;
	mysqli_query ($db_connect, $sql);
	$id = mysqli_insert_id($db_connect);
	
	$sql='INSERT INTO uzivatel_prava (UZid) VALUES ("'.$id.'")';
	echo $sql;
	mysqli_query ($db_connect, $sql);

	
?>