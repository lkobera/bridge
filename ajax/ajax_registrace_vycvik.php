<?
	session_start();

	include_once $_GET['inc_path'].'inc_db_connect.php';
	include_once $_GET['inc_path'].'inc_library.php';
	
	/*vypocet predbezne hlasenych lidi*/
	$sql='SELECT COUNT(GUID) as hlaseno FROM registrace WHERE loc="'.$_SESSION['loc'].'" AND ApplicationStatus="3"';
	$result=mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
	$hlaseno=$radek['hlaseno'];

	
	$sql='	SELECT * FROM registrace
			WHERE loc="'.$_SESSION['loc'].'"
			AND request="'.$_GET['request'].'"
			ORDER BY datum DESC';
	$result=mysqli_query ($db_connect, $sql);
?>
<script>
	$(function(){
		$('.tablesorter').tablesorter({
			widgets        : ['filter','stickyHeaders'],
			headers: { 0: { filter: false },7: { filter: false }, }, /*toto funguje jen na Instruktor view, u Admina je to vypnuté přes CSS*/
			usNumberFormat : false,
			sortReset      : true, 
			sortRestart    : false
		});
	});
</script>


<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

<table class="table table-responsive table-condensed table-hover tablesorter">
	<thead>
    	<tr>
        	<th colspan="8" class="nosort bg-warning"><a href="/?menu=registrace_edit&request=<? echo $_GET['request']?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;Nová registrace</a></th>
	        <th class="nosort bg-warning text-right"><button class="btn btn-primary" data-toggle="modal" data-target="#hlasenka"><span class="glyphicon glyphicon-list"></span>&nbsp;Náhled hlášenky výcviku&nbsp;<snap class="badge"><? echo $hlaseno?></badge></button></th>
	    </tr>
	    <tr rowspan="2">
	        <th class="nosort filter-false bg-info"></th>
	        <th class="sort bg-info">Datum</th>
	        <th class="sort bg-info">Příjmení a jméno</th>
	        <th class="sort bg-info">Výcvik</th>
	        <th class="sort bg-info">Narozen</th>
	        <th class="sort bg-info">Adresa</th>
	        <th class="sort bg-info">Telefon<br />E-mail</th>
	        <th class="nosort filter-false bg-info"></th>
	        <th class="nosort bg-warning"><h4>Management registrací</h4></th>
	    </tr>
    </thead>
    <tbody>    
		<? while ($radek=mysqli_fetch_array($result)):?>
            <tr id="<? echo $radek['GUID']?>">
                <td><button class="registrace_delete btn btn-danger" data-toggle="tooltip" title="Vymazat z registrace"><span class="glyphicon glyphicon-trash"></span></button></td>
                <td><? echo date('d.m.Y', strtotime($radek['datum']))?></td>
                <td><a href="?menu=karta_zaka&GUID=<?=$radek['GUID']?>&source=registrace"><? echo strtoupper_all($radek['prijmeni']).' '.$radek['jmeno']?></a></td>
                <td><? echo skupina_kod('registrace',$radek['GUID'])?></td>
                <td><? echo date('d.m.Y', strtotime($radek['narozen']))?></td>
                <td><? echo $radek['adresa1'].' '.$radek['cpopisne']?><br /><? echo $radek['adresa2'].' '.$radek['psc']?></td>
                <td><a href="tel:<? echo $radek['tel']?>"><? echo $radek['tel']?></a><br /><a href="mailto:<? echo $radek['mail']?>"><? echo $radek['mail']?></a></td>
                <td><a href="?menu=registrace_edit&GUID=<? echo $radek['GUID']?>&request=<? echo $_GET['request']?>" class="btn btn-info" data-toggle="tooltip" title="Editace"><span class="glyphicon glyphicon-edit"></span></a></td>
                
                <td>
                    <div class="btn-group">
                        <? if ($radek['ApplicationStatus']==0):?>
                            <button class="app_status btn btn-primary" data-toggle="tooltip" title="Přihláška">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;&nbsp;</button>
                            <button class="btn btn-default disabled">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;&nbsp;</button>
                            <button class="btn btn-default disabled">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-share"></span>&nbsp;&nbsp;&nbsp;</button>
                        <? endif?>
                        <? if ($radek['ApplicationStatus']==1):?>
                            <button class="btn btn-success disabled">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;&nbsp;</button>
                            <button class="app_status btn btn-primary" data-toggle="tooltip" title="Zdravotní posudek">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;&nbsp;</button>
                            <button class="btn btn-default disabled">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-share"></span>&nbsp;&nbsp;&nbsp;</button>
                        <? endif?>
                        <? if ($radek['ApplicationStatus']==2):?>
                            <button class="btn btn-success disabled">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;&nbsp;</button>
                            <button class="btn btn-success disabled">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;&nbsp;</button>
                           <button class="app_status btn btn-success" data-toggle="tooltip" title="Zapsat do hlášenky">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-share"></span>&nbsp;&nbsp;&nbsp;</button>
                        <? endif?>
                    </div>
                    <? if ($radek['ApplicationStatus']==3):?>
                        <button class="app_status btn btn-warning"><span class="glyphicon glyphicon-remove"></span>&nbsp;Odstranit z hlášenky</button>
                    <? endif?>
    
                </td>
                
            </tr>
        <? endwhile?>
	</tbody>
</table>

<!-- Modal Hlasenka -->
<div id="hlasenka" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><small><?php echo $_SESSION['loc']?> | Admin | Registrace žáků</small><br />Náhled hlášenky výcviku</h4>
        <br>
        <p><span class="glyphicon glyphicon-alert"></span>&nbsp;Zkontrolujte, že všechna povinná pole jsou řádně vyplněná!</p>
      </div>
      <div class="modal-body">
        <? include 'inc_hlasenka_preview.php'?>
      </div>
      <div class="modal-footer">
      	<button id="button_hlasenka_create" type="button" class="btn btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span>&nbsp;Uložit hlášenku</button>
      </div>
    </div>

  </div>
</div>

