<!-- Tady se spousti tablesort + tablefilter -->
<script>
	$(function(){
		$('.tablesorter').tablesorter({
			widgets        : ['filter','stickyHeaders'],
			headers: { 0: { filter: false },7: { filter: false }, }, /*toto funguje jen na Instruktor view, u Admina je to vypnuté přes CSS*/
			usNumberFormat : false,
			sortReset      : true, 
			sortRestart    : true
		});
	});
</script>



<script>
	var poznamka=[];
	var poznamka_ext=[];
</script>
<?php

/*UKLADANI Z EDITACE*/
	if (isset($_POST['registrace_save'])) {
		
		$sql='	UPDATE matrika SET
				prijmeni="'.$_POST['prijmeni'].'"
				,jmeno="'.$_POST['jmeno'].'"
				,narozen="'.date('Y-m-d', strtotime($_POST['narozen'])).'"
				,adresa1="'.$_POST['adresa1'].'"
				,cpopisne="'.$_POST['cpopisne'].'"
				,adresa2="'.$_POST['adresa2'].'"
				,psc="'.$_POST['psc'].'"
				,tel="'.$_POST['tel'].'"
				,mail="'.$_POST['mail'].'"
				,rc="'.$_POST['rc'].'"
				,cOP="'.$_POST['cOP'].'"
				,mistonar="'.$_POST['mistonar'].'"
				,obcanstvi="'.$_POST['obcanstvi'].'"
				,rp="'.$_POST['rp'].'"
				,cena="'.$_POST['cena'].'"
				
				,status="'.$_POST['status'].'"
		
				,maAM="'.$_POST['maAM'].'"
				,maA1="'.$_POST['maA1'].'"
				,maA2="'.$_POST['maA2'].'"
				,maA="'.$_POST['maA'].'"
				,maB="'.$_POST['maB'].'"
				,maC="'.$_POST['maC'].'"
				,maD="'.$_POST['maD'].'"
				,maE="'.$_POST['maE'].'"
				,maT="'.$_POST['maT'].'"
		
				,request="'.$_POST['request'].'"
		
				,chceAM="'.$_POST['chceAM'].'"
				,chceA1="'.$_POST['chceA1'].'"
				,chceA2="'.$_POST['chceA2'].'"
				,chceA="'.$_POST['chceA'].'"
				,chceB="'.$_POST['chceB'].'"
				WHERE GUID="'.$_POST['registrace_save'].'"';

		mysqli_query($db_connect,$sql);
		unset ($_POST['registrace_save']);
	}



	/*nastaveni defaultniho pohledu podle prav uzivatele*/
	if (($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)) {
			if (isset($_GET['ucitel'])) $_SESSION['ucitel']=$_GET['ucitel'];
			if (isset($_GET['view'])) $_SESSION['view']=$_GET['view'];
			if (!isset($_SESSION['view'])) $_SESSION['view']='admin';
	}
		
	else {
		$_SESSION['ucitel']=$_COOKIE['userID'];
		$_SESSION['view']='instruktor';
	}
	

	


	
	/*NASTAVENI SESSION PRO ZOBRAZENI STATUSU V MATRICE*/
	if (isset($_GET['status0'])) $_SESSION['status0']=$_GET['status0'];
	if (isset($_GET['status1'])) $_SESSION['status1']=$_GET['status1'];
	if (isset($_GET['status2'])) $_SESSION['status2']=$_GET['status2'];
	if (isset($_GET['status3'])) $_SESSION['status3']=$_GET['status3'];
	if (isset($_GET['status4'])) $_SESSION['status4']=$_GET['status4'];
	if (isset($_GET['status5'])) $_SESSION['status5']=$_GET['status5'];
	if (isset($_GET['status6'])) $_SESSION['status6']=$_GET['status6'];




	
	/*SELECT Z MATRIKY*/
	$sql='	SELECT
			a.* , 
			IFNULL(a.zahajen,"") AS zahajeni,
			IFNULL(a.ukoncen,"") AS ukoncen,
				(SELECT MIN(datum)
				FROM zkousky_terminy zt
				JOIN zkousky_zaci zz
				ON zz.zkID=zt.id
				WHERE zz.GUID=a.GUID) AS prvnizk
			FROM matrika a
			WHERE a.loc="'.$_SESSION['loc'].'"';
			
	if ($_SESSION['ucitel']>0)	{ 
		$sql=$sql.' AND (ucitel_A="'.$_SESSION['ucitel'].'"';
		$sql=$sql.' OR ucitel_B="'.$_SESSION['ucitel'].'")';
	}
	$sql=$sql.' AND (';
		if ($_SESSION['status0']=='1') $sql=$sql.' status="0"'; else $sql=$sql.'status=99';
		if ($_SESSION['status1']=='1') $sql=$sql.' OR status="1"'; else $sql=$sql.' OR status=99';
		if ($_SESSION['status2']=='1') $sql=$sql.' OR status="2"'; else $sql=$sql.' OR status=99';
		if ($_SESSION['status3']=='1') $sql=$sql.' OR status="3"'; else $sql=$sql.' OR status=99';
		if ($_SESSION['status4']=='1') $sql=$sql.' OR status="4"'; else $sql=$sql.' OR status=99';
		if ($_SESSION['status5']=='1') $sql=$sql.' OR status="5"'; else $sql=$sql.' OR status=99';
		if ($_SESSION['status6']=='1') $sql=$sql.' OR status="6"'; else $sql=$sql.' OR status=99';
	$sql=$sql.')';	
	$result = mysqli_query ($db_connect, $sql);
	$resultxs = mysqli_query ($db_connect, $sql);
