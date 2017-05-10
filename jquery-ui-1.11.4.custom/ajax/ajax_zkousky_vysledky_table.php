<?
	session_start();

	
	include_once $_GET['inc_path'].'inc_db_connect.php';
	include_once $_GET['inc_path'].'inc_library.php';
	
	/**SELECT ZAKU ZE ZKOUSEK*/
	$sql='	SELECT
			zkousky_zaci.*,
			IFNULL (zkousky_zaci.absence,"") AS absence,
			IFNULL (zkousky_zaci.resultPPV,"") AS resultPPV,
			IFNULL (zkousky_zaci.resultPJ_A,"") AS PJ_A,
			IFNULL (zkousky_zaci.PJ_B,"") AS PJ_B,
			matrika.UID, matrika.jmeno, matrika.prijmeni
			FROM zkousky_zaci LEFT JOIN matrika
			ON zkousky_zaci.GUID=matrika.GUID
			WHERE zkousky_zaci.ZKid="'.$_GET['ZKid'].'"';
	$result2=mysqli_query ($db_connect, $sql); 
	
?>

<table class="table table-responsive table-bordered table-condensed table-hover">
    <tr>
        <th class="bg-info"></th>
        <th class="bg-info">Ev.č.</th>
        <th class="bg-info">Jméno</th>
        <th class="bg-info">Skupina</th>
        <th class="bg-info">Učitel</th>
        <th class="bg-warning"></th>
        <th class="bg-warning">PPV</th>
        <th class="bg-warning">PJ&nbsp;A</th>
        <th class="bg-warning">PJ&nbsp;B</th>
        
        <th class="bg-warning"></th>
    </tr>
    <? while ($radek2=mysqli_fetch_array($result2)):?>
         <tr id="<? echo $radek2['ID']?>"><!--ID neni UID zaka ale unikatni ID zaznamu ve zkousky_zaci-->
            <td>
                <? if ($radek2['locked']==0):?><button class="zkousky_vysledky_delete btn btn-warning"><span class="glyphicon glyphicon-trash"></span></button><? endif?>
            </td>
            <td><? echo $radek2['UID']?></td>
            <td><? echo strtoupper_all($radek2['prijmeni']).' '.$radek2['jmeno']?></td>
            <td><? echo skupina_kod('matrika', $radek2['GUID'])?></td>
        	<td><? echo $arrayUser[$radek2['ucitel_A']]['inicial'].'&nbsp;'.$arrayUser[$radek2['ucitel_B']]['inicial']?></td>
           
           <!--vysledky-->           
            <td><button id="absence_<? echo $radek2['ID']?>" class="zk_absence btn btn-warning" value="<? echo $radek2['absence']?>"><span class="glyphicon glyphicon-ban-circle"></span>&nbsp;Absence</button></td>
            <td><button id="PPV_<? echo $radek2['ID']?>" class="zk_switch btn btn-info" value="<? echo $radek2['resultPPV']?>"><span class="glyphicon glyphicon-unchecked"></span></button></td>
            <td><button id="PJ_A_<? echo $radek2['ID']?>" class="zk_switch btn btn-info" value="<? echo $radek2['resultPJ_A']?>"><span class="glyphicon glyphicon-unchecked"></span></button></td>
            <td><button id="PJ_B_<? echo $radek2['ID']?>" class="zk_switch btn btn-info" value="<? echo $radek2['resultPJ_B']?>"><span class="glyphicon glyphicon-unchecked"></span></button></td>
            
            
			<td><button id="zk_save_<? echo $radek2['ID']?>" class="zk_save btn btn-default disabled" value=""><span class="glyphicon glyphicon-save"></span></button></td>            
           

        </tr>
    <? endwhile?>
</table>
