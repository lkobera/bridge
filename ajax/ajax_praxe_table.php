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
		$(".jqtimepicker").timepicker();
  	});
</script>

<? foreach ($osnova[$skupina] as $key=>$zakladni_vycvik):/*zde je hlavička každé skupiny*/?>

	<div class="row col-sm-4">
		<table id="<? echo $key?>" class="table table-bordered">
			<? /*SELECT Z TK_PJ*/
                $sql=	  'SELECT';
                $sql=$sql.' tk_pj.ID, tk_pj.datum, tk_pj.ucitel, tk_pj.skupina, vehicle.rz, tk_pj.cas_od, tk_pj.cas_do, tk_pj.km_od, tk_pj.km_do';
                $sql=$sql.' FROM tk_pj LEFT JOIN vehicle ON tk_pj.GUID_vehicle=vehicle.GUID';
                $sql=$sql.' WHERE tk_pj.GUID="'.$_GET['GUID'].'"';
				$sql=$sql.' AND tk_pj.skupina="'.$key.'"';
				$sql=$sql.' ORDER BY tk_pj.ID ASC';				
                $result = mysqli_query ($db_connect, $sql);
                $remaining_lessons=$osnova[$skupina][$key]-$odjeto[$key]+$kondice[$key];
            ?>
            <!--hlavicka-->
            <tr class="warning">
            	<th>
                	<h4>Výcvik <? echo $key?></h4>
                	<h4><small>odemčeno</small>&nbsp;<span class="label label-default"><? echo $jizdy['bank_'.$key]?></span></h4><!--kolik hodin je mozne planovat-->
                </th>
            </tr>
            <tr class="info">
            	<th colspan="2">
                	<h5><? echo $arrayUser[$radek['ucitel_'.$key]]['jmeno']?></h5>
                    <input type="hidden" id="ucitelID_<? echo $key?>" value="<? echo $radek['ucitel_'.$key]?>">
				</th>                
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
			<tr class="info"><td>Základní rozsah&nbsp;<span class="badge"><? echo $osnova[$skupina][$key]?></span></td></tr>
            <tr class="info"><td>Doplňkový výcvik&nbsp;<span class="badge"><? echo $jizdy['kondice_'.$key] ?></span></td></tr>

            
            <? $count=0?>
            <? while ($lesson=mysqli_fetch_array($result)): /*vypis hodin*/?>
                <label><? $count++?></label>                
                <tr>
                    <td>
	                    <h4 class="float-left"><span class="label label-default"><? echo $count?></span></h4> 
                    	<? if (($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) AND !isset($readonly)):?>
	                   	 	<button class="button_praxe_delete btn btn-danger pull-right" data-id="<?=$lesson['ID']?>" value="<? echo $_GET['GUID']?>" ><span class="glyphicon glyphicon-trash"></span></button>
						<? endif?>                                           	
						<? echo date('d.m.Y', strtotime($lesson['datum']))?>
                        <br />
						<div class="m-b-sm"><?= $arrayUser[$lesson['ucitel']]['jmeno']?></div>


