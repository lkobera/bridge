
<div class="container-fluid">

	<?php 

	  $sql='	SELECT 
				zkousky_terminy.datum, zkousky_terminy.loc, zkousky_venue.adresa
				FROM zkousky_terminy
				LEFT JOIN zkousky_venue
				ON zkousky_terminy.venue_ID=zkousky_venue.ID
				WHERE zkousky_terminy.ID="'.$_GET['id'].'"';
				
				
				
	  $result=mysqli_query ($db_connect, $sql);		
	  $radek=mysqli_fetch_array ($result);
	  
	  	
	?>

    <p>autoskolaeasy.cz</p>
    <p>Autoškola Lukáš Kobera<br>IČ: 72583347</p>
    
    <h1>Seznam žadatelů ke zkoušce z odborné způsobilosti</h1>
    <h4><?php echo $radek['loc'].' '.date('d.m.Y', strtotime($radek['datum']))?><br><small><? echo $radek['adresa']?></small></h4>


	<div class="seznam">
		<table class="table table-condensed table-bordered">
			<tr class="active">
              <th>Ev. č.</th>
              <th>Jméno</th>
              <th>Skupina</th>
              <th>Narozen</th>
              <th>Bydliště</th>
              <th>Číslo ŘP</th>
              <th>PPV</th>
              <th>PJ A</th>
              <th>PJ B</th>
              <th>Učitel</th>
              <th>Pozn.</th>
			</tr>

			<?
                $sql='	SELECT 
						matrika.GUID,
						matrika.UID, 
						matrika.cmat, 
						matrika.rok, 
						matrika.prijmeni, 
						matrika.jmeno, 
						matrika.narozen, 
						matrika.adresa1, 
						matrika.adresa2, 
						matrika.psc, 
						matrika.rp, 
						matrika.ucitel_A, 
						matrika.ucitel_B,
						matrika.poznamka_ext,
						zkousky_zaci.PPV,
						zkousky_zaci.PJ_A,
						zkousky_zaci.PJ_B
                		FROM matrika JOIN zkousky_zaci
                		ON matrika.GUID=zkousky_zaci.GUID
                		WHERE zkousky_zaci.ZKid="'.$id.'"
                		ORDER BY jmeno';
                $result=mysqli_query ($db_connect, $sql);
            ?>

			<? while ($radek=mysqli_fetch_array ($result)): ?>
                <? $narozen=date('d.m.Y', strtotime($radek['narozen']));?>
   
				<tr>
					<td><? echo $radek['UID']?></td>
					<td><? echo strtoupper_all($radek ['prijmeni']).' '.$radek ['jmeno']?></td>
					<td><? echo skupina_kod('matrika', $radek['GUID'])?></td>
					<td><? echo $narozen?></td>
					<td><? echo $radek['adresa1'].' '.$radek['adresa2'].' '.$radek['psc']?></td>
					<td><? echo $radek['rp']?></td>
                    <td><? if ($radek['PPV']==1):?><!--<span class="glyphicon glyphicon-ok"></span>--><? endif?><? if ($radek['PPV']==0):?><span class="glyphicon glyphicon-remove"></span><? endif?></td>
                    <td><? if ($radek['PJ_A']==1):?><!--<span class="glyphicon glyphicon-ok"></span>--><? endif?><? if ($radek['PJ_A']==0):?><span class="glyphicon glyphicon-remove"></span><? endif?></td>
                    <td><? if ($radek['PJ_B']==1):?><!--<span class="glyphicon glyphicon-ok"></span>--><? endif?><? if ($radek['PJ_B']==0):?><span class="glyphicon glyphicon-remove"></span><? endif?></td>
					<td><? echo $arrayUser[$radek['ucitel_A']]['inicial'].'&nbsp;'.$arrayUser[$radek['ucitel_B']]['inicial']?></td>
                    <td><? echo $radek['poznamka_ext']?></td>
				</tr>
            <? endwhile?>
            
		</table>
	</div>
	<br /><br />
	<div class="well">
	    <a href="?menu=zkousky" class="btn btn-info"><span class="glyphicon glyphicon-backward"></span>&nbsp;Zpět</a>
		<button type="button" class="btn btn-info" onClick="window.print()"><span class="glyphicon glyphicon-print"></span>&nbsp;Tisk</button>
	    <button type="button" value="<? echo $_GET['id']?>" class="button_xml_seznam btn btn-info" ><span class="glyphicon glyphicon-file"></span>&nbsp;Export do XML</button>
        
	</div>
</div>
