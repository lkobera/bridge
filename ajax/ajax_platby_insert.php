<?
	session_start();
	include '../inc_db_connect.php';
	
	/*ZAPIS PLATBY KURZU*/
	$sql='		INSERT INTO pokladna_zalohy (GUID, datum, platba, autor, cenikID, pocet)';
	$sql=$sql.' VALUES ("'.$_GET['GUID'].'","'.$_GET['datum'].'","'.$_GET['platba'].'","'.$_GET['autor'].'","'.$_GET['cenikID'].'","'.$_GET['pocet'].'")';
	mysqli_query ($db_connect, $sql);
	
	
	/*zapis doplnkovych sluzeb do requestu*/
	if ($_GET['cenikID']>0) {
		$sql='SELECT kod, skupina FROM cenik WHERE ID="'.$_GET['cenikID'].'"';
		$result=mysqli_query ($db_connect, $sql);
		$radek=mysqli_fetch_array($result);
		
		$count=1;
		while ($count<=$_GET['pocet']) {
			$sql='INSERT INTO request_sluzby (GUID,kod,skupina) VALUES ("'.$_GET['GUID'].'","'.$radek['kod'].'","'.$radek['skupina'].'")';
			mysqli_query ($db_connect, $sql);
			$count++;
		}
	}
	
	
?>