?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small><? echo $_SESSION['loc']?> | Evidence žáků</small><br />Matriční kniha</h1>
        
        
       
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
			<select class="form-control" id="matrika_select_ucitel">
	    		<?php option_ucitel($_SESSION['ucitel']);?>
			</select>
		</div>
    <? endif?>
    
    <? if (($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)):?>    
	    <a href="/?menu=matrika&status0=<? echo abs($_SESSION['status0']-1)?>" class="btn statusRed<?php if ($_SESSION['status0']=='1') echo ' active'?>">nový žák</a>
	    <a href="/?menu=matrika&status1=<? echo abs($_SESSION['status1']-1)?>" class="btn statusOrange<?php if ($_SESSION['status1']=='1') echo ' active'?>">zahájen</a>
	<? endif?>
    <a href="/?menu=matrika&status2=<? echo abs($_SESSION['status2']-1)?>" class="btn statusLGreen<?php if ($_SESSION['status2']=='1') echo ' active'?>">čeká na výcvik</a>
    <a href="/?menu=matrika&status3=<? echo abs($_SESSION['status3']-1)?>" class="btn statusDGreen<?php if ($_SESSION['status3']=='1') echo ' active'?>">ve výcviku</a>
    <a href="/?menu=matrika&status4=<? echo abs($_SESSION['status4']-1)?>" class="btn statusLBlue<?php if ($_SESSION['status4']=='1') echo ' active'?>">před zkouškou</a>
    <a href="/?menu=matrika&status5=<? echo abs($_SESSION['status5']-1)?>" class="btn statusDBlue<?php if ($_SESSION['status5']=='1') echo ' active'?>">repro</a>
    <a href="/?menu=matrika&status6=<? echo abs($_SESSION['status6']-1)?>" class="btn statusGrey<?php if ($_SESSION['status6']=='1') echo ' active'?>">archiv</a>

	<? if (($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)):?>
    <div class="btn-group">
    	<a href="/?menu=matrika&view=admin" class="btn btn-default <? if ($_SESSION['view']=='admin') echo 'active'?>">Admin</a>
        <a href="/?menu=matrika&view=instruktor" class="btn btn-default <? if ($_SESSION['view']=='instruktor') echo 'active'?>">Instruktor</a>
    </div>
    <? endif?>

<br><br>

<!--ADMIN VIEW-->
<? if ((($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)) AND ($_SESSION['view']=='admin')):?>
    <div class="table-responsive">
      <table class="table table-hover table-responsive tablesorter">
          <thead>
                <tr>
                  <td colspan="7" class="nosort"></td>
                  <td colspan="3" class="nosort bg-warning"><h4>Management výcviku</h4></td>
                  <td colspan="3" class="nosort bg-warning text-right"><button class="testb btn btn-primary">Evidenční kniha - export do XLS</button></td>
                </tr>
                <tr>
                  <td class="filter-false nosort bg-info"></td>
                  <th class="sort bg-info">Ev.č.</th>
                  <th class="sort bg-info">Příjmení a jméno</th>
                  <th class="sort bg-info">Výcvik</th>
                  <th class="sort bg-info">Narozen</th>
                  <th class="nosort bg-info">Adresa</th>
                  <th class="nosort bg-info">Tel.<br />E-mail</th>
                  <th class="sort bg-warning">Zahájení</th>
                  <th class="filter-false nosort bg-warning">Instruktor</th>
                  <th class="sort bg-warning">Ukončení</th>
                  <th class="nosort bg-warning">Zkoušky</th>
                  <th colspan="2" class="nosort bg-warning">Poznámka<br><em><span class="glyphicon glyphicon-comment"></span>&nbsp;interní<br><span class="glyphicon glyphicon-paperclip"></span>&nbsp;pro komisaře</em></th>
                </tr>
            </thead>

        <!--VYPIS RADKU MATRIKY-->
        	<tbody>
				<? while ($radek = mysqli_fetch_array ($result)): 
                    $skupina_kod=skupina_kod('matrika',$radek['GUID']);
                    if ($radek['zahajeni']<>'') $zahajen='<a href=/?menu=hlasenky_detail&folderID='.$radek['folderID'].' class="btn btn-sm btn-link"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;'.date('d.m.Y', strtotime($radek['zahajeni'])).'</a>'; else $zahajen=$radek['zahajeni'];
                    
                    if ($radek['ukoncen']=='') $ukoncen='';
                    else $ukoncen=date('d.m.Y', strtotime($radek['ukoncen']));
                ?>
                  <script>
                    poznamka["<? echo $radek['GUID']?>"]="<? echo $radek['poznamka']?>"
                    poznamka_ext["<? echo $radek['GUID']?>"]="<? echo $radek['poznamka_ext']?>"
                  </script>
                  <tr id="<? echo $radek['GUID']?>">
                    <td><a href="?menu=matrika_edit&GUID=<? echo $radek['GUID']?>" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a></td>
                    <td class="<? echo switchstatus($radek['status'])?>"><? echo $radek['UID']?></td>
                    <td class="<? echo switchstatus($radek['status'])?>"><strong><a href="?menu=karta_zaka&GUID=<?=$radek['GUID']?>&source=matrika"><? echo strtoupper_all($radek['prijmeni']).' '.$radek['jmeno']?></a></strong></td>
                    <td><? echo $skupina_kod?></td>
                    <td><? echo date('d.m.Y',strtotime ($radek['narozen']))?></td>
                    <td><? echo $radek['adresa1'].' '.$radek['cpopisne'].'<br>'.$radek['adresa2'].' '.$radek['psc']?></td>
                    <td><a href="tel:<? echo $radek['tel']?>"><? echo $radek['tel']?></a><br /><a href="mailto:<? echo $radek['mail']?>"><? echo $radek['mail']?></a></td>
                    <td><? echo $zahajen?><br></td>
                    
                    <!--MAPOVANI INSTURKTORU-->            
                    
                    <td>
                        <table>
                            <!--A-->
                            <? if ($osnova[$skupina_kod]['A']>0):?>
                                <tr>
                                    <td><h4><span class="label label-info">A</span></h4></td>
                                    <td>
                                        <select name="ucitel_A" class="matrika_ucitel form-control input-sm">
                                            <? option_ucitel($radek['ucitel_A']);?>
                                        </select>
                                    </td>
                                </tr>
                            <? endif?>
                            <!--B-->
                            <? if ( $osnova[$skupina_kod]['B']>0):?>
                                <tr>
                                    <td> <h4><span class="label label-primary">B</span></h4></td>
                                    <td>
                                        <select name="ucitel_B" class="matrika_ucitel form-control input-sm">
                                            <? option_ucitel($radek['ucitel_B']);?>
                                        </select>
                                    </td>
                                </tr>
                            <? endif?>
                        </table>
                    </td>
                    
                    
                    <td><? echo $ukoncen?></td>
                    <!--zkousky-->
                    <td>
                        <table>                        	
                        	<? if ($radek['prvnizk']<>''):?>
	                            <tr><td><?=date('d.m.Y',strtotime($radek['prvnizk']))?></td></tr>
							<? endif?>
                            <tr><td><? if ($radek['PPV']==1):?><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;PPV<? endif?></td></tr>
                            <tr><td><? if ($radek['PJ_A']==1):?><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;PJ A<? endif?></td></tr>
                            <tr><td><? if ($radek['PJ_B']==1):?><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;PJ B<? endif?></td></tr>
                        </table>
                    </td>
                    
                    
                    <!--ZADAVANI POZNAMEK-->
                    <td>
                        <? if ($radek['poznamka']<>''):?>
                            <a class="block" href="javascript:void(0)" data-toggle="popover" title="<? echo $radek['prijmeni'].' '.$radek['jmeno']?>" data-placement="left" data-trigger="focus" data-content="<? echo $radek['poznamka']?>"><span class="glyphicon glyphicon-comment"></span>&nbsp;<? echo substr ($radek['poznamka'],0,40)?>...</a>
                        <? endif?>
                        <? if ($radek['poznamka_ext']<>''):?>
                           <a class="block" href="javascript:void(0)" data-toggle="popover" title="<? echo $radek['prijmeni'].' '.$radek['jmeno']?>" data-placement="bottom" data-trigger="focus" data-content="<? echo $radek['poznamka_ext']?>"><span class="glyphicon glyphicon-paperclip"></span>&nbsp;<? echo substr ($radek['poznamka_ext'],0,40)?>...</a>
                        <? endif?>                
                    </td>
                    <td>
                       <button value="<? echo $radek['GUID']?>" class="btn-poznamka-edit btn btn-info" data-toggle="modal" data-target="#poznamka"><span class="glyphicon glyphicon-pencil"></span></button>
                    </td>
                </tr>
                <? endwhile; ?>
			</tbody>
		</table>
        
    </div>
<? endif?>



<!--INSTRUKTOR VIEW-->


<? if ((($arrayUser[$_COOKIE['userID']]['pravo-admin']==0) AND ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==0)) OR ($_SESSION['view']=='instruktor')):?>
<!--FULL VIEW-->

	<div class="table-responsive">
    	<table class="hidden-xs table table-hover table-responsive tablesorter">
        	<thead>
            	<tr><td class="nosort nofilter"><br><br></td></tr>
				<tr>
	            	<th class="sort bg-info">Ev.č.&nbsp;&nbsp;</th>
	          		<th class="sort bg-info">Příjmení a jméno&nbsp;&nbsp;</th>
	          		<th class="sort bg-info">Výcvik&nbsp;&nbsp;</th>
	         		<th class="sort bg-info">Narozen&nbsp;&nbsp;</th>
	         		<th class="nosort bg-info">Adresa</th>
	          		<th class="nosort bg-info">Tel.<br />E-mail</th>
					<th class="sort bg-info">Zahájen&nbsp;&nbsp;</th>
	                <th class="nosort bg-info">Instruktor</th>
	                <th class="sort bg-info">Ukončení&nbsp;&nbsp;</th>
	                <th class="nosort bg-warning">Zkoušky</th>
	                <th class="nosort bg-warning" colspan="2">Poznámka<br><em><span class="glyphicon glyphicon-comment"></span>&nbsp;interní<br><span class="glyphicon glyphicon-paperclip"></span>&nbsp;pro komisaře</em></th>
				</tr>
			</thead>


            <!--VYPIS RADKU MATRIKY-->
            <tbody>
				<? while ($radek = mysqli_fetch_array ($result)): 
                    if ($radek['ukoncen']=='') $ukoncen='';
                    else $ukoncen=date('d.m.Y', strtotime($radek['ukoncen']));
                    $skupina=skupina_kod('matrika',$radek['GUID']);
                    $status=switchstatus($radek['status']);
                    if ($radek['zahajeni']<>'') $zahajen=date('d.m.Y', strtotime($radek['zahajeni'])); else $zahajen=$radek['zahajeni'];
                ?>
                <script>
                    poznamka["<? echo $radek['GUID']?>"]="<? echo $radek['poznamka']?>"
                    poznamka_ext["<? echo $radek['GUID']?>"]="<? echo $radek['poznamka_ext']?>"
                </script>
                <tr>
                    <td class="<? echo $status?>">
                        <? echo $radek['UID']?>
                        <div class="visible-xs"><? echo skupina_kod('matrika', $radek['GUID']);?></div>
                    </td>
                    
                    <td class="<? echo $status?>">
                        <strong><a href="?menu=karta_zaka&GUID=<?=$radek['GUID']?>&source=matrika"><? echo strtoupper_all($radek['prijmeni']).' '.$radek['jmeno']?></a></strong>
                    </td>
                    
                    
                    <td class="hidden-xs"><? echo skupina_kod('matrika', $radek['GUID']);?></td>
                    <td class="hidden-xs"><? echo date('d.m.Y',strtotime ($radek['narozen']))?></td>
                    <td class="hidden-xs"><? echo $radek['adresa1'].' '.$radek['cpopisne'].'<br>'.$radek['adresa2'].' '.$radek['psc']?></td>
                    <td class="hidden-xs"><a href="tel:<? echo $radek['tel']?>"><? echo $radek['tel']?></a><br /><a href="mailto:<? echo $radek['mail']?>"><? echo $radek['mail']?></a></td>
                    <td class="hidden-xs"><? echo $zahajen?></td>
                    <td class="hidden-xs">
                        <? if ($radek['chceAM']==1 OR $radek['chceA1']==1 OR $radek['chceA2']==1 OR $radek['chceA']==1):?>
                            <h4><span class="label label-info">A</span>&nbsp;<small><? echo $arrayUser[$radek['ucitel_A']]['jmeno']?></small></h4>
                        <? endif?>
                        <? if ($radek['chceB']==1):?>
                            <h4><span class="label label-primary">B</span>&nbsp;<small><? echo $arrayUser[$radek['ucitel_B']]['jmeno']?></small></h4>
                        <? endif?>
                    </td>
                    
                    <td class="hidden-xs"><? echo $ukoncen?></td>
                    
                    <!--zkousky-->
                    <td class="hidden-xs">
                        <table>
                       	 	<? if ($radek['prvnizk']<>''):?>
	                            <tr><td><?=date('d.m.Y',strtotime($radek['prvnizk']))?></td></tr>
							<? endif?>
                            <tr><td><? if ($radek['PPV']==1):?><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;PPV<? endif?></td></tr>
                            <tr><td><? if ($radek['PJ_A']==1):?><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;PJ A<? endif?></td></tr>
                            <tr><td><? if ($radek['PJ_B']==1):?><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;PJ B<? endif?></td></tr>
                        </table>
                    </td>
                    
                    
                    <td class="hidden-xs">
                        <? if ($radek['poznamka']<>''):?>
                            <a class="block" href="javascript:void(0)" data-toggle="popover" title="<? echo $radek['prijmeni'].' '.$radek['jmeno']?>" data-placement="bottom" data-trigger="focus" data-content="<? echo $radek['poznamka']?>"><span class="glyphicon glyphicon-comment"></span>&nbsp;<? echo substr ($radek['poznamka'],0,20)?>...</a>
                        <? endif?>
                        <? if ($radek['poznamka_ext']<>''):?>
                            <a class="block" href="javascript:void(0)" data-toggle="popover" title="<? echo $radek['prijmeni'].' '.$radek['jmeno']?>" data-placement="bottom" data-trigger="focus" data-content="<? echo $radek['poznamka_ext']?>"><span class="glyphicon glyphicon-paperclip"></span>&nbsp;<? echo substr ($radek['poznamka_ext'],0,40)?>...</a>
                        <? endif?>
                    </td>
                    
                    
                    <td class="hidden-xs"><button value="<? echo $radek['GUID']?>" class=" btn-poznamka-edit btn btn-info" data-toggle="modal" data-target="#poznamka"><span class="glyphicon glyphicon-pencil"></span></button></td>
                </tr>
                <? endwhile;?>
			</tbody>
		</table>





<!--MOBILE VIEW-->

    	<table class="visible-xs table table-hover table-responsive tablesorter">
        	<thead>
				<tr>
	            	<th class="sort bg-info">Ev.č.&nbsp;&nbsp;</th>
	          		<th class="sort bg-info">Příjmení a jméno<br>Poznámka&nbsp;&nbsp;</th>
                    <th class="sort bg-info">Skupina&nbsp;&nbsp;</th>
                    <td class="nosort filter-false bg-info"></td>
				</tr>
			</thead>


            <!--VYPIS RADKU MATRIKY-->
            <tbody>
				<? while ($radek = mysqli_fetch_array ($resultxs)): 
                    if ($radek['ukoncen']=='') $ukoncen='';
                    else $ukoncen=date('d.m.Y', strtotime($radek['ukoncen']));
                    $skupina=skupina_kod('matrika',$radek['GUID']);
                    $status=switchstatus($radek['status']);
                    if ($radek['zahajeni']<>'') $zahajen=date('d.m.Y', strtotime($radek['zahajeni'])); else $zahajen=$radek['zahajeni'];
                ?>
                <script>
                    poznamka["<? echo $radek['GUID']?>"]="<? echo $radek['poznamka']?>"
                    poznamka_ext["<? echo $radek['GUID']?>"]="<? echo $radek['poznamka_ext']?>"
                </script>
                <tr>
                    <td class="<? echo $status?>">
                        <?= $radek['UID']?>
                        <button value="<? echo $radek['GUID']?>" class="btn-poznamka-edit btn btn-info" data-toggle="modal" data-target="#poznamka"><span class="glyphicon glyphicon-pencil"></span></button>
                    </td>
                    
                    <td class="<? echo $status?>">
                    
                    	<strong><a href="?menu=karta_zaka&GUID=<?=$radek['GUID']?>&source=matrika"><? echo strtoupper_all($radek['prijmeni']).' '.$radek['jmeno']?></a></strong>
                        <br>
                        <? if ($radek['poznamka']<>''):?>
                            <a class="block" href="javascript:void(0)" data-toggle="popover" title="<? echo $radek['prijmeni'].' '.$radek['jmeno']?>" data-placement="bottom" data-trigger="focus" data-content="<? echo $radek['poznamka']?>"><span class="glyphicon glyphicon-comment"></span>&nbsp;<? echo substr ($radek['poznamka'],0,15)?>...</a>
                        <? endif?>
                        <? if ($radek['poznamka_ext']<>''):?>
                            <a class="block" href="javascript:void(0)" data-toggle="popover" title="<? echo $radek['prijmeni'].' '.$radek['jmeno']?>" data-placement="bottom" data-trigger="focus" data-content="<? echo $radek['poznamka_ext']?>"><span class="glyphicon glyphicon-paperclip"></span>&nbsp;<? echo substr ($radek['poznamka_ext'],0,15)?>...</a>
                        <? endif?>
					</td>
                    <td><? echo skupina_kod('matrika', $radek['GUID']);?></td>
                    <td>
	                    <a href="tel:<? echo $radek['tel']?>" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-phone-alt"></span></a>
                        <? if ($radek['mail']<>''):?>
                              <a href="mailto:<? echo $radek['mail']?>" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-envelope"></span></a>
                        <? endif ?>
                    </td>
                        
                       
                        
						
                        
                    </td>
                </tr>

                <? endwhile;?>
			</tbody>
		</table>



        
        
        
	</div>
<? endif;?>
</div>

<!-- Modal Hlasenka -->
<div id="poznamka" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
		<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
       		  	<h4 class="modal-title"><small><? echo $_SESSION['loc']?> | Evidence žáků | Matriční kniha</small><br>Poznámka</h4>
      		</div>
      		<div class="modal-body">
            	<label><span class="glyphicon glyphicon-comment"></span>&nbsp;interní</label>
				<textarea class="form-control" id="poznamka-text">
	            </textarea>
  			</div>
            <div class="modal-body">
            	<label><span class="glyphicon glyphicon-paperclip"></span>&nbsp;pro komisaře</label>
				<textarea class="form-control" id="poznamka_ext-text">
	            </textarea>
  			</div>
      	<div class="modal-footer">
      	<button value="" id="button_poznamka_save" type="button" class="btn btn-info" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span>&nbsp;Uložit</button>
	</div>
</div>
