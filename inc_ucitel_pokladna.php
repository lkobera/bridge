<?
	$ucetni_osnova=ucetni_osnova();
	/*nastaveni defaultniho pohledu podle prav uzivatele*/
	if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1) {
			if (isset($_GET['ucitel'])) $_SESSION['ucitel']=$_GET['ucitel'];
	}
		
	else $_SESSION['ucitel']=$_COOKIE['userID'];	

	$sql='	SELECT SUM(pokladna.prijmy) AS prijmy, SUM(pokladna.vydaje) AS vydaje
			FROM 
			(
				SELECT
				IFNULL(SUM(platba),0) AS prijmy,
				0 AS vydaje
				FROM pokladna_zalohy 
				WHERE pokladna_zalohy.autor="'.$_SESSION['ucitel'].'"
	
				
				UNION
				
				SELECT
				0 AS prijmy,
				IFNULL (SUM(castka),0) AS vydaje
				FROM pokladna_vydaje
				WHERE pokladna_vydaje.uzivatel_ucet="'.$_SESSION['ucitel'].'"
			) AS pokladna
			
			
			';
			
		
			
			

	$result=mysqli_query($db_connect,$sql);
	$sum=mysqli_fetch_array($result);


	/***************SELECT POHYBU V POKLADNE*****************/
	$sql='	SELECT pokladna.* FROM 
			(
			SELECT
				GUID, datum,platba AS castka, autor, cenikID AS ucet, "" AS popis
				FROM pokladna_zalohy
				WHERE autor="'.$_SESSION['ucitel'].'"
				AND datum>="'.date('Y-m-d', strtotime($_GET['pokladna_od'])).'"
				AND datum<="'.date('Y-m-d', strtotime($_GET['pokladna_do'])).'"		
			
			UNION
			SELECT
				0 AS GUID,datum,castka,autor,ucet,popis
				FROM pokladna_vydaje
				WHERE uzivatel_ucet="'.$_SESSION['ucitel'].'"
				AND datum>="'.date('Y-m-d', strtotime($_GET['pokladna_od'])).'"
				AND datum<="'.date('Y-m-d', strtotime($_GET['pokladna_do'])).'"
			) AS pokladna
			ORDER BY datum ASC
		';
	$result=mysqli_query($db_connect,$sql);

?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Nástroje</small><br />Moje pokladna</h1>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>        
	</div>
	<? if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1):?>
	    <div class="col-md-2 col-sm-4">
		    <select class="form-control" id="pokladna_select_ucitel">
				<?php option_ucitel($_SESSION['ucitel']);?>
			</select>
	        <br>
		</div><br clear="all">
	<? endif?>
    
   
<!----------------------POKLADNA STAV------------------------------->
	<? if($_SESSION['ucitel']>0):?>
	<div class="col-md-6">
    	<table class="table table-condensed table-bordered table-responsive">
        	<tr class="bg-info">
            	<td><h4>Stav zálohové pokladny</h4></td>
                <td><h4><? echo $sum['prijmy']+$sum['vydaje']?></h4></td><!--prijmy a vydaje se scitaji, protoze vydaje uz maji v DB zaporne znamenko-->
            </tr>
        </table>
	</div>
	<br clear="all">
    <? endif?>
    
