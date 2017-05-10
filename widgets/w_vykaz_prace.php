 <?
	$sql='	
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
					IFNULL(ucitel,'.$_COOKIE['userID'].') AS ucitel
					FROM tk_pj  
					WHERE ucitel="'.$_COOKIE['userID'].'" 
					AND skupina="A" 
					AND datum>="'.date('Y-m-01').'" AND datum<="'.date('Y-m-t').'")
					AS tA
					
				JOIN

				(SELECT 
					COUNT(GUID) as vycvik, 
					IFNULL(ucitel,'.$_COOKIE['userID'].') AS ucitel
					FROM tk_pj  
					WHERE ucitel="'.$_COOKIE['userID'].'" 
					AND skupina="B" 
					AND datum>="'.date('Y-m-01').'" AND datum<="'.date('Y-m-t').'")
					AS tB
					
				ON tA.ucitel=tB.ucitel
				
				JOIN
				
				(SELECT 
					COUNT(zkousky_zaci.ID) AS zk,
					IFNULL(zkousky_zaci.ucitel_A,'.$_COOKIE['userID'].') AS ucitel
					FROM zkousky_zaci  JOIN zkousky_terminy 
					ON zkousky_zaci.ZKid=zkousky_terminy.id 
					WHERE zkousky_zaci.repro=0 
					AND zkousky_zaci.ucitel_A="'.$_COOKIE['userID'].'" 
					AND zkousky_terminy.datum>="'.date('Y-m-01').'" 
					AND zkousky_terminy.datum<="'.date('Y-m-t').'")
					AS zkA

				ON tA.ucitel=zkA.ucitel
				
				JOIN
				
				(SELECT 
					COUNT(zkousky_zaci.ID) AS zk,
					IFNULL(zkousky_zaci.ucitel_B,'.$_COOKIE['userID'].') AS ucitel
					FROM zkousky_zaci  JOIN zkousky_terminy 
					ON zkousky_zaci.ZKid=zkousky_terminy.id 
					WHERE zkousky_zaci.repro=0 
					AND zkousky_zaci.ucitel_B="'.$_COOKIE['userID'].'" 
					AND zkousky_terminy.datum>="'.date('Y-m-01').'" 
					AND zkousky_terminy.datum<="'.date('Y-m-t').'")
					AS zkB

				ON tA.ucitel=zkB.ucitel
				
				
				JOIN
				
				(SELECT 
					COUNT(zkousky_zaci.ID) AS zk,
					IFNULL(zkousky_zaci.ucitel_A,'.$_COOKIE['userID'].') AS ucitel
					FROM zkousky_zaci  JOIN zkousky_terminy 
					ON zkousky_zaci.ZKid=zkousky_terminy.id 
					WHERE zkousky_zaci.repro=1 
					AND zkousky_zaci.ucitel_A="'.$_COOKIE['userID'].'" 
					AND zkousky_terminy.datum>="'.date('Y-m-01').'" 
					AND zkousky_terminy.datum<="'.date('Y-m-t').'")
					AS repA

				ON tA.ucitel=repA.ucitel
				
				JOIN
				
				(SELECT 
					COUNT(zkousky_zaci.ID) AS zk,
					IFNULL(zkousky_zaci.ucitel_B,'.$_COOKIE['userID'].') AS ucitel
					FROM zkousky_zaci  JOIN zkousky_terminy 
					ON zkousky_zaci.ZKid=zkousky_terminy.id 
					WHERE zkousky_zaci.repro=1 
					AND zkousky_zaci.ucitel_B="'.$_COOKIE['userID'].'" 
					AND zkousky_terminy.datum>="'.date('Y-m-01').'" 
					AND zkousky_terminy.datum<="'.date('Y-m-t').'")
					AS repB

				ON tA.ucitel=repB.ucitel
		';
	$result=mysqli_query($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
?>

<div class="widget widget-blue">
    <table width="100%">
        <tr><td rowspan="2"><h1><span class="glyphicon glyphicon-cog"></span></h1></td><td><h2 class="text-right">Výkaz práce</h2></td></tr>
        <tr><td><h3 class="text-right"><?=$radek['vycvik_A']+$radek['zk_A']+$radek['rep_A']+$radek['vycvik_B']+$radek['zk_B']+$radek['rep_B']?></h3></td></tr>
    </table>
</div>