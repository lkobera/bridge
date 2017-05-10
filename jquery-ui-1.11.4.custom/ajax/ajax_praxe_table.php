<?
	session_start();
	include_once $_GET['inc_path'].'inc_db_connect.php';
	include_once $_GET['inc_path'].'inc_library.php';
	
	$skupina=skupina_kod('matrika',$_GET['GUID']);
	$osnova=osnova();
	$jizdy=jizdy($_GET['GUID'],$skupina);
	$arrayUser=arrayUser();
	
	$sql='	SELECT
			ucitel_A, ucitel_B FROM matrika
			WHERE GUID="'.$_GET['GUID'].'"';
			
	$result = mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);

	

?>

<script>
	$(function() {
    	$(".jqdatepicker").datepicker();
  	});
</script>

<? foreach ($osnova[$skupina] as $key=>$zakladni_vycvik):/*zde je hlavička každé skupiny*/?>

	<div class="col-sm-4">
		<table id="<? echo $key?>" class="table table-bordered">
			<? /*SELECT Z TK_PJ*/
                $sql=	  'SELECT';
                $sql=$sql.' datum, ucitel, tk_pj.skupina';
                $sql=$sql.' FROM tk_pj';
                $sql=$sql.' WHERE GUID="'.$_GET['GUID'].'"';
				$sql=$sql.' AND skupina="'.$key.'"';
                $result = mysqli_query ($db_connect, $sql);
				
                $remaining_lessons=$osnova[$skupina][$key]-$odjeto[$key]+$kondice[$key];
            ?>
            <!--hlavicka-->
            <tr class="warning">
            	<th><h4>Výcvik <? echo $key?></h4></th>
                <th><span style="text-align:right"><h4><small>odemčeno</small>&nbsp;<span class="label label-default"><? echo $jizdy['predplaceno_zaklad']+$jizdy['kondice_'.$key]?></span></h4></span></th><!--kolik hodin je mozne planovat-->
            </tr>
            <tr class="info">
            	<th>Učitel</th>
                <th></th>
            </tr>
            <tr>
            	<td colspan="2">
		            <div class="progress">
	    				<div class="progress-bar progress-bar-info" role="progressbar" style="width:<? echo procenta($jizdy['odjeto_'.$key],($osnova[$skupina][$key]+$jizdy['kondice_'.$key]))?>%">
	                    	<span><? echo $jizdy['odjeto_'.$key].'/'.($osnova[$skupina][$key]+$jizdy['kondice_'.$key])?></span>
	                  	</div>
    	            </div>
				</td>
            </tr>
			<tr class="info">
            	<td>Základní rozsah&nbsp;<span class="badge"><? echo $osnova[$skupina][$key]?></span></td>
                <td>Doplňkový výcvik&nbsp;<span class="badge"><? echo $jizdy['kondice_'.$key] ?></span></td>
			</tr>
            
            <? $count=0?>
            <? while ($lesson=mysqli_fetch_array($result)): /*vypis hodin*/?>
                <? $count++?>
                <? if($count%2<>0) echo '<tr>'?>
                    <td><label><? echo $count?></label><input type="text" class="form-control" value="<? echo date('d.m.Y', strtotime($lesson['datum']))?>" disabled /></td>
				<? if($count%2==0) echo '</tr>'?>
                
            <? endwhile?>
    
            <? if ($jizdy['predplaceno_zaklad']+$jizdy['kondice_'.$key]>0):
                $count++;?>
                <tr>
                    <td colspan="2">
                        <label><? echo $count?></label><br /><input id="datum_<? echo $key?>" type="text" class="form-control jqdatepicker" />&nbsp;<button value="<? echo $_GET['GUID']?>" class="button_praxe_insert btn btn-info"><span class="glyphicon glyphicon-plus"></span></button>
                    </td>
                </tr>
            <? endif?>
 		</table>
	</div>
<? endforeach?>