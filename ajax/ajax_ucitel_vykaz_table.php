<?
	session_start();
	include_once $_GET['inc_path'].'inc_db_connect.php';
	include_once $_GET['inc_path'].'inc_library.php';
	
	$sql='SELECT zahajen FROM uzivatel WHERE UZid="'.$_SESSION['ucitel'].'"';
	$result=mysqli_query($db_connect,$sql);
	$zahajen=mysqli_fetch_array($result);
	$zahajen=date('Y-m-01', strtotime($zahajen['zahajen']));
	$mesic=date('Y-m-01');
	
	if (isset($_GET['open'])) $detail=$_GET['open']; else $detail='';

?>
<!-- Tady se spousti tablesort + tablefilter -->
<script>
	$(function(){
		$('.tablesorter').tablesorter({
			widgets        : ['stickyHeaders'],
		});
	});
</script>
<table class="tablesorter table table-bordered table-responsive table-striped table-condensed">
	<thead>
    	<tr>
     		<td colspan="2" class="nosort"></th>
			<th class="nosort active" colspan="3"><h4>A</h4></th>
	        <th class="nosort active" colspan="3"><h4>B</h4></th>
            <td class="nosort bg-warning"></td>
    	<tr>
		<tr class="bg-info">
	        <th class="nosort"></th>
	        <th class="nosort">Datum</th>
    	    <th class="nosort">Výc. A</th>
	        <th class="nosort">Zk. A</th>
	        <th class="nosort">Rep. A</th>
	        <th class="nosort">Výc. B</th>
	        <th class="nosort">Zk. B</th>
	        <th class="nosort">Rep. B</th>
            <th class="nosort bg-warning">Vyúčtování</th>
	    </tr>
    </thead>
    <? 
  
    if (($_SESSION['ucitel']==NULL) OR ($_SESSION['ucitel']==0)) {echo '<p>Chyba. Nebyl vybrán žádný instruktor!</p>'; die;}
    /*CYKLUS VYKRESLOVANI KALENDARE*/
    else while($mesic>=$zahajen):?>
        <? /*SELECT SOUCTU HODIN PO MESICI*/
            $sql_m='	
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
							COUNT(invoiceID) as is_invoice,  IFNULL(locked,0) AS locked_invoice
							FROM uzivatel_invoice 
							WHERE UZid="'.$_SESSION['ucitel'].'"
							AND invoiceID="'.date('m.Y', strtotime($mesic)).'") 
							AS invoice
						JOIN
            
                        (SELECT 
                            COUNT(GUID) as vycvik, 
                            IFNULL(ucitel,'.$_SESSION['ucitel'].') AS ucitel
                            FROM tk_pj  
                            WHERE ucitel="'.$_SESSION['ucitel'].'" 
                            AND skupina="A" 
                            AND datum>="'.$mesic.'" AND datum<="'.date('Y-m-t', strtotime($mesic)).'")
                            AS tA
                            
                        JOIN
  
                        (SELECT 
                            COUNT(GUID) as vycvik, 
                            IFNULL(ucitel,'.$_SESSION['ucitel'].') AS ucitel
                            FROM tk_pj  
                            WHERE ucitel="'.$_SESSION['ucitel'].'" 
                            AND skupina="B" 
                            AND datum>="'.$mesic.'" AND datum<="'.date('Y-m-t', strtotime($mesic)).'")
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
                            AND zkousky_terminy.datum>="'.$mesic.'" 
                            AND zkousky_terminy.datum<="'.date('Y-m-t', strtotime($mesic)).'")
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
                            AND zkousky_terminy.datum>="'.$mesic.'" 
                            AND zkousky_terminy.datum<="'.date('Y-m-t', strtotime($mesic)).'")
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
                            AND zkousky_terminy.datum>="'.$mesic.'" 
                            AND zkousky_terminy.datum<="'.date('Y-m-t', strtotime($mesic)).'")
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
                            AND zkousky_terminy.datum>="'.$mesic.'" 
                            AND zkousky_terminy.datum<="'.date('Y-m-t', strtotime($mesic)).'")
                            AS repB
  
                        ON tA.ucitel=repB.ucitel
                ';
            $result_m=mysqli_query($db_connect, $sql_m);
            $radek_m=mysqli_fetch_array($result_m);
        ?>
	    <tbody>
	        <tr id="<? echo $row=date('Y-m-01', strtotime($mesic))?>" class="<? if ($row==$detail) echo 'info'?>">
	            <td id="<? echo date('Y-m-01', strtotime($mesic))?>"><button class="button_vykaz_mesic_open btn btn-info"><? if ($row==$detail):?><span class="glyphicon glyphicon-folder-open"></span><? endif?><? if ($row<>$detail):?><span class="glyphicon glyphicon-folder-close"><? endif?></span></button></td>
	            
				<? if ($row==$detail):?><td><h4><? echo $invoiceID=date('m.Y', strtotime($mesic))?></h4></td><? endif?>
	            <? if ($row<>$detail):?><td><? echo $invoiceID=date('m.Y', strtotime($mesic))?></td><? endif?>
	            
	            <td><? echo $radek_m['vycvik_A']?></td>
	            <td><? echo $radek_m['zk_A']?></td>
	            <td><? echo $radek_m['rep_A']?></td>
	            <td><? echo $radek_m['vycvik_B']?></td>
	            <td><? echo $radek_m['zk_B']?></td>
	            <td><? echo $radek_m['rep_B']?></td>
                <td>                	
                	<? if($radek_m['is_invoice']==0):?><button value="<?=$invoiceID?>" class="button_ucitel_new_invoice btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button><? endif?>
                    <? if(($radek_m['is_invoice']>0) AND ($radek_m['locked_invoice']==0)) :?><a href="?menu=ucitel_invoice_edit&invoiceID=<?=$invoiceID?>" class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></a><? endif?>
                    <? if(($radek_m['is_invoice']>0) AND ($radek_m['locked_invoice']==1)) :?><a href="?menu=ucitel_invoice_edit&invoiceID=<?=$invoiceID?>" class="btn btn-default"><span class="glyphicon glyphicon-check"></span></a><? endif?>
                </td>
	        </tr>
		
        


		
        <? if ($row==$detail):?>
        <!-- detail mesice-->
        	<? $day=$row?>
        	<? while ($day<=date('Y-m-t', strtotime($mesic))):?>
				<?
                $sql_d='	
                    SELECT 
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
                            COUNT(GUID) as vycvik, 
                            IFNULL(ucitel,'.$_SESSION['ucitel'].') AS ucitel
                            FROM tk_pj  
                            WHERE ucitel="'.$_SESSION['ucitel'].'" 
                            AND skupina="A" 
                            AND datum="'.$day.'")
                            AS tA
                            
                        JOIN
  
                        (SELECT 
                            COUNT(GUID) as vycvik, 
                            IFNULL(ucitel,'.$_SESSION['ucitel'].') AS ucitel
                            FROM tk_pj  
                            WHERE ucitel="'.$_SESSION['ucitel'].'" 
                            AND skupina="B" 
                            AND datum="'.$day.'")
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
                            AND zkousky_terminy.datum="'.$day.'")
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
                            AND zkousky_terminy.datum="'.$day.'")
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
                            AND zkousky_terminy.datum="'.$day.'")
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
                            AND zkousky_terminy.datum="'.$day.'")
                            AS repB
  
                        ON tA.ucitel=repB.ucitel
                ';
           	 	$result_d=mysqli_query($db_connect, $sql_d);
            	$radek_d=mysqli_fetch_array($result_d);
				?>         
            
              	<tr class="active">
                	<td class="info"></td>
                    <td><? echo date('d.m.Y',strtotime($day))?></td>
                    <td><? echo $radek_d['vycvik_A']?></td>
            		<td><? echo $radek_d['zk_A']?></td>
            		<td><? echo $radek_d['rep_A']?></td>
            		<td><? echo $radek_d['vycvik_B']?></td>
            		<td><? echo $radek_d['zk_B']?></td>
            		<td><? echo $radek_d['rep_B']?></td>
                </tr>
            	
                <? $day=date('Y-m-d', mktime(0, 0, 0, date('m',strtotime($day)), date('d',strtotime($day))+1, date('Y',strtotime($day))))?>
            <? endwhile?>
            
            <td class="info" colspan='8'></td>
        <? endif?>
        
        
        
   
        <? $mesic=date('Y-m-d',mktime(0, 0, 0, date('m',strtotime($mesic))-1,1, date('Y',strtotime($mesic))));?>
    <? endwhile?>
    </tbody>
</table>