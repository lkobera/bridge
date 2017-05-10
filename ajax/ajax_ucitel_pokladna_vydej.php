<?
	session_start();
	include '../inc_db_connect.php';
	include '../inc_library.php';
	$arrayUser=arrayUser();
	
	if ($_GET['ucet']>999000) $_GET['popis']=$arrayUser[$_GET['popis']]['jmeno'];
	$sql='	INSERT
			INTO pokladna_vydaje
			(
				datum,
				uzivatel_ucet,
				autor,
				ucet,
				popis,
				castka
			)
			VALUES
			(
				"'.date('Y-m-d', strtotime($_GET['datum'])).'",
				"'.$_GET['uzivatel_ucet'].'",
				"'.$_GET['autor'].'",
				"'.$_GET['ucet'].'",
				"'.$_GET['popis'].'",
				"-'.$_GET['castka'].'"
			)';
	echo $sql;
	mysqli_query($db_connect,$sql);
?>