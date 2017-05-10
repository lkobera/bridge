<?
	$sql='	SELECT 
			zkousky_terminy.datum, zkousky_terminy.loc, zkousky_venue.adresa
			FROM zkousky_terminy
			LEFT JOIN zkousky_venue
			ON zkousky_terminy.venue_ID=zkousky_venue.ID
			WHERE zkousky_terminy.ID="'.$_GET['seznam'].'"';
			
	$result = mysqli_query ($db_connect, $sql);
	$radek = mysqli_fetch_array ($result);	

	$filename = $radek['datum'].'_'.$_GET['seznam'].'.xml';


/*HLAVICKA*/
	$XMLhead='
	<carschool>
		<number>489</number>
		<name>autoškolaeasy.cz</name>
		<ordernumber>'.$_GET['seznam'].'</ordernumber>
		<examdate>'.date('d.m.Y', strtotime($radek['datum'])).'</examdate>
		<examplace>nám. Kinských 741/6 Praha 5</examplace>
		<evidencetype>2</evidencetype>
		<form>Individuální studijní plán</form>
		<isp></isp>
	</carschool>';
/*HLAVICKA KONEC*/	


/*PARTICIPANTS*/
	$XMLparticipants='<participants>';
	
 
	$sql='	SELECT 
			matrika.GUID, 
			matrika.UID, 
			matrika.prijmeni, 
			matrika.jmeno, 
			matrika.narozen, 
			matrika.adresa1, 
			matrika.adresa2, 
			matrika.cpopisne,
			matrika.psc, 
			matrika.rp,
			matrika.poznamka_ext
			FROM matrika 
			JOIN zkousky_zaci 
			ON matrika.GUID=zkousky_zaci.GUID 
			WHERE zkousky_zaci.ZKid="'.$_GET['seznam'].'" 
			ORDER BY prijmeni';

	$result = mysqli_query ($db_connect, $sql);
	
	$IDradku=0;
	if ($result==true) {
	
	/*PARTICIPANT*/
		while ($radek = mysqli_fetch_array ($result)) {
			$IDradku++;
			$GUID=$radek['GUID'];
			$UID=$radek['UID'];			
			$jmeno=$radek['jmeno'];
			$prijmeni=$radek['prijmeni'];
			$nationality=$radek['nationality'];
			if ($nationality=='') $nationality='Česká republika';
			$narozen=date('d.m.Y', strtotime($radek['narozen']));
			$adresa1=$radek['adresa1'];
			$adresa2=$radek['adresa2'];
			$cpopisne=$radek['cpopisne'];
			$psc=$radek['psc'];
			$rp=$radek['rp'];
			$poznamka=$radek['poznamka_ext'];
			$XMLmaRP=XMLskupinaMa($GUID);
			$XMLchceRP=XMLskupinaChce($GUID);
			if ($XMLmaRP<>'') {$testtype='rozsireni'; $typeexamext=' Roz';} else {$testtype='prvni'; $typeexamext='';}
			
			$XMLradek='	<participant>
							<id>'.$IDradku.'</id>
							<surname>'.$prijmeni.'</surname>
							<firstname>'.$jmeno.'</firstname>
							<degree></degree>
							<registry>'.$UID.'</registry>
							<dateofbirth>'.$narozen.'</dateofbirth>
							<birthnumber></birthnumber>
							<nationality>'.$nationality.'</nationality>
							<street>'.$adresa1.'</street>
							<city>'.$adresa2.'</city>
							<postcode>'.$psc.'</postcode>
							<license>'.$XMLmaRP.'</license>
							<licensenone></licensenone>
							<licenserequested>'.$XMLchceRP.'</licenserequested>
							<testtype>'.$testtype.'</testtype>
							<typeexam></typeexam>
							<typeexamext>'.$typeexamext.'</typeexamext>
							<note>'.$poznamka.'</note>
							<trainingrange></trainingrange>
							<drivinglicence>'.$rp.'</drivinglicence>
							<finishdate></finishdate>
							<streetnumber>'.$cpopisne.'</streetnumber>
						</participant>';
						
			$XMLparticipants=$XMLparticipants.$XMLradek;			
		}
		
		/*PARTICIPANT KONEC*/
		
	}
	
	$XMLparticipants=$XMLparticipants.'</participants>';
	/*PARTICIPANTS KONEC*/
	
	
	
	/*VEHICLES*/
	$XMLvehicles='<vehicles></vehicles>';
	/*VEHICLES KONEC*/
	
	
	/*SESTAVENI XML*/
		
	$XMLcontent='
		<?xml version="1.0" encoding="utf-8"?>
		<root>
		'.$XMLhead.$XMLparticipants.$XMLvehicles.'
		</root>';
	
	/*odstrani taby*/
	$XMLcontent = trim(preg_replace('/\t/', '', $XMLcontent));
?>