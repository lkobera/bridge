<?
	/*SELECT Z MATRIKY*/
	$sql='SELECT';
	$sql=$sql.' * FROM matrika';
	$sql=$sql.' WHERE GUID="'.$_GET['GUID'].'"';
	$result = mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Evidence žáků | </small><br />Třídni kniha</h1>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) 
				OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>        
	</div>
    
    <table class="table table-bordered">
    	<tr class="active">
        	<th>Ev.č.</th>
            <th>Příjmení a jméno</th>
            <th>Druh výcviku</th>
            <th>Skupina</th>
			<th>Zahájení</th>
        </tr>
        <tr>
        	<td><?=$radek['UID']?></td>
            <td><?=strtoupper_all($radek['prijmeni']).' '.$radek['jmeno']?></td>
            <td>ISP</td>
            <td><?=skupina_kod('matrika',$_GET['GUID'])?></td>
        	<td><?=date('d.m.Y', strtotime($radek['zahajen']))?></td>            
        </tr>
    </table>
    

    <table class="table table-bordered">
    	<tr>
        	<th class="active">PPV</th>
            <td>            
            	<? for ($x=0; $x<=4; $x++):?>
					<? if ($radek['request']==0) 
						if (date('Y-m-d', strtotime($radek['zahajen'].'+'.(floor($x/2)*3).' day')) < date('Y-m-d')) echo date('d.m.Y', strtotime($radek['zahajen'].'+'.(floor($x/2)*3).' day')).' |'?>
                <? endfor?>
            </td>
        </tr>
    	<tr>
        	<th class="active">TZBJ</th>
            <td>
            	<? for ($x=5; $x<=7; $x++):?>       
                	<? if ($radek['request']==0) 
						if (date('Y-m-d', strtotime($radek['zahajen'].'+'.(floor($x/2)*3).' day')) < date('Y-m-d')) echo date('d.m.Y', strtotime($radek['zahajen'].'+'.(floor($x/2)*3).' day')).' |'?>
                <? endfor?>
            </td>
        </tr>
    	<tr>
        	<th class="active">ZP</th>
            <td>
            	<? if ($radek['request']==0) 
					if (date('Y-m-d', strtotime($radek['zahajen'].'+'.(4*3).' day')) < date('Y-m-d')) echo date('d.m.Y', strtotime($radek['zahajen'].'+'.(4*3).' day')).' |'?>
            </td>
        </tr>
    	<tr>
        	<th class="active">OÚV</th>
            <td>
            	<? if ($radek['request']==0) 
					if (date('Y-m-d', strtotime($radek['zahajen'].'+'.(5*3).' day')) < date('Y-m-d')) echo date('d.m.Y', strtotime($radek['zahajen'].'+'.(5*3).' day')).' |'?>
            </td>
        </tr>
    	<tr>
        	<th class="active">OP</th>
            <td>
            	<? if ($radek['request']==0) 
					if (date('Y-m-d', strtotime($radek['zahajen'].'+'.(5*3).' day')) < date('Y-m-d')) echo date('d.m.Y', strtotime($radek['zahajen'].'+'.(5*3).' day')).' |'?>
            </td>
        </tr>
    	<tr>
        	<th class="active">PV-ZP</th>
            <td>
            	<? for ($x=0; $x<=1; $x++):?>               
                	<? if ($radek['request']==0) 
						if (date('Y-m-d', strtotime($radek['zahajen'].'+'.(6*3).' day')) < date('Y-m-d')) echo date('d.m.Y', strtotime($radek['zahajen'].'+'.(6*3).' day')).' |'?>
                <? endfor?>
            </td>
        </tr>        
    
    	<? 	
			$sql= 'SELECT * FROM tk_pj WHERE GUID="'.$_GET['GUID'].'" AND skupina="A"';
			$result = mysqli_query ($db_connect, $sql);
		?>	
    	<tr>
        	<th class="active">PV-ŘV A</th>
            <td>
            	<? 	$ia=0;
					while ($rsPVRV=mysqli_fetch_array($result)):?>
                		<?=date('d.m.Y', strtotime($rsPVRV['datum'])).' |'?>
                        <?
							
							if ($ia<=1) {
								$ia++;
								$PVUV_A[$ia]=date('d.m.Y', strtotime($rsPVRV['datum']));							
							}
                        ?>                    	
                <? endwhile?>
            </td>
        </tr>
        <? 	
			$sql= 'SELECT * FROM tk_pj WHERE GUID="'.$_GET['GUID'].'" AND skupina="B"';
			$result = mysqli_query ($db_connect, $sql);
		?>
    	<tr>
        	<th class="active">PV-ŘV B</th>
            <td>
            	<? 	$ib=0;
					while ($rsPVRV=mysqli_fetch_array($result)):?>
                	<?=date('d.m.Y', strtotime($rsPVRV['datum'])).' |'?>
                        <?
							if ($ib<=1) {
								$ib++;
								$PVUV_B[$ib]=date('d.m.Y', strtotime($rsPVRV['datum']));					
							}
                        ?>                        
                <? endwhile?>
            </td>
        </tr>
        <tr>
        	<th class="active">PV-ÚV A</th>
            <td>
            	<? if ($ia>0):?>
                	<? for ($x=1; $x<=$ia; $x++):?>
                    	<? if ($radek['request']==0) echo $PVUV_A[$x].' |'?>
                    <? endfor?>
                <? endif?>                                
            </td>
        </tr>
        <tr>
        	<th class="active">PV-ÚV B</th>
            <td>
            	<? if ($ib>0):?>
                	<? for ($x=1; $x<=$ib; $x++):?>
                    	<? if ($radek['request']==0) echo $PVUV_B[$x].' |'?>
                    <? endfor?>
                <? endif?>
            </td>
        </tr>
        <tr class="active">
        	<th>Pozn.</th>
            <td><?=$radek['poznamka_ext']?></td>
        </tr>
    </table>
</div>

<div class="well">
    <a href="/?menu=karta_zaka&GUID=<?=$_GET['GUID']?>&source=matrika" class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span>&nbsp;Zpět</a>
    <button type="button" class="btn btn-primary" onClick="window.print()"><span class="glyphicon glyphicon-print"></span>&nbsp;Tisk</button>    
</div>