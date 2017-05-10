<script>
	$(function(){
		$('.tablesorter').tablesorter({
			widgets        : ['filter','stickyHeaders'],
			headers: { 6: { filter: false },7: { filter: false }, }, 
			usNumberFormat : false,
			sortReset      : true, 
			sortRestart    : true
		});
	});
</script>
<?
	$osnovaZK=osnovaZK();
	
	/*nastaveni defaultniho pohledu podle prav uzivatele*/
	if (($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)) {
			if (isset($_GET['ucitel'])) $_SESSION['ucitel']=$_GET['ucitel'];
	}
		
	else $_SESSION['ucitel']=$_COOKIE['userID'];	


	
/*SELECT Z MATRIKY*/
	$sql='	SELECT
			*,IFNULL(ukoncen,"") AS ukoncen FROM matrika
			WHERE loc="'.$_SESSION['loc'].'"
			AND (ucitel_A="'.$_SESSION['ucitel'].'" OR ucitel_B="'.$_SESSION['ucitel'].'")
			AND (status>1 AND status<6)';
			
	$result = mysqli_query ($db_connect, $sql);
?>




<div class="container-fluid">

<!--PAGE LIST-->
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Evidence žáků</small><br />Třídní kniha - praxe</h1>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) 
				OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>
    </div>
    
    <? /****************UCITEL SELECT *****************/
	   if (($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)):?>
		<div class="col-md-2 col-sm-4">
			<select class="form-control" id="praxe_select_ucitel">
	    		<?php option_ucitel($_SESSION['ucitel']);?>
			</select>
            <br>
		</div>
    <? endif?>
    <br clear=all><br>
	<div class="table-responsive">
	<table class="table table-hover table-responsive tablesorter">
    	<thead>
        	<tr><td class="nosort"><br><br></td></tr>
            <tr>
                <th class="sort bg-info"><br>Ev.č.</th>
                <th class="sort bg-info">Příjmení a jméno</th>
                <th class="sort bg-info">Výcvik</th>
                <th class="sort bg-info hidden-xs">Narozen</th>
                <th class="sort bg-info hidden-xs">Bezp. datum</th>
                <th class="sort bg-warning hidden-xs">Plán. ukončení</th>
                <th class="nosort bg-warning" colspan="2"></th>
            </tr>
		</thead>
        <tbody
        
			<? while ($radek = mysqli_fetch_array ($result)): ?>
                <? 
                    if ($radek['ukoncen']=='') $ukoncen='';
                    else $ukoncen=date('d.m.Y', strtotime($radek['ukoncen']));
                ?>
                <? 
                    $skupina=skupina_kod('matrika',$radek['GUID']);
                    $safedate=date('d.m.Y', mktime(0, 0, 0, date('m',strtotime($radek['narozen'])), date('d',strtotime($radek['narozen'])), date('Y',strtotime($radek['narozen']))+$osnovaZK[$skupina]['vek']));
                    $jizdy=jizdy($radek['GUID'], skupina_kod('matrika',$radek['GUID'])); /*nastaveni pole odjetych a predplacenych jizd*/
                
                ?>
                
                
                <tr>
                    <td class="<? echo switchstatus($radek['status'])?>"><? echo $radek['UID']?></td>
                    <td class="<? echo switchstatus($radek['status'])?>"><a href="?menu=karta_zaka&GUID=<?=$radek['GUID']?>&source=matrika"><strong><? echo strtoupper_all($radek['prijmeni']).' '.$radek['jmeno']?></strong></a></td>
                    <td><? echo $skupina?></td>
                    <td><? echo date('d.m.Y',strtotime ($radek['narozen']))?></td>
                    <td><? if (date("Y-m-d")<date('Y-m-d', strtotime($safedate))):?><span class="glyphicon glyphicon-alert"></span>&nbsp;<? endif?><? echo $safedate?></td>
                    <td><? echo $ukoncen?></td>
                   
                    
    
                    <!--ODJETY VYCVIK-->
                    <td class="col-sm-4"> 
                        <!--A-->
                        <? if (($radek[chceA]==1) or ($radek[chceA1]==1) or ($radek[chceA2]==1) or ($radek[chceA]==1)):?>
                            <div class="progress">
                                <div class="progress-bar progress-bar-info" role="progressbar" style="width:<? echo procenta($jizdy['odjeto_A'],($osnova[$skupina]['A']+$jizdy['kondice_A']))?>%">
                                    <span><? echo 'A: '.$jizdy['odjeto_A'].'/'.($osnova[$skupina]['A']+$jizdy['kondice_A'])?></span>
                                </div>
                            </div>
                        <? endif?>
                        
                        <!--B-->
                        <? if ($radek[chceB]==1):?>
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary" role="progressbar" style="width:<? echo procenta($jizdy['odjeto_B'],($osnova[$skupina]['B']+$jizdy['kondice_B']))?>%">
                                    <span><? echo 'B: '.$jizdy['odjeto_B'].'/'.($osnova[$skupina]['B']+$jizdy['kondice_B'])?></span>
                                </div>
                            </div>
                        <? endif?>
                        
                        
                        
                    </td><!--ODJETY VYCVIK KONEC-->
                    
                    
                    <td><!--detail button-->
                        <a href="?menu=praxe_edit&GUID=<? echo $radek['GUID']?>" 
                        <? if ($jizdy['bank']==0):?>class="btn btn-lg btn-warning"><? endif?>
                        <? if ($jizdy['bank']>0):?>class="btn btn-lg btn-primary"><? endif?>
                        <span class="glyphicon glyphicon-edit"></span>&nbsp;<span class="badge"><? echo $jizdy['bank']?></span></a>
                    </td>
                </tr>
               
            
            
            <? endwhile?>
        </tbody>
    </table> 
    </div>



<!--KONEC PAGE LIST-->

    
</div>
