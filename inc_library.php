<?php

function strtoupper_all($string) {
 $string=strtoupper($string);
 $string=strtoupper_cz($string);
 return $string;

}


function strtoupper_cz($string) {
	return strtr($string, array(
      "ě" => "Ě",
      "š" => "Š",
      "č" => "Č",
      "ř" => "Ř",
      "ž" => "Ž",
	  "ý" => "Ý",
	  "á" => "Á",
	  "í" => "Í",
	  "é" => "É",
	  "ú" => "Ú",
	  "ů" => "Ů",
	  "ť" => "Ť",
	  "ó" => "Ó",
	  "ď" => "Ď",
	  "ň" => "Ň"
    )); 
}

function arrayUser() {
	$sql='	SELECT
			a.*, b.* 
			FROM uzivatel a JOIN uzivatel_prava b
			ON a.UZid=b.UZid
			ORDER BY aktivni DESC, a.UZid ASC';

	
	$result=mysqli_query ($GLOBALS['db_connect'], $sql);

	while ($radek=mysqli_fetch_array ($result)) {
		$user[$radek['UZid']]['UZid']=$radek['UZid'];
		$user[$radek['UZid']]['jmeno']=$radek['jmeno'];
		$user[$radek['UZid']]['inicial']=$radek['inicial'];
		$user[$radek['UZid']]['aktivni']=$radek['aktivni'];
		$user[$radek['UZid']]['email']=$radek['mail'];
		
		$user[$radek['UZid']]['pravo-supervisor']=$radek['supervisor'];
		$user[$radek['UZid']]['pravo-admin']=$radek['admin'];
		$user[$radek['UZid']]['pravo-pokladna']=$radek['pokladna'];
		$user[$radek['UZid']]['pravo-instr_A']=$radek['instr_A'];
		$user[$radek['UZid']]['pravo-instr_B']=$radek['instr_B'];
		$user[$radek['UZid']]['pravo-liberec']=$radek['liberec'];
		$user[$radek['UZid']]['pravo-praha']=$radek['praha'];
		$user[$radek['UZid']]['pravo-jablonec']=$radek['jablonec'];
		$user[$radek['UZid']]['pravo-kontraktor']=$radek['kontraktor'];


		
	}
	return $user;
}


/*select ucitelu*/
function option_ucitel($UZid) {

	echo '<option value="0">---</option>';
	foreach ($GLOBALS['arrayUser'] as $user) {
		if ((($user['pravo-instr_A']>0) OR ($user['pravo-instr_B']>0)) AND $user['aktivni']==1) {
			echo '<option';
			if ($UZid==$user['UZid']) echo ' selected';
			echo ' value="'.$user['UZid'].'">';
			echo $user['jmeno'];
			echo '</option>';		
		}
	}
}


/*select tarifu kondicnich jizd do pole*/
function cenik($loc) {

	$sql='SELECT * FROM cenik WHERE loc="'.$loc.'"';
	$result= mysqli_query ($GLOBALS['db_connect'], $sql);
	
	while ($radek=mysqli_fetch_array($result)) {
		$cenik[$radek['ID']]['kod']=$radek['kod'];
		$cenik[$radek['ID']]['skupina']=$radek['skupina'];
		$cenik[$radek['ID']]['polozka']=$radek['polozka'];
		$cenik[$radek['ID']]['cena']=$radek['cena'];
	}
	return $cenik;
}
	
/*pole čisleniků účtů učetní osnovy**/
function ucetni_osnova() {
	$sql='SELECT * FROM pokladna_osnova ORDER BY ucetID ASC';
	$result= mysqli_query ($GLOBALS['db_connect'], $sql);
	while ($radek=mysqli_fetch_array($result)) {
		$osnova[$radek['ucetID']]['popis']=$radek['popis'];
		$osnova[$radek['ucetID']]['pokladna']=$radek['pokladnik'];
		$osnova[$radek['ucetID']]['kontraktor']=$radek['kontraktor'];
		$osnova[$radek['ucetID']]['supervisor']=$radek['supervisor'];
		}
	return $osnova;
}


