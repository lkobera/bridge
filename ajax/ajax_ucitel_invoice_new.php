<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='INSERT INTO uzivatel_invoice (UZid, invoiceID) VALUES ("'.$_SESSION['ucitel'].'","'.$_GET['invoiceID'].'")';
	mysqli_query ($db_connect, $sql);
?>