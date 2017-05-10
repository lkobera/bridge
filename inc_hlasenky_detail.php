<div class="container-fluid">

	<?php 
		$sql='	SELECT 
				*, 
				IFNULL(zahajeni,"") AS zahajeni
				FROM slozky
				WHERE folderID="'.$_GET['folderID'].'"';
		
	  	$result=mysqli_query ($db_connect, $sql);		
		$radek=mysqli_fetch_array($result);
		if ($radek['zahajeni']=='') $zahajeni='<span class="bg-danger">&nbsp;datum zahájení nezadáno&nbsp;</span>';
		else $zahajeni=date('d.m.Y', strtotime($radek['zahajeni']));
	  	
		
		$sql='SELECT * FROM matrika WHERE folderID="'.$_GET['folderID'].'"';
	  	$result2=mysqli_query ($db_connect, $sql);		
	?>

    <p>autoskolaeasy.cz</p>
    <p>Autoškola Lukáš Kobera<br>IČ: 72583347</p>
    
    <h1>Seznam žadatelů zařazených do výuky a výcviku<br><small>Individuální studijní plán</small></h1>
    <h4><?php echo $radek['loc'].' '.$zahajeni?></h4>

	<div class="seznam">
		<table class="table table-condensed table-bordered">
			<tr class="active">
              <td><strong>Ev. č.</strong></td>
              <td><strong>Jméno</strong></td>
              <td><strong>Skupina</strong></td>
              <td><strong>Narozen</strong></td>
              <td><strong>Bydliště</strong></td>
              <td><strong>Číslo ŘP</strong></td>
			</tr>

			<? while ($radek2=mysqli_fetch_array ($result2)): ?>
                <? $narozen=date('d.m.Y', strtotime($radek2['narozen']));?>
   
				<tr>
					<td><? echo $radek2['UID']?></td>
					<td><? echo strtoupper_all($radek2 ['prijmeni']).' '.$radek2['jmeno']?></td>
					<td><? echo skupina_kod('matrika',$radek2['GUID'])?></td>
					<td><? echo $narozen?></td>
					<td><? echo $radek2['adresa1'].' '.$radek2['cpopisne'].' '.$radek2['adresa2'].' '.$radek2['psc']?></td>
					<td><? echo $radek2['rp']?></td>
				</tr>
            <? endwhile?>
            
		</table>
	</div>
	<br /><br />
	<div class="well">
	    <a href="?menu=hlasenky" class="btn btn-info"><span class="glyphicon glyphicon-backward"></span>&nbsp;Zpět</a>
        <? if($radek['status']==0):?>
			<button type="button" class="btn btn-info disabled"><span class="glyphicon glyphicon-print"></span>&nbsp;Tisk</button>
		    <button type="button" class="btn btn-info disabled" ><span class="glyphicon glyphicon-file"></span>&nbsp;Export do XML</button>
        <? endif?>
        <? if($radek['status']==1):?>
			<button type="button" class="btn btn-info" onClick="window.print()"><span class="glyphicon glyphicon-print"></span>&nbsp;Tisk</button>
		    <button type="button" class="button_xml_hlasenka btn btn-info" value="<? echo $_GET['folderID']?>"><span class="glyphicon glyphicon-file"></span>&nbsp;Export do XML</button>
        <? endif?>

	</div>
</div>