function skupina_kod ($from, $GUID) {

	$sql='SELECT request, maAM, maA1, maA2, maA, maB, maC, maD, maE, maT, chceAM, chceA1, chceA2, chceA, chceB FROM '.$from.' WHERE GUID="'.$GUID.'"';
	$result = mysqli_query ($GLOBALS['db_connect'], $sql);
	$radek = mysqli_fetch_array ($result);

	$request=$radek['request'];
	
	$maAM=$radek['maAM'];
	$maA1=$radek['maA1'];
	$maA2=$radek['maA2'];
	$maA=$radek['maA'];
	$maB=$radek['maB'];
	$maC=$radek['maC'];
	$maD=$radek['maD'];
	$maE=$radek['maE'];
	$maT=$radek['maT'];
		
	$chceAM=$radek['chceAM'];
	$chceA1=$radek['chceA1'];
	$chceA2=$radek['chceA2'];
	$chceA=$radek['chceA'];
	$chceB=$radek['chceB'];
			
	$skupina='';
	if (($maAM==true) or ($maA1==true) or ($maA2==true) or ($maA==true) or ($maB==true) or ($maC==true) or ($maD==true) or ($maE==true) or ($maT==true)) {
		$skupina='R';
		if ($maAM==true) $skupina=$skupina.'AM';
		if ($maA1==true) $skupina=$skupina.'A1';
		if ($maA2==true) $skupina=$skupina.'A2';
		if ($maA==true) $skupina=$skupina.'A';
		if ($maB==true) $skupina=$skupina.'B';
		if ($maC==true) $skupina=$skupina.'C';
		if ($maD==true) $skupina=$skupina.'D';
		if ($maE==true) $skupina=$skupina.'E';
		if ($maT==true) $skupina=$skupina.'T';
		$skupina=$skupina.'-';				
	}
	if ($chceAM==true) $skupina=$skupina.'AM';
	if ($chceA1==true) $skupina=$skupina.'A1';
	if ($chceA2==true) $skupina=$skupina.'A2';
	if ($chceA==true) $skupina=$skupina.'A';
	if ($chceB==true) $skupina=$skupina.'B';
	
	if ($request==1) $skupina='přezk. '.$skupina;
	return ($skupina);
}


/*NASTAVI CLASS PODLE STATUSU ZAKA*/
function switchStatus($status) {
	switch ($status) {
		case "0": $statusclass='statusRed'; break; /*zaevidovan*/
		case "1": $statusclass='statusOrange'; break;/*zahajen-bez ucitele*/
		case "2": $statusclass='statusLGreen'; break;/*zahajen-s ucitelem-ceka na jizdy*/
		case "3": $statusclass='statusDGreen'; break;/*zahajen-s ucitelem-jezdi*/
		case "4": $statusclass='statusLBlue'; break;/*ukoncen-ceka na zkousku*/
		case "5": $statusclass='statusDBlue'; break;/*ukoncen-ceka na opravu*/
		case "6": $statusclass='statusGrey'; break;/*vyrazen*/
	}
	return ($statusclass);
}


/*NACTE OSNOVU VYUKY*/
function osnova() {
	$sql='SELECT';
	$sql=$sql.' * FROM osnova';
	$sql=$sql.' WHERE PJ="1"';
	$result = mysqli_query ($GLOBALS['db_connect'], $sql);
	while ($radek = mysqli_fetch_array ($result)) {
		$osnova[$radek['skupina']][$radek['subject']]=$radek['hodin'];
	}
	return $osnova;
}

function osnovaZK() {
	$sql='SELECT';
	$sql=$sql.' * FROM osnova';
	$sql=$sql.' WHERE ZK="1"';
	$result = mysqli_query ($GLOBALS['db_connect'], $sql);
	while ($radek = mysqli_fetch_array ($result)) {
		$osnova[$radek['skupina']][$radek['subject']]=$radek['hodin'];
		$osnova[$radek['skupina']]['vek']=$radek['vek'];
	}
	return $osnova;
}

/*TK PRAXE*/

/*kontrola platebni moralky - pocet hodin, ktere muze odjet podle vyse slozene zalohy na kurzovne*/
/*vraci pole odjeto A,B, kondicky A,B, predplaceno*/

