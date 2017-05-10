<?
	session_start();

	include '../inc_db_connect.php';
	include '../inc_library.php';
	$arrayUser=arrayUser();
	
	/*odpis zalohy z vydaju*/
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
				"999999",
				"'.$arrayUser[$_GET['komu']]['jmeno'].'",
				"-'.$_GET['castka'].'"
			)';
	mysqli_query($db_connect,$sql);
	
	/*zapis zalohy do prijmu*/
	$sql='	INSERT
			INTO pokladna_zalohy
			(
				datum,
				platba,
				autor,
				cenikID,
				pocet
			)
			VALUES
			(
				"'.date('Y-m-d', strtotime($_GET['datum'])).'",
				"'.$_GET['castka'].'",
				"'.$_GET['komu'].'",
				"999999",
				"1"
			)';
	echo $sql;
	mysqli_query($db_connect,$sql);
	
?>