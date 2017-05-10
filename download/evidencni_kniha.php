<?
	session_start();
	ini_set('auto_detect_line_endings',TRUE);
	include '../inc_db_connect.php';
	include_once '../inc_library.php';
	
	$sql='	SELECT 
				GUID,
				UID,
				prijmeni,
				jmeno,
				narozen,
				adresa1,
				cpopisne,
				adresa2,
				PSC,
				zahajen,
				ukoncen
			FROM matrika
			WHERE loc="'.$_SESSION['loc'].'"';

	$result = mysqli_query ($db_connect, $sql);
	$radek = mysqli_fetch_array ($result);	

	$filename = 'evidencni_kniha_'.$_SESSION['loc'].'.csv';


/*HLAVICKA*/
	$CSVhead='Ev.č,Příjmení a jméno,Druh výcviku,Skupina,Narozen,Adresa pobytu,Zahájení,Ukončení';
/*HLAVICKA KONEC*/	


/*PARTICIPANTS*/
	$CSVparticipants='';
	while ($rsX=mysqli_fetch_array($result)) {
		$CSVradek=
			$rsX['UID'].','
			.$rsX['prijmeni'].' '.$rsX['jmeno'].','
			.'ISP,'
			.skupina_kod('matrika',$rsX['GUID']).','
			.date('d.m.Y', strtotime($rsX['narozen'])).','
			.str_replace(",", " ", $rsX['adresa1']).' '.$rsX['cpopisne'].' '.$rsX['adresa2'].' '.$rsX['PSC'].','
			.date('d.m.Y', strtotime($rsX['zahajen'])).','
			.date('d.m.Y', strtotime($rsX['ukoncen']))
			.PHP_EOL;
		$CSVparticipants=$CSVparticipants.$CSVradek;
	}

	/*SESTAVENI CSV*/
		
	$XMLcontent=$CSVhead.PHP_EOL.$CSVparticipants;
?>