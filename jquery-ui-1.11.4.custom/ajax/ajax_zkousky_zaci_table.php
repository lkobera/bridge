<?
	session_start();

	
	include_once $_GET['inc_path'].'inc_db_connect.php';
	include_once $_GET['inc_path'].'inc_library.php';
	
	$arrayUID=explode('|','1605|0|0|1'); /*prevezme data ve formatu UID|PPV|PJ_A|PJ_B*/

	
	/*SELECT -JE LOCKED + KAPACITA?*/
	$sql='		SELECT
				zkousky_terminy.locked, zkousky_terminy.PPV, zkousky_terminy.PJ_A, zkousky_terminy.PJ_B,
				SUM(zkousky_zaci.PPV) as usedPPV, SUM(zkousky_zaci.PJ_A) as usedPJ_A, SUM(zkousky_zaci.PJ_B) as usedPJ_B
				FROM zkousky_terminy JOIN zkousky_zaci
				ON zkousky_terminy.id=zkousky_zaci.ZKid
				WHERE  zkousky_terminy.id="'.$_GET['ZKid'].'"';
	
	
	$result2=mysqli_query ($db_connect, $sql);
	$radek2=mysqli_fetch_array($result2);
	$locked=$radek2['locked'];
	$PPV=$radek2['PPV'];
	$PJ_A=$radek2['PJ_A'];
	$PJ_B=$radek2['PJ_B'];
	$usedPPV=$radek2['usedPPV']; if ($usedPPV==NULL) $usedPPV=0;	
	$usedPJ_A=$radek2['usedPJ_A']; if ($usedPJ_A==NULL) $usedPJ_A=0;
	$usedPJ_B=$radek2['usedPJ_B']; if ($usedPJ_B==NULL) $usedPJ_B=0;
	
	
	/*SELECT ZAKU DO VYPISU*/
	$sql='	 	SELECT
				zkousky_zaci.*, matrika.jmeno, matrika.prijmeni
				FROM zkousky_zaci
				JOIN matrika
				ON zkousky_zaci.GUID=matrika.GUID
				WHERE zkousky_zaci.ZKid="'.$_GET['ZKid'].'"';
	$result2=mysqli_query ($db_connect, $sql);
	
	
	/*SELECT ZAKU READY*/
	$sql='	SELECT
			* FROM matrika
			WHERE status>3 AND status<6
			AND loc="'.$_SESSION['loc'].'"
			AND ukoncen<"'.date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))).'"
			ORDER BY prijmeni';
	$result3=mysqli_query ($db_connect, $sql);

	
	
?>

<table class="table table-responsive table-bordered table-condensed table-hover">
    <tr>
        <th class="bg-info"><? echo $_GET['ZKid']?></th>
        <th class="bg-info">Ev.č.</th>
        <th class="bg-info">Jméno</th>
        <th class="bg-info">Skupina</th>
        <th class="bg-info">Učitel</th>
        <th class="bg-warning">PPV
        	<span class="badge 
				<? if ($PPV<$usedPPV) echo ' statusRed'?>
                <? if ($PPV==$usedPPV) echo ' statusOrange'?>
                <? if ($PPV>$usedPPV) echo ' statusGreen'?>
			"><? echo $usedPPV.'/'.$PPV?></span>
        </th>
        <th class="bg-warning">PJ A
        	<span class="badge 
				<? if ($PJ_A<$usedPJ_A) echo ' statusRed'?>
                <? if ($PJ_A==$usedPJ_A) echo ' statusOrange'?>
                <? if ($PJ_A>$usedPJ_A) echo ' statusGreen'?>
			"><? echo $usedPJ_A.'/'.$PJ_A?></span>
        </th>
        <th class="bg-warning">PJ B
        	<span class="badge 
				<? if ($PJ_B<$usedPJ_B) echo ' statusRed'?>
                <? if ($PJ_B==$usedPJ_B) echo ' statusOrange'?>
                <? if ($PJ_B>$usedPJ_B) echo ' statusGreen'?>
			"><? echo $usedPJ_B.'/'.$PJ_B?></span>  
        </th>
    </tr>

<!--radek v listu studentu ke zkousce-->
<? while($radek2=mysqli_fetch_array($result2)): ?>
    <tr id="<? echo $radek2['ID']?>"><!--ID neni UID zaka ale unikatni ID zaznamu ve zkousky_zaci-->
        <td>
        	<? if ($locked==0):?><button class="zkousky_zaci_delete btn btn-warning"><span class="glyphicon glyphicon-trash"></span></button><? endif?>
		</td>
        <td><? echo $radek2['UID']?></td>
        <td><? echo strtoupper_all($radek2['prijmeni']).'&nbsp;'.$radek2['jmeno']?></td>
        <td><? echo skupina_kod('matrika', $radek2['GUID'])?></td>
        <td><? echo $arrayUser[$radek2['ucitel_A']]['inicial'].'&nbsp;'.$arrayUser[$radek2['ucitel_B']]['inicial']?></td>
        <td><input id="PPV" class="ZKinput form-control" type="checkbox" <? if ($radek2['PPV']==1) echo 'checked'?> /></td>
        <td><input id="PJ_A" class="ZKinput form-control" type="checkbox" <? if ($radek2['PJ_A']==1) echo 'checked'?> /></td>
        <td><input id="PJ_B" class="ZKinput form-control" type="checkbox" <? if ($radek2['PJ_B']==1) echo 'checked'?> /></td>
    </tr>
<? endwhile ?>

<!--PRIHLASENI ZAKA DO ZKOUSKY-->
<? if ($locked==0):?>
	<tr>
    	<td><button class="zkousky_zaci_insert btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>
        <td colspan=3>
        	<select name="<? echo $_GET['ZKid']?>" class="form-control">
            	
            	<? while ($radek3=mysqli_fetch_array($result3)):?>
	                <? 
						$skupina=skupina_kod('matrika', $radek3['GUID']);
	                   	if (($radek3['PPV']==0) AND ($_SESSION['osnovaZK'][$skupina]['PPV']==1)) $PPV=1; else $PPV=0;
						if (($radek3['PJ_A']==0) AND ($_SESSION['osnovaZK'][$skupina]['A']==1)) $PJ_A=1; else $PJ_A=0;
						if (($radek3['PJ_B']==0) AND ($_SESSION['osnovaZK'][$skupina]['B']==1)) $PJ_B=1; else $PJ_B=0;
					?>
              
                
                
                	<option value="<? echo $radek3['GUID'].'|'.$PPV.'|'.$PJ_A.'|'.$PJ_B?>">
						<? 
							$skupina=skupina_kod('matrika',$radek3['GUID']);
							echo strtoupper_all($radek3['prijmeni']).' '.$radek3['jmeno'].'&nbsp; ['.$skupina.']&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        	if ($PPV==1) echo '|PPV|&nbsp';
							if ($PJ_A==1) echo '|A|&nbsp;';
							if ($PJ_B==1) echo '|B|';
						?>
                    </option>
                <? endwhile?>
            </select>
        </td>
    </tr>
<? endif?>
	

</table>

<script src="../js/myjquery.js"></script>
