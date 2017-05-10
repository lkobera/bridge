<?
include 'inc_library.php';
include 'inc_db_connect.php';

$arrayUser=arrayUser();
$osnovaZK=osnovaZK();


/*EMAIL - ALERT ZKOUSKY -4 dny   *******************************************************************/
	$x=0;
	foreach ($arrayUser as $user) { /*maily chodi adminum*/
		if (($user['pravo-admin']==1) AND ($user['aktivni']==1)) $to[$x]=$user[email];
		$x++;
	}
	
	$examDay = date('Y-m-d',strtotime(date('Y-m-d')."+4 days"));
	$sql = 'SELECT id, loc FROM zkousky_terminy WHERE datum="'.$examDay.'"';
	$result=mysqli_query ($db_connect, $sql);
	
	$subject='URGENTNÍ! Zkoušky - dnes odeslat seznam';
	$message='<h3>Dnes odeslat seznam ke zkouškám:</h3>';
	
	/*debug*/
	/*unset($to);
	$to[0]='lukas.kobera@autoskolaeasy.cz';	*/
	
	while ($radek=mysqli_fetch_array($result)) {
		$message = $message."[".$radek['id']."] <a href='http://admin.autoskolaeasy.cz/?menu=zkousky_seznam&id=".$radek[id]."'>".$radek['loc']." ".date('d.m.Y', strtotime($examDay))."</a><br>";
	}
	$message=$message.'<br><br><em>Tato zpráva je generována automaticky pro uživatele systému s právem Admin, neodpovídejte na ni.</em>';
	if ($result->num_rows >0) mailer($to,$subject,$message);

	journal_email($to, $subject,$message,"scheduler.php");








	

/*ARCHIVACE HOTOVYCH STUDENTU************************************************************/

	$sql='SELECT GUID, PPV, PJ_A, PJ_B FROM matrika WHERE status>=4 AND status<=5';
	$result=mysqli_query ($db_connect, $sql);
	$ok=0;
	while ($radek=mysqli_fetch_array($result)){
		$skupina=skupina_kod('matrika',$radek['GUID']);
		/*otestuju shodu predpisu zkousky s vysledkem*/
		if (($osnovaZK[skupina_kod('matrika',$radek['GUID'])]['A']==1) AND ($radek['PJ_A']==1)) $ok=1;
		if ($osnovaZK[skupina_kod('matrika',$radek['GUID'])]['B']==1) {if ($radek['PJ_B']==1) $ok=1; else $ok=0;}
		if ($ok==1) {
			$sql='UPDATE matrika SET status="6" WHERE GUID="'.$radek['GUID'].'"';
			mysqli_query ($db_connect, $sql);
		}	
	}
	
	
	
/* Aktivace eventu podle startdate*********************************************************/		
	$sql='SELECT ID FROM event WHERE start_date=CURDATE() AND status>0';
	$result=mysqli_query ($db_connect, $sql);

	while ($rsX=mysqli_fetch_array($result)) {
		$sql='UPDATE event SET status="2" WHERE ID="'.$rsX['ID'].'"';
		mysqli_query ($db_connect, $sql);
	}

/* Archivace eventu podle enddate**********************************************************/


	$sql='	SELECT 
				a.ID
			FROM event a
			JOIN (
				SELECT ID, MAX( c.PPV ) AS end_date
				FROM (
					SELECT ID, PPV1_date AS PPV FROM `event`
					UNION ALL
					SELECT ID, PPV2_date FROM `event`
					UNION ALL
					SELECT ID, PPV3_date FROM `event`
					UNION ALL
					SELECT ID, PPV4_date FROM `event`
					UNION ALL
					SELECT ID, TZBJ_date FROM `event`
					UNION ALL
					SELECT ID, ZPOP_date FROM `event`
					) AS c
				GROUP BY c.ID
				) AS b 
				ON a.ID = b.ID
				WHERE b.end_date=DATE_ADD(CURDATE(), INTERVAL -1 DAY)';

	$result=mysqli_query ($db_connect, $sql);
	while ($rsX=mysqli_fetch_array($result)) {
		$sql='UPDATE event SET status="4" WHERE ID="'.$rsX['ID'].'"';
		mysqli_query ($db_connect, $sql);
	}

?>