function jizdy ($GUID, $skupina) { 

	if (isset($GLOBALS['osnova'][$skupina])) {
		foreach ($GLOBALS['osnova'][$skupina] as $osnova) {
			$celkemPJ=$celkemPJ+$osnova;
		}
	}
	else return ('chyba');

	
	$sql='SELECT 
			m.cena, 
			IFNULL (SUM(p.platba),0) AS zaloha, 
			IFNULL(ra.kondice,0) as kondice_A,
			IFNULL(rb.kondice,0) as kondice_B,
			ta.odjeto_A as odjeto_A,
			tb.odjeto_B as odjeto_B
			

			FROM matrika m
			JOIN pokladna_zalohy p ON m.GUID=p.GUID
			JOIN (SELECT COUNT(1) AS kondice FROM request_sluzby WHERE kod="KJ" AND skupina="A" AND GUID="'.$GUID.'") AS ra
			JOIN (SELECT COUNT(1) AS kondice FROM request_sluzby WHERE kod="KJ" AND skupina="B" AND GUID="'.$GUID.'") AS rb
			JOIN (SELECT COUNT(datum) AS odjeto_A FROM tk_pj WHERE skupina="A" AND GUID="'.$GUID.'") AS ta
			JOIN (SELECT COUNT(datum) AS odjeto_B FROM tk_pj WHERE skupina="B" AND GUID="'.$GUID.'") AS tb
			
			WHERE m.GUID="'.$GUID.'" AND p.cenikID=0';
	
	$result = mysqli_query ($GLOBALS['db_connect'], $sql);
	$radek = mysqli_fetch_array ($result);
	$zaloha=$radek['zaloha']; /*zaplacena zaloha*/
	$cena=$radek['cena'];	/*cena kurzu*/
	
	/*return*/
	$res['kondice_A']=$radek['kondice_A'];/*predplacene kondice*/
	$res['kondice_B']=$radek['kondice_B'];
	if ($GLOBALS['osnova'][$skupina]['A']>0) $res['odjeto_A']=$radek['odjeto_A']; /*odjete hodiny*/
	else $res['kondice_A']=0;
	if ($GLOBALS['osnova'][$skupina]['B']>0) $res['odjeto_B']=$radek['odjeto_B'];
	else $res['kondice_B']=0;
	
	/*************pausal za teorii 2000*****************/
	/*vypocet ceny za jednu hodinu vycviku*/
	$cena_teo=2000;
	$cena_hodina=($cena-$cena_teo)/$celkemPJ;
	
	
	/*****************banka predplacenych hodin********************/
	/*soucet*/
	if (($res['kondice_A']>0) OR ($res['kondice_B']>0)) $zaloha=$cena; /*pokud ma doplaceno tak uz nepocita se zalohou ale pevnou cenou kurzu - jinak to blblo s kondickama*/
	
	if ($cena_hodina>0) $bank=floor(($zaloha-$cena_teo)/$cena_hodina);
	else $bank=$celkemPJ; 
	
 	$bank=$bank-$res['odjeto_A']-$res['odjeto_B']+$res['kondice_A']+$res['kondice_B'];
	if ($bank<0) $bank=0;
	
	/*banka A*/
	if ($bank > $GLOBALS['osnova'][$skupina]['A']-$res['odjeto_A']+$res['kondice_A']) $bankA=$GLOBALS['osnova'][$skupina]['A']-$res['odjeto_A']+$res['kondice_A'];
	else $bankA=$bank;

	
	/*banka B*/
	if ($bank > $GLOBALS['osnova'][$skupina]['B']-$res['odjeto_B']+$res['kondice_B']) $bankB=$GLOBALS['osnova'][$skupina]['B']-$res['odjeto_B']+$res['kondice_B'];
	else $bankB=$bank;

	

	$res['bank']=$bank;/*banka hodin*/
	$res['bank_A']=$bankA;
	$res['bank_B']=$bankB;
	

	/*return*/
	return ($res);
}



/*vypocet PROCENTA pro PROGRESSBAR*/

function procenta ($citatel,$jmenovatel) {
	return $citatel/($jmenovatel/100);
}


/*ZKOUSKY*/

/*adresa konani zkousky*/
function zkousky_venue ($loc) {
	$sql='	SELECT * FROM zkousky_venue WHERE loc="'.$loc.'"';
	$result = mysqli_query ($GLOBALS['db_connect'], $sql);
	while ($radek = mysqli_fetch_array ($result)) {
		echo '<option value="'.$radek['ID'].'">';
		echo $radek['adresa'];
		echo '</option>';
	}
}


/*XML formatovani skupin*/

function XMLskupinaMa ($GUID) {
	$sql='SELECT maAM, maA1, maA2, maA, maB, maC, maD, maE, maT FROM matrika WHERE GUID="'.$GUID.'"';
	$result = mysqli_query ($GLOBALS['db_connect'], $sql);
	$radek = mysqli_fetch_array ($result);

	$maAM=$radek['maAM'];
	$maA1=$radek['maA1'];
	$maA2=$radek['maA2'];
	$maA=$radek['maA'];
	$maB=$radek['maB'];
	$maC=$radek['maC'];
	$maD=$radek['maD'];
	$maE=$radek['maE'];
	$maT=$radek['maT'];
		
			
	$skupina='';
	if ($maAM==true) $skupina='AM,';
	if ($maA1==true) $skupina='A1,';
	if ($maA2==true) $skupina='A2,';
	if ($maA==true) $skupina='A,';
	
	if ($maB==true) $skupina=$skupina.'B,';
	if ($maC==true) $skupina=$skupina.'C,';
	if ($maD==true) $skupina=$skupina.'D,';
	
	if ($maE==true) $skupina=$skupina.'E,';
	if ($maT==true) $skupina=$skupina.'T,';
	$skupina= substr($skupina, 0, -1);

	return ($skupina);
}


function XMLskupinaChce ($GUID) {
	$sql='SELECT chceAM, chceA1, chceA2, chceA, chceB FROM matrika WHERE GUID="'.$GUID.'"';
	$result = mysqli_query ($GLOBALS['db_connect'], $sql);
	$radek = mysqli_fetch_array ($result);

	$chceAM=$radek['chceAM'];
	$chceA1=$radek['chceA1'];
	$chceA2=$radek['chceA2'];
	$chceA=$radek['chceA'];
	$chceB=$radek['chceB'];
			
	$skupina='';
	if ($chceAM==true) $skupina='AM,';
	if ($chceA1==true) $skupina='A1,';
	if ($chceA2==true) $skupina='A2,';
	if ($chceA==true) $skupina='A,';
	
	if ($chceB==true) $skupina=$skupina.'B,';
	$skupina= substr($skupina, 0, -1);
	
	return ($skupina);
}

function mailer($address, $subject, $message){
	require_once "class.phpmailer.php";
	$mail = new PHPMailer();
	$mail->IsSMTP();  // k odeslání e-mailu použijeme SMTP server
	$mail->Host = "smtp.gransy.com";  // zadáme adresu SMTP serveru
	$mail->SMTPAuth = true;               // nastavíme true v případě, že server vyžaduje SMTP autentizaci
	$mail->Username = "info@autoskolaeasy.cz";   // uživatelské jméno pro SMTP autentizaci
	$mail->Password = "rokba79";            // heslo pro SMTP autentizaci
	$mail->From = "info@autoskolaeasy.cz";   // adresa odesílatele skriptu
	$mail->FromName = "Autoškola Easy"; // jméno odesílatele skriptu (zobrazí se vedle adresy odesílatele)
	
	
	foreach ($address as $to) {
		$mail->AddAddress($to);  // přidáme příjemce
	}
	
	$mail->Subject = $subject;    // nastavíme předmět e-mailu
	$mail->Body = $message;  // nastavíme tělo e-mailu
	$mail->WordWrap = 50;   // je vhodné taky nastavit zalomení (po 50 znacích)
	$mail->CharSet = "UTF-8";   // nastavíme kódování, ve kterém odesíláme e-mail
	$mail->IsHTML(true);
	$mail->Send();   // odešleme e-mail
}

function journal_email($to, $subject, $body, $sender) {
	if (is_array ($to)) $to=implode(";",$to);
	$sql='INSERT INTO journal_email (`to`, subject, body, sender) VALUES ("'.$to.'","'.$subject.'","'.$body.'","'.$sender.'")';	
	mysqli_query ($GLOBALS['db_connect'], $sql);
}


?>