<!--VYDAJE-->
	<? if($_SESSION['ucitel']>0):?>
	<div class="col-md-6">
		
    	<table class="table table-condensed table-bordered table-responsive">
        	<tr class="bg-warning"><td colspan="4"><h5>Výdaje</h5></td></tr>
            <tr>
            	<td>Datum</td><td><input id="ucitel_vydej_datum" type="text" class="jqdatepicker form-control" value="<? echo date('d.m.Y')?>"></td>
                <td></td>
            </tr>
            <tr>
            	<td>Popis</td>
                <td>
                	<select id="ucitel_vydej_ucet" class="form-control">
	                    <option value="-">---</option>
                    	<? $polozky=0?>
                        <? foreach ($ucetni_osnova AS $key=>$ucet):?>
                        	<? if (
								(($ucet['pokladna']==0) AND ($ucet['kontraktor']==0) AND ($ucet['supervisor']<>1)) /*pokud jsou vsechna prava k osnove defaultni 0 tak ukazuj vsem*/

								OR ( /*pokud pravo k osove neni v defaultnim stavu, tak porovnavej prava*/
										(
											(($ucet['pokladna']==1) AND ($arrayUser[$_SESSION['ucitel']]['pravo-pokladna']==1) AND ($ucet['supervisor']<>1))
											OR (($ucet['pokladna']==0) AND ($arrayUser[$_SESSION['ucitel']]['pravo-pokladna']<=1) AND ($ucet['supervisor']<>1))
											OR (($ucet['pokladna']==(-1)) AND ($arrayUser[$_SESSION['ucitel']]['pravo-pokladna']<>1) AND ($ucet['supervisor']<>1))
										)
										AND
										(
											(($ucet['kontraktor']==1) AND ($arrayUser[$_SESSION['ucitel']]['pravo-kontraktor']==1) AND ($ucet['supervisor']<>1))
											OR (($ucet['kontraktor']==0) AND ($arrayUser[$_SESSION['ucitel']]['pravo-kontraktor']<=1) AND ($ucet['supervisor']<>1))
											OR (($ucet['kontraktor']==(-1)) AND ($arrayUser[$_SESSION['ucitel']]['pravo-kontraktor']<>1) AND ($ucet['supervisor']<>1))										
										)
									)
								):
							?>
                            	<? $polozky++?>
                            	<option value="<? echo $key?>"><? echo $ucet['popis']?></option>
							<? endif?>
                            <? if (($ucet['supervisor']==1) AND ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)):?>
	                            <? $polozky++?>
                            	<option value="<? echo $key?>"><? echo $ucet['popis']?></option>
							<? endif?>
                        <? endforeach?>
                    </select>
                </td>
                <td></td>
            </tr>
            <tr id="poznamka">
            	<td>Poznámka</td>
                <td><input id="ucitel_vydej_popis" type="text" class="form-control"></td>
                <td></td>
            </tr>
            <tr id="zaloha_komu" class="hide">
            	<td>Komu</td>
                <td>
                	<select class="form-control" id="ucitel_zaloha_komu">
						<?php option_ucitel($_SESSION['ucitel']);?>
					</select>
                </td>
            </tr>
            <tr>
            	<td>Částka</td>
            	<td><input id="ucitel_vydej_castka" type="text" class="form-control" <? if ($polozky==0) echo 'disabled'?>></td>
                <td><button id="button_pokladna_vydej" class="btn btn-default disabled"><span class="glyphicon glyphicon-ok"></span></button></td>
            </tr>
        </table>
    </div>
    <? endif?>
    <input id="autor" type="hidden" value="<? echo $_COOKIE['userID']?>">
    <input id="uzivatel_ucet" type="hidden" value="<? echo $_SESSION['ucitel']?>">
    
    
    
<!---------------------------POKLADNA POHYBY------------------------------>   
	<? if($_SESSION['ucitel']>0):?> 
 	<div class="col-md-6">
        <table class="table table-condensed table-bordered table-responsive">
        	<tr class="active"><td colspan="3"><h5>Pohyby v pokladně</h5></td></tr>
			<tr class="active">
            	<th>Od</th>
                <th>Do</th>
                <td></td>
            </tr>
            <tr>
            	<form action="" method="get">
                	<input type="hidden" name="menu" value="ucitel_pokladna">
                    <td>
                        <input name="pokladna_od" type="text" class="jqdatepicker form-control" value="<? echo $_GET['pokladna_od']?>">
                    </td>
                    <td>
                        <input name="pokladna_do" type="text" class="jqdatepicker form-control" value="<? echo $_GET['pokladna_do']?>">
                    </td>
                    <td><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-filter"></span></button></td>
                </form>
            </tr>
            <tr class="bg-info">
                <th>Datum</th>
                <th>Popis</th>
                <th>Částka</th>
            </tr>
            <? while ($radek=mysqli_fetch_array($result)):?>
            	<? 	$sql_z='SELECT UID, jmeno, prijmeni FROM matrika WHERE GUID="'.$radek['GUID'].'"';
					$result_z=mysqli_query($db_connect, $sql_z);
					$radek_z=mysqli_fetch_array($result_z);
				?>
                <tr>
                    <td><? echo date('d.m.Y', strtotime($radek['datum']))?></td>
                    <td>
						<? 	if ($radek['GUID']=="0") echo $radek['ucet'].' '.$ucetni_osnova[$radek['ucet']]['popis'].' '.$radek['popis'];
							else echo $radek_z['UID'].' '.strtoupper_all($radek_z['prijmeni']).' '.$radek_z['jmeno'];
						?>
                    </td>
                    <td><? echo $radek['castka']?></td>
                </tr>
            <? endwhile?>
        </table>
   	</div>
    <? endif?>

    
</div>

