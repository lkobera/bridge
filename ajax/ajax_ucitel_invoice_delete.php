<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='DELETE FROM uzivatel_invoice_detail WHERE ID="'.$_GET['ID'].'"';
	mysqli_query ($db_connect, $sql);
?>