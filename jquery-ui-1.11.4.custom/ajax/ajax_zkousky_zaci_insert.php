<?
	session_start();
	include '../inc_db_connect.php';
	
	$arrayGUID=explode('|',$_GET['GUID']); /*prevezme data ve formatu GUID|PPV|PJ_A|PJ_B*/
	
	
	$sql='SELECT COUNT(GUID) AS test FROM zkousky_zaci WHERE ZKid="'.$_GET['ZKid'].'" AND GUID="'.$arrayGUID[0].'" GROUP BY GUID';
	$result=mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
	
	/*nedovoli vlozit zaka do zkousky duplicitne*/
	if($radek['test']==0) {
		$sql='INSERT INTO zkousky_zaci (ZKid, GUID, PPV, PJ_A, PJ_B) VALUES ("'.$_GET['ZKid'].'","'.$arrayGUID[0].'","'.$arrayGUID[1].'","'.$arrayGUID[2].'","'.$arrayGUID[3].'")';
		mysqli_query ($db_connect, $sql);
	}
?>