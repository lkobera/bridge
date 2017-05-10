<?
	session_start();
	include '../inc_db_connect.php';
	
	
	if($_GET['pausal_A']>0) {
		if($_GET['vycvik_A']>0) {
			$sql='INSERT INTO uzivatel_invoice_detail (invoiceUID,popis,cena_jednotka,pocet) VALUES ("'.$_GET['invoiceUID'].'","Výcvik A","'.$_GET['pausal_A'].'","'.$_GET['vycvik_A'].'")';
			mysqli_query ($db_connect, $sql);
		}
		if($_GET['zk_A']>0) {
			$sql='INSERT INTO uzivatel_invoice_detail (invoiceUID,popis,cena_jednotka,pocet) VALUES ("'.$_GET['invoiceUID'].'","Zkouška A","'.$_GET['pausal_A'].'","'.$_GET['zk_A'].'")';
			mysqli_query ($db_connect, $sql);
		}
		if($_GET['rep_A']>0) {
			$sql='INSERT INTO uzivatel_invoice_detail (invoiceUID,popis,cena_jednotka,pocet) VALUES ("'.$_GET['invoiceUID'].'","Repro A","'.$_GET['pausal_A'].'","'.$_GET['rep_A'].'")';
			mysqli_query ($db_connect, $sql);
		}
	}
	
	if($_GET['pausal_B']>0) {
		if($_GET['vycvik_B']>0) {
			$sql='INSERT INTO uzivatel_invoice_detail (invoiceUID,popis,cena_jednotka,pocet) VALUES ("'.$_GET['invoiceUID'].'","Výcvik B","'.$_GET['pausal_B'].'","'.$_GET['vycvik_B'].'")';
			mysqli_query ($db_connect, $sql);
		}
		if($_GET['zk_B']>0) {
			$sql='INSERT INTO uzivatel_invoice_detail (invoiceUID,popis,cena_jednotka,pocet) VALUES ("'.$_GET['invoiceUID'].'","Zkouška B","'.$_GET['pausal_B'].'","'.$_GET['zk_B'].'")';
			mysqli_query ($db_connect, $sql);
		}
		if($_GET['rep_B']>0) {
			$sql='INSERT INTO uzivatel_invoice_detail (invoiceUID,popis,cena_jednotka,pocet) VALUES ("'.$_GET['invoiceUID'].'","Repro B","'.$_GET['pausal_B'].'","'.$_GET['rep_B'].'")';
			mysqli_query ($db_connect, $sql);
		}
	}
	
	if ($_GET['odvody']=='') $_GET['odvody']=0;
	$sql='UPDATE uzivatel_invoice SET locked=true, odvody="'.$_GET['odvody'].'" WHERE invoiceUID="'.$_GET['invoiceUID'].'"';
	echo $sql;
	mysqli_query ($db_connect, $sql);

	
?>