<? if (($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)):?>
                        <? if ($lesson['rz']==''):?>                        	
                            <div class="input-group m-b-sm">
                                <span class="input-group-addon">RZ</span>
                                    <select name="GUID_vehicle" class="pj_detail form-control">
                                        <option value="">Vyber...</option>
                                        <? 
                                            $sql="SELECT GUID, rz, typ FROM vehicle WHERE aktivni='1'";
                                            $rsV = mysqli_query ($db_connect, $sql);
                                            while ($rsVX=mysqli_fetch_array($rsV)):
                                        ?>
                                            <option value="<?=$rsVX['GUID']?>"><?=$rsVX['typ']?> <?=$rsVX['rz']?></option>
                                        <? endwhile?>
                                    </select>
                                <span class="input-group-btn"><button class="btn_pj_detail btn btn-default" data-id="<?=$lesson['ID']?>" disabled><span class="glyphicon glyphicon-ok"></span></button></span> 
                            </div> 
                        <? else:?>
                            <?=$lesson['rz']?>
						<? endif?>
						
						<? if ($lesson['cas_od']=='00:00:00'):?>
                            <div class="input-group m-b-sm">
                                <span class="input-group-addon input-group-addon-150">Čas</span> 
                                <input type="text" data-id=<?=$lesson['ID']?> class="pj_cas_od form-control jqtimepicker" />
                                <input id="cas_do_<?=$lesson['ID']?>" type="text" class="pj_cas_do form-control" disabled/>
                                <span class="input-group-btn"><button class="btn_pj_detail btn_pj_cas btn btn-default" data-id="<?=$lesson['ID']?>" disabled><span class="glyphicon glyphicon-ok"></span></button><span class="glyphicon glyphicon-plus"></span></button></span> 
                            </div>
	                    <? else:?>
                        	<br />
                            <?=date('H:i', strtotime($lesson['cas_od']))?>
                            -
                            <?=date('H:i', strtotime($lesson['cas_do']))?>
						<? endif?>  
                         
                        <? if ($lesson['km_od']==0):?>                          
                            <div class="input-group">
                                <span class="input-group-addon">Km od</span> 
                                <input type="number" name="km_od" class="pj_detail form-control" />
                                <span class="input-group-btn"><button class="btn_pj_detail btn btn-default" data-id="<?=$lesson['ID']?>" disabled><span class="glyphicon glyphicon-ok"></span></button><span class="glyphicon glyphicon-plus"></span></button></span> 
                            </div>                    
	                    <? else:?>
                            <?=$lesson['km_od']?>
						<? endif?>
                        
                        
                        <? if ($lesson['km_od']==0):?>
                            <div class="input-group">
                                <span class="input-group-addon">Km do</span> 
                                <input type="number" name="km_do" class="pj_detail form-control" />
                                <span class="input-group-btn"><button class="btn_pj_detail btn btn-default" data-id="<?=$lesson['ID']?>" disabled><span class="glyphicon glyphicon-ok"></span></button><span class="glyphicon glyphicon-plus"></span></button></span> 
                            </div>  
	                    <? else:?>
                            <?=$lesson['km_do']?>
						<? endif?>                            
					<? endif?>                                                                      
                    </td>
				</tr>
                
            <? endwhile?>
    
    		<? 
			/*********PRAVA NA ZAPIS JIZD********/
			if (($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1) OR ($_COOKIE['userID']==$radek['ucitel_'.$key])):
			?>
				<? if ($jizdy['bank_'.$key]>0 AND !isset($readonly)):
                    $count++;?>
                    <tr>
                        <td>
                        	<div class="input-group m-b">
                            	<span class="input-group-addon"><? echo $count?></span> 
                            	<input id="datum_<? echo $key?>" type="text" class="form-control jqdatepicker" />
                            	<span class="input-group-btn"><button value="<? echo $_GET['GUID']?>" class="button_praxe_insert btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button></span> 
                            </div>                            
                        </td>
                    </tr>
                <? endif?>
            <? endif?>
 		</table>
	</div>
<? endforeach?>



<script>
	$(document).ready(function(){
		$(document).off('change', '.pj_cas_od').on('change', '.pj_cas_od',function() {
			var time_do = new Date();
			time_do.setHours($(this).val().slice(0,2),parseInt($(this).val().slice(3,5))+45);
			var hour_do =time_do.getHours();
			var min_do=time_do.getMinutes();
			if (hour_do<10) hour_do='0'+hour_do;
			if (min_do<10) min_do='0'+min_do;
			$('#cas_do_'+$(this).attr('data-id')).val(hour_do+':'+min_do);
			
			if ($(this).val()!="") {
				$(this).closest('div').find('button').removeClass('btn-default').removeAttr('disabled').addClass('btn-success');
			}
			else $(this).closest('div').find('button').removeClass('btn-success').attr('disabled','').addClass('btn-default').addClass('btn-default');
			
			
		});
	});
</script>