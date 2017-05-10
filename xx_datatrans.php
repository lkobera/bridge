<?
	include 'inc_db_connect.php';
	
	
	$sql='SELECT GUID, UID FROM matrika';
	$result=mysqli_query ($db_connect, $sql);
	while ($radek=mysqli_fetch_array($result)) {
		echo $radek['UID'].'->'.$radek['GUID'].'<br>';

		echo $sql='UPDATE tk_pj SET GUID="'.$radek['GUID'].'" WHERE UID="'.$radek['UID'].'"';
		mysqli_query ($db_connect, $sql);
		echo $sql='UPDATE pokladna_zalohy SET GUID="'.$radek['GUID'].'" WHERE UID="'.$radek['UID'].'"';
		mysqli_query ($db_connect, $sql);
		echo $sql='UPDATE zkousky_zaci SET GUID="'.$radek['GUID'].'" WHERE UID="'.$radek['UID'].'"';
		mysqli_query ($db_connect, $sql);


	}
	
?>