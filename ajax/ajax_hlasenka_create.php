<?
	session_start();
	include '../inc_db_connect.php';
	
	/*zjisti posledni IDcka folderu*/
	$sql='SELECT MAX(id) AS maxID FROM slozky WHERE loc="'.$_SESSION['loc'].'"';
	$result=mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
	
	$id=$radek['maxID']+1;
		
	
	/*vytvori novou hlasenku*/
	$sql='INSERT INTO slozky (id, loc) VALUES ("'.$id.'","'.$_SESSION['loc'].'")';
	mysqli_query ($db_connect, $sql);
	$folderID=mysqli_insert_id($db_connect);
	
	/*nacte a vlozi zaky z registrace*/
	$sql='	SELECT *
			FROM registrace
			WHERE loc="'.$_SESSION['loc'].'"
			AND ApplicationStatus="3"';
	$result=mysqli_query ($db_connect, $sql);
	
	while ($radek=mysqli_fetch_array($result)) {
		$sql2='SELECT MAX(UID) as lastUID FROM matrika WHERE loc="'.$_SESSION['loc'].'"';
		$result2=mysqli_query ($db_connect, $sql2);
		$radek2=mysqli_fetch_array($result2);
		$UID=$radek2['lastUID']+1;
		
				
		$sql='	INSERT INTO matrika
				(
					GUID,
					UID,
					loc,
					folderID,
					jmeno,
					prijmeni,
					obcanstvi,
					rc,
					narozen,
					request,
					maAM,
					maA1,
					maA2,
					maA,
					maB,
					maC,
					maD,
					maE,
					maT,
					chceAM,
					chceA1,
					chceA2,
					chceA,
					chceB,
					adresa1,
					cpopisne,
					adresa2,
					psc,
					tel,
					mail,
					rp,
					poznamka
				)
					
				VALUES
				(
					"'.$radek['GUID'].'",
					"'.$UID.'",
					"'.$radek['loc'].'",
					"'.$folderID.'",
					"'.$radek['jmeno'].'", 
					"'.$radek['prijmeni'].'",
					"'.$radek['obcanstvi'].'",
					"'.$radek['rc'].'",
					"'.$radek['narozen'].'",
					"'.$radek['request'].'",
					"'.$radek['maAM'].'",
					"'.$radek['maA1'].'",
					"'.$radek['maA2'].'",
					"'.$radek['maA'].'",
					"'.$radek['maB'].'",
					"'.$radek['maC'].'",
					"'.$radek['maD'].'",
					"'.$radek['maE'].'",
					"'.$radek['maT'].'",
					"'.$radek['chceAM'].'",
					"'.$radek['chceA1'].'",
					"'.$radek['chceA2'].'",
					"'.$radek['chceA'].'",
					"'.$radek['chceB'].'",
					"'.$radek['adresa1'].'",
					"'.$radek['cpopisne'].'",
					"'.$radek['adresa2'].'",
					"'.$radek['psc'].'",
					"'.$radek['tel'].'",
					"'.$radek['mail'].'",
					"'.$radek['rp'].'",
					"'.$radek['poznamka'].'"
			)';
			mysqli_query ($db_connect, $sql);
			$sql='DELETE FROM registrace WHERE GUID="'.$radek['GUID'].'"';
			mysqli_query ($db_connect, $sql);
	}

?>