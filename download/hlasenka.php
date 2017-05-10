<?
	$sql='	SELECT 
			slozky.zahajeni, slozky.loc, slozky.id, venue.number, venue.venue
			FROM slozky         
			LEFT JOIN venue
			ON slozky.loc=venue.loc
			WHERE slozky.folderID="'.$_GET['hlasenka'].'"';

	$result = mysqli_query ($db_connect, $sql);
	$radek = mysqli_fetch_array ($result);	

	$filename = $radek['zahajeni'].'_'.$radek['id'].'.xml';


/*HLAVICKA*/
	$XMLhead='
	<carschool>
		<number>'.$radek['number'].'</number>
		<name>autoškolaeasy.cz</name>
		<ordernumber>'.$radek['id'].'</ordernumber>
		<examdate>'.date('d.m.Y', strtotime($radek['zahajeni'])).'</examdate>
		<examplace>'.$radek['venue'].'</examplace>
		<evidencetype>0</evidencetype>
		<form>Individuální studijní plán</form>
		<isp></isp>
		<ext></ext>
		<teachname>Základní</teachname>
	</carschool>';
/*HLAVICKA KONEC*/	


/*PARTICIPANTS*/
	$XMLparticipants='<participants>';
	
 
	$sql='	SELECT 
			GUID, 
			UID, 
			prijmeni, 
			jmeno, 
			narozen, 
			adresa1, 
			adresa2, 
			cpopisne,
			psc, 
			rp  
			FROM matrika 
			WHERE folderID="'.$_GET['hlasenka'].'"';

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
			$narozen=date('d.m.Y', strtotime($radek['narozen']));
			$adresa1=$radek['adresa1'];
			$adresa2=$radek['adresa2'];
			$cpopisne=$radek['cpopisne'];
			$psc=$radek['psc'];
			$rp=$radek['rp'];
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
							<note></note>
							<trainingrange></trainingrange>
							<drivinglicence></drivinglicence>
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