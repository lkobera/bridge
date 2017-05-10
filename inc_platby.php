<?
	session_start();
	
	include_once 'inc_db_connect.php';
	include_once 'inc_library.php';

	/*SELECT Z MATRIKY*/	
	$sql='		SELECT';
	$sql=$sql.'	GUID, UID, jmeno, prijmeni, narozen, cena';
	$sql=$sql.'	FROM matrika';
	$sql=$sql.'	WHERE vyuctovan="0" AND loc="'.$_SESSION['loc'].'"';
	$result=mysqli_query ($db_connect, $sql);
	while ($radek=mysqli_fetch_array($result)) {
		$arrayPlatce[$radek['GUID']]['zdroj']="matrika";
		$arrayPlatce[$radek['GUID']]['UID']=$radek['UID'];
		$arrayPlatce[$radek['GUID']]['prijmeni']=$radek['prijmeni'];
		$arrayPlatce[$radek['GUID']]['jmeno']=$radek['jmeno'];
		$arrayPlatce[$radek['GUID']]['narozen']=date('d.m.Y', strtotime($radek['narozen']));
		$arrayPlatce[$radek['GUID']]['cena']=$radek['cena'];
	}
	
	/*SELECT Z NOVYCH*/
	$sql='		SELECT';
	$sql=$sql.'	GUID, jmeno, prijmeni, narozen';
	$sql=$sql.'	FROM registrace';
	$sql=$sql.'	WHERE loc="'.$_SESSION['loc'].'"';
	$result=mysqli_query ($db_connect, $sql);
	while ($radek=mysqli_fetch_array($result)) {
		$arrayPlatce[$radek['GUID']]['zdroj']="registrace";
		$arrayPlatce[$radek['GUID']]['UID']="-";
		$arrayPlatce[$radek['GUID']]['prijmeni']=$radek['prijmeni'];
		$arrayPlatce[$radek['GUID']]['jmeno']=$radek['jmeno'];
		$arrayPlatce[$radek['GUID']]['narozen']=date('d.m.Y', strtotime($radek['narozen']));
	}
	
?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Evidence žáků</small><br />Pokladna</h1>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if ($arrayUser[$_COOKIE['userID']]['pravo-pokladna']==1);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>        
	</div>
    
	<div class="well">
	    <div class="ui-widget">
        	<form action="" method="get">
				<label>Začni psát jméno: </label>
                <input type="hidden" name="menu" value="platby_detail" />
				<select id="combobox" name="GUID">
					<option value="">Select one...</option>
            	    <? foreach ($arrayPlatce as $GUID=>$platce):?>
	            	    <option value="<? echo $GUID?>"><? echo strtoupper_all($platce['prijmeni']).' '.$platce['jmeno']?></option>
	                <? endforeach?>
				</select>
        	    <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-search"></span>&nbsp;Detail</button>
                <a href="?menu=platby&show=all" class="btn btn-info"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Zobrazit vše</a>
			</form>
            
		</div>
	</div>

    <!--vypis vsech aktivnich platcu-->
    <? if ($_GET['show']=='all'):?>
		<table class="table table-responsive table-bordered table-condensed table-hover">
			<tr class="info">
            	<th>Ev. č.</th>
                <th>Příjmení a jméno</th>
                <th>Narozen</th>
                <th>Kurzovné</th>
                <th>Zaplaceno</th>
                <th></th>
            </tr>
            <? foreach ($arrayPlatce as $GUID=>$platce):?>
            	<? 
					$sql='		SELECT';
					$sql=$sql.'	SUM(platba) as zaloha';
					$sql=$sql.'	FROM pokladna_zalohy';
					$sql=$sql.'	WHERE GUID="'.$GUID.'"';
					$result=mysqli_query ($db_connect, $sql);
					$radek=mysqli_fetch_array($result);
				?>
            
            
            
            
            
                <tr>
                    <td><? echo $platce['UID']?></td>
                    <td><? echo strtoupper_all($platce['prijmeni']).' '.$platce['jmeno']?></td>
                    <td><? echo $platce['narozen']?></td>
                    <td><? echo $platce['cena']?></td>
                    <td><? echo $radek['zaloha']?></td>
                    <td>
                        <a href="?menu=platby_detail&zdroj=<? echo $arrayPlatce[$GUID]['zdroj']?>&GUID=<? echo $GUID?>" class="btn btn-info"><span class="glyphicon glyphicon-search"></span>&nbsp;Detail</a>
					</td>
                </tr>
            <? endforeach?>
		</table>
    <? endif?>

    
</div>   