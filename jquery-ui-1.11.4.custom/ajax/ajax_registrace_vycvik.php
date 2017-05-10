<?
	session_start();

	include_once $_GET['inc_path'].'inc_db_connect.php';
	include_once $_GET['inc_path'].'inc_library.php';

	
	$sql='		SELECT * FROM registrace';
	$sql=$sql.' WHERE loc="'.$_SESSION['loc'].'"';
	$sql=$sql.' AND request="0"';
	$sql=$sql.'	ORDER BY datum DESC';
	$result=mysqli_query ($db_connect, $sql);
?>

<table class="table table-responsive table-hover">
    <tr>
        <th colspan="8"><a href="" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span>&nbsp;Nová registrace</a></th>
        <th colspan="3" class="warning"><h4>Management registrací</h4></th>
     </tr>
    <tr rowspan="2" class="info">
        <th></th>
        <th>Datum</th>
        <th>Příjmení a jméno</th>
        <th>Výcvik</th>
        <th>Narozen</th>
        <th>Adresa</th>
        <th>Telefon<br />E-mail</th>
        <th></th>
        <th class="warning"><span class="btn-lg glyphicon glyphicon-file"></span></th>
        <th class="warning"><span class="btn-lg glyphicon glyphicon-plus"></span></th>
        <th class="warning"><span class="btn-lg glyphicon glyphicon-road"></span></th>
        
    </tr>
        
    <? while ($radek=mysqli_fetch_array($result)):?>
        <tr id="<? echo $radek['GUID']?>">
            <td><button class="registrace_delete btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button></td>
            <td><? echo date('d.m.Y', strtotime($radek['datum']))?></td>
            <td><? echo strtoupper_all($radek['prijmeni']).' '.$radek['jmeno']?></td>
            <td><? echo skupina_kod('registrace',$radek['GUID'])?></td>
            <td><? echo date('d.m.Y', strtotime($radek['narozen']))?></td>
            <td><? echo $radek['adresa1'].' '.$radek['cpopis']?><br /><? echo $adresa2.' '.$radek['psc']?></td>
            <td><a href="tel:<? echo $radek['tel']?>"><? echo $radek['tel']?></a><br /><a href="mailto:<? echo $radek['mail']?>"><? echo $radek['mail']?></a></td>
			<td><a href="?menu=registrace_edit&GUID=<? echo $radek['GUID']?>&request=0" class="btn btn-info" data-toggle="tooltip" title="Editace"><span class="glyphicon glyphicon-edit"></span></a></td>
            
            <? if ($radek['ApplicationStatus']==0):?>
            	<td><button class="app_status btn btn-primary"><span class="glyphicon glyphicon-file"></span></button></td>
            	<td><button class="btn btn-default disabled"><span class="glyphicon glyphicon-duplicate"></span></button></td>
            	<td><button class="btn btn-default disabled"><span class="glyphicon glyphicon-share"></span></button></td>
			<? endif?>
            <? if ($radek['ApplicationStatus']==1):?>
               	<td><button class="btn btn-success disabled"><span class="glyphicon glyphicon-file"></span></button></td>
            	<td><button class="app_status btn btn-primary" data-toggle="tooltip" title="Zdravotní posudek"><span class="glyphicon glyphicon-duplicate"></span></button></td>
            	<td><button class="btn btn-default disabled"><span class="glyphicon glyphicon-share"></span></button></td>
            <? endif?>
            <? if ($radek['ApplicationStatus']==2):?>
               	<td><button class="btn btn-success disabled"><span class="glyphicon glyphicon-file"></span></button></td>
            	<td><button class="btn btn-success disabled"><span class="glyphicon glyphicon-duplicate"></span></button></td>
            	<td><button class="registrace_confirm btn btn-success" data-toggle="tooltip" title="Přesun do výcviku"><span class="glyphicon glyphicon-share"></span></button></td>
            <? endif?>
            
            
        </tr>
    <? endwhile?>
</table>

