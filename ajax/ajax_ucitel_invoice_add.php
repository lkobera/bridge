<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='INSERT INTO uzivatel_invoice_detail (invoiceUID, popis, cena_jednotka, pocet) VALUES ("'.$_GET['invoiceUID'].'","'.$_GET['popis'].'","'.$_GET['cena_jednotka'].'","'.$_GET['pocet'].'")';
	mysqli_query ($db_connect, $sql);
?>