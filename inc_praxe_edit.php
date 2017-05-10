<?


	$osnovaZK=osnovaZK();
	
/*SELECT Z MATRIKY*/
	$sql='	SELECT
			matrika.*, IFNULL(matrika.ukoncen,"") AS ukoncen,
			SUM(platba) AS zaloha
			FROM matrika JOIN pokladna_zalohy
			ON matrika.GUID=pokladna_zalohy.GUID
			WHERE matrika.GUID="'.$_GET['GUID'].'"';

	$result = mysqli_query ($db_connect, $sql);

	$radek = mysqli_fetch_array ($result);
	$UID=$radek['UID'];
	$fullname=strtoupper_all($radek['prijmeni']).' '.$radek['jmeno'];
	$narozen=date('d.m.Y',strtotime($radek['narozen']));
	if ($radek['ukoncen']<>'') $ukoncen=date('d.m.Y',strtotime($radek['ukoncen']));
	else $ukoncen=$radek['ukoncen'];
	$cena=$radek['cena'];
	$zaloha=$radek['zaloha'];

	

	$skupina=skupina_kod('matrika',$_GET['GUID']);
	$safedate=date('d.m.Y', mktime(0, 0, 0, date('m',strtotime($radek['narozen'])), date('d',strtotime($radek['narozen'])), date('Y',strtotime($radek['narozen']))+$osnovaZK[$skupina]['vek']));
	
?>


<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Evidence žáků | Třídní kniha - praxe </small><br />Záznam výcviku</h1>
	</div>
	<table id="<? echo $_GET['GUID']?>" class="table">
        <tr>
        	<th class="info">Ev.č.</th>
         	<th class="info">Příjmení a jméno</th>
          	<th class="info">Výcvik</th>
            <th class="info hidden-xs">Narozen</th>
            <th class="info">Bezp. datum</th>
            <th class="bg-warning hidden-xs">Plánované ukončení</th>
        </tr>
        <tr>
        	<td class="<? echo switchstatus($radek['status'])?>"><? echo $UID?></td>
            <td class="<? echo switchstatus($radek['status'])?>"><a href="?menu=karta_zaka&GUID=<?=$radek['GUID']?>&source=matrika"><strong><? echo $fullname?></strong></a></td>
            <td><? echo $skupina?></td>
            <td class="hidden-xs"><? echo $narozen?></td>
            <td><? echo $safedate?><input id="safedate" type="hidden" value="<? echo date('Y-m-d', strtotime($safedate))?>" /></td>
            <td class="hidden-xs">
            	<? if ($zaloha<$cena):?><span class="glyphicon glyphicon-alert"></span>&nbsp;Nelze ukončit výcvik - neuhrazené kurzovné <? echo $cena-$zaloha?>.<? endif?>
                <? if ($zaloha>=$cena):?><div class="col-sm-6"><input id="closedate_LG" class="closedate form-control jqdatepicker" type="text" value="<? echo $ukoncen?>" /></div><button class="button_closedate btn btn-default disabled" value="LG"><span class="glyphicon glyphicon-ok"></span></button><? endif?>
            </td>
            
        </tr>
        <tr class="visible-xs bg-warning"><th colspan="4">Plánované ukončení</th></tr>
        <tr class="visible-xs">
	        <? if ($zaloha<$cena):?><td colspan="4"><span class="glyphicon glyphicon-alert"></span>&nbsp;Nelze ukončit výcvik - neuhrazené kurzovné <? echo $cena-$zaloha?>.</td><? endif?>
          	<? if ($zaloha>=$cena):?>
	            <td colspan="3"><input id="closedate_XS" class="closedate form-control jqdatepicker" type="text" value="<? echo $ukoncen?>" /></td>
	        	<td><button class="button_closedate btn btn-default disabled" value="XS"><span class="glyphicon glyphicon-ok"></span></button></td>
            <? endif?>
		</tr>
	</table>
    
    
    <!--VYPIS HODIN-->
    <div id="output">
	    <? include 'ajax/ajax_praxe_table.php'?>
	</div>

</div>