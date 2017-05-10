<?
	session_start();
	include_once 'inc_db_connect.php';
	include_once 'inc_library.php';
?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small>Nástroje | Měsíční výkaz práce</small><br />Vyúčtování</h1>
        <? if (($_SESSION['ucitel']==NULL) OR ($_SESSION['ucitel']==0)) {echo '<p>Chyba. Nebyl vybrán žádný instruktor!</p>'; die;}?>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>        
	</div>
    
    <?	/*je vyuctovani zamcene?*/
		$sql='SELECT invoiceUID, odvody, locked FROM uzivatel_invoice WHERE UZid="'.$_SESSION['ucitel'].'" AND invoiceID="'.$_GET['invoiceID'].'"';
		$result=mysqli_query($db_connect, $sql);
       	$radek=mysqli_fetch_array($result);
		$locked=$radek['locked'];
		$odvody=$radek['odvody'];
		$invoiceUID=$radek['invoiceUID'];

		
		
		/****************odemncene vyuctovani se sestavuje dynamicky z aktualnich dat******************/
		if ($locked==false) {
			$sql='	
						SELECT 
							invoice.*,
							CASE
								WHEN tA.vycvik=0 THEN ""
								ELSE tA.vycvik
							END AS vycvik_A, 
							CASE
								WHEN tB.vycvik=0 THEN ""
								ELSE tB.vycvik
							END AS vycvik_B,
							CASE
								WHEN zkA.zk=0 THEN ""
								ELSE zkA.zk
							END AS zk_A,
							CASE
								WHEN zkB.zk=0 THEN ""
								ELSE zkB.zk
							END AS zk_B,
							CASE
								WHEN repA.zk=0 THEN ""
								ELSE repA.zk
							END AS rep_A,
							CASE
								WHEN repB.zk=0 THEN ""
								ELSE repB.zk
							END AS rep_B
	
						
						FROM
							(SELECT 
								invoiceUID, locked
								FROM uzivatel_invoice 
								WHERE UZid="'.$_SESSION['ucitel'].'"
								AND invoiceID="'.$_GET['invoiceID'].'") 
								AS invoice
							JOIN
				
							(SELECT 
								COUNT(GUID) as vycvik, 
								IFNULL(ucitel,'.$_SESSION['ucitel'].') AS ucitel
								FROM tk_pj  
								WHERE ucitel="'.$_SESSION['ucitel'].'" 
								AND skupina="A" 
								AND datum>="'.date('Y-m-d', strtotime('01.'.$_GET['invoiceID'])).'" AND datum<="'.date('Y-m-t', strtotime('01.'.$_GET['invoiceID'])).'")
								AS tA
								
							JOIN
	  
							(SELECT 
								COUNT(GUID) as vycvik, 
								IFNULL(ucitel,'.$_SESSION['ucitel'].') AS ucitel
								FROM tk_pj  
								WHERE ucitel="'.$_SESSION['ucitel'].'" 
								AND skupina="B" 
								AND datum>="'.date('Y-m-d', strtotime('01.'.$_GET['invoiceID'])).'" AND datum<="'.date('Y-m-t', strtotime('01.'.$_GET['invoiceID'])).'")
								AS tB
								
							ON tA.ucitel=tB.ucitel
							
							JOIN
							
							(SELECT 
								COUNT(zkousky_zaci.ID) AS zk,
								IFNULL(zkousky_zaci.ucitel_A,'.$_SESSION['ucitel'].') AS ucitel
								FROM zkousky_zaci  JOIN zkousky_terminy 
								ON zkousky_zaci.ZKid=zkousky_terminy.id 
								WHERE zkousky_zaci.repro=0 
								AND zkousky_zaci.ucitel_A="'.$_SESSION['ucitel'].'" 
								AND zkousky_terminy.datum>="'.date('Y-m-d', strtotime('01.'.$_GET['invoiceID'])).'" 
								AND zkousky_terminy.datum<="'.date('Y-m-t', strtotime('01.'.$_GET['invoiceID'])).'")
								AS zkA
	  
							ON tA.ucitel=zkA.ucitel
							
							JOIN
							
							(SELECT 
								COUNT(zkousky_zaci.ID) AS zk,
								IFNULL(zkousky_zaci.ucitel_B,'.$_SESSION['ucitel'].') AS ucitel
								FROM zkousky_zaci  JOIN zkousky_terminy 
								ON zkousky_zaci.ZKid=zkousky_terminy.id 
								WHERE zkousky_zaci.repro=0 
								AND zkousky_zaci.ucitel_B="'.$_SESSION['ucitel'].'" 
								AND zkousky_terminy.datum>="'.date('Y-m-d', strtotime('01.'.$_GET['invoiceID'])).'" 
								AND zkousky_terminy.datum<="'.date('Y-m-t', strtotime('01.'.$_GET['invoiceID'])).'")
								AS zkB
	  
							ON tA.ucitel=zkB.ucitel
							
							
							JOIN
							
							(SELECT 
								COUNT(zkousky_zaci.ID) AS zk,
								IFNULL(zkousky_zaci.ucitel_A,'.$_SESSION['ucitel'].') AS ucitel
								FROM zkousky_zaci  JOIN zkousky_terminy 
								ON zkousky_zaci.ZKid=zkousky_terminy.id 
								WHERE zkousky_zaci.repro=1 
								AND zkousky_zaci.ucitel_A="'.$_SESSION['ucitel'].'" 
								AND zkousky_terminy.datum>="'.date('Y-m-d', strtotime('01.'.$_GET['invoiceID'])).'" 
								AND zkousky_terminy.datum<="'.date('Y-m-t', strtotime('01.'.$_GET['invoiceID'])).'")
								AS repA
	  
							ON tA.ucitel=repA.ucitel
							
							JOIN
							
							(SELECT 
								COUNT(zkousky_zaci.ID) AS zk,
								IFNULL(zkousky_zaci.ucitel_B,'.$_SESSION['ucitel'].') AS ucitel
								FROM zkousky_zaci  JOIN zkousky_terminy 
								ON zkousky_zaci.ZKid=zkousky_terminy.id 
								WHERE zkousky_zaci.repro=1 
								AND zkousky_zaci.ucitel_B="'.$_SESSION['ucitel'].'" 
								AND zkousky_terminy.datum>="'.date('Y-m-d', strtotime('01.'.$_GET['invoiceID'])).'" 
								AND zkousky_terminy.datum<="'.date('Y-m-t', strtotime('01.'.$_GET['invoiceID'])).'")
								AS repB
	  
							ON tA.ucitel=repB.ucitel
					';
				$result=mysqli_query($db_connect, $sql);
				$radek=mysqli_fetch_array($result);
		/********************PAUSAL***********************/
				$pausal=array('A'=>0,'B'=>0);
	
				$sql_p='	SELECT
								pausal AS A
								FROM uzivatel_pausal 
								WHERE UZid="'.$_SESSION['ucitel'].'"
								AND vykon="A"
								AND datum_od<="'.date('Y-m-d', strtotime('01.'.$_GET['invoiceID'])).'"
								ORDER BY datum_od DESC
								LIMIT 1';
				$result_p=mysqli_query($db_connect, $sql_p);
				$radek_p=mysqli_fetch_array($result_p);
				if ($radek_p['A']>0) $pausal['A']=$radek_p['A'];
				$sql_p='	SELECT
								pausal AS B
								FROM uzivatel_pausal 
								WHERE UZid="'.$_SESSION['ucitel'].'"
								AND vykon="B"
								AND datum_od<="'.date('Y-m-d', strtotime('01.'.$_GET['invoiceID'])).'"
								ORDER BY datum_od DESC
								LIMIT 1';
			$result_p=mysqli_query($db_connect, $sql_p);
			$radek_p=mysqli_fetch_array($result_p);
			if ($radek_p['B']>0) $pausal['B']=$radek_p['B'];
			
		
		}
		
	?>
    <div class="col-md-6">
	    <table class="table table-bordered">
	    	<tr class="bg-info">
	        	<th colspan="3"><h4><? if ($locked==true):?><span class="glyphicon glyphicon-lock"></span>&nbsp;<? endif?><?=$arrayUser[$_SESSION['ucitel']]['jmeno']?></h4></th>
	            <th colspan="2"><h4 class="text-right"><?=$_GET['invoiceID']?></h4></th>
	        </tr>
            <tr class="bg-warning"><th width="35%">Popis</th><th>Sazba</th><th width="20%">Počet</th><th>Částka</th><th></th></tr>
            
            <? if($radek['vycvik_A']>0):?><tr><td>Výcvik A</td><td class="pausal_A"><?=$pausal['A']?></td><td id="vycvik_A"><?=$radek['vycvik_A']?></td><td><?=ceil($A=$pausal['A']*$radek['vycvik_A'])?></td></tr><? endif?>
            <? if($radek['zk_A']>0):?><tr><td>Zkouška A</td><td class="pausal_A"><?=$pausal['A']?></td><td id="zk_A"><?=$radek['zk_A']?></td><td><?=ceil($zkA=$pausal['A']*$radek['zk_A'])?></td></tr><? endif?>
            <? if($radek['rep_A']>0):?><tr><td>Repro A</td><td class="pausal_A"><?=$pausal['A']?></td><td id="rep_A"><?=$radek['rep_A']?></td><td><?=ceil($repA=$pausal['A']*$radek['rep_A'])?></td></tr><? endif?>
            <? if($radek['vycvik_B']>0):?><tr><td>Výcvik B</td><td class="pausal_B"><?=$pausal['B']?></td><td id="vycvik_B"><?=$radek['vycvik_B']?></td><td><?=ceil($B=$pausal['B']*$radek['vycvik_B'])?></td></tr><? endif?>
            <? if($radek['zk_B']>0):?><tr><td>Zkouška B</td><td class="pausal_B"><?=$pausal['B']?></td><td id="zk_B"><?=$radek['zk_B']?></td><td><?=ceil($zkB=$pausal['B']*$radek['zk_B'])?></td></tr><? endif?>
			<? if($radek['rep_B']>0):?><tr><td>Repro B</td><td class="pausal_B"><?=$pausal['B']?></td><td id="rep_B"><?=$radek['rep_B']?></td><td><?=ceil($repB=$pausal['B']*$radek['rep_B'])?></td></tr><? endif?>
            
            <?
				$sum=$A+$zkA+$repA+$B+$zkB+$repB;
			
			
				$sql='	SELECT
						ID, popis, cena_jednotka, pocet
						FROM uzivatel_invoice_detail
						WHERE invoiceUID="'.$invoiceUID.'"
				';
	           	$result=mysqli_query($db_connect, $sql);
            ?>
            
            <? while($radek=mysqli_fetch_array($result)):?>
            	<tr>
                	<td><?=$radek['popis']?></td>
                    <td><?=$radek['cena_jednotka']?></td>
                    <td><?=$radek['pocet']?></td>
                    <td><?=$custom=ceil($radek['cena_jednotka']*$radek['pocet'])?></td>
                    <td><? if($locked==false):?><button class="button_invoice_custom_delete btn btn-danger" value="<?=$radek['ID']?>"><span class="glyphicon glyphicon-trash"></span></button><? endif?></td>
                </tr>
                <? $sum=$sum+$custom?>
            <? endwhile?>
            
           <? if($locked==false): ?>
	            <tr>
	            	<td><select id="ucitel_vykon" class="form-control"></select></td>
	                <td><input id="ucitel_vykon_cena_jednotka" class="form-control" type="text"></td>
	                <td><input id="ucitel_vykon_pocet" class="form-control" type="number"></td>
	                <td id="ucitel_vykon_cena"></td>
	                <td><button id="button_invoice_custom_add" value="<?=$invoiceUID?>" class="btn btn-default" disabled><span class="glyphicon glyphicon-plus"></span></button></td>
	            </tr>
			<? endif?>
            <tr><td colspan="2"></td><td class="bg-success"><h4>&sum;</h4></td><td class="bg-success"><h4 id="hrubamzda"><?=$sum?></h4></td></tr>
            <? if ($arrayUser[$_SESSION['ucitel']]['pravo-kontraktor']==0):?>
            	<? $sum=$sum-$odvody?>
	            <tr><td colspan="2"></td><td class="active"><h5>Odvody</h5></td><td class="active"><input id="odvody" type="text" class="form-control" value="<?=$odvody?>" <? if($locked==true) echo 'disabled'?>></td></tr>
	            <tr><td colspan="2"></td><td class="bg-success"><h4>K výplatě</h4></td><td class="bg-success"><h4 id="mzda"><?=$sum?></h4></td></tr>
            <? endif?>
            <? if (($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1) AND ($locked==false)):?><tr><td colspan="3"><td><button id="ucitel_invoice_lock" class="btn btn-warning" value="<?=$invoiceUID?>"><span class="glyphicon glyphicon-lock"></span>&nbsp;Uzávěrka</button></td></tr><? endif?>
	    </table>
	</div>
    <div class="clearfix"></div>
   	<div class="well">
    	<a href="/?menu=vykaz" class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span>&nbsp;Zpět</a>
    	<button class="btn btn-primary" onClick="window.print()"><span class="glyphicon glyphicon-print"></span>&nbsp;Tisk</button>
	</div>        
</div>



<script>
    $.getJSON('ajax/ucitel_vykon.json', function(json) {
        $.each(json, function() {
			$('#ucitel_vykon').append('<option value="'+ this.option +'">' + this.option + '</option>');
        });
    });
</script>