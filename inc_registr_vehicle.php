<!-- Tady se spousti tablesort + tablefilter -->
<script>
	$(function(){
		$('.tablesorter').tablesorter({
			widgets        : ['filter','stickyHeaders'],
			
			usNumberFormat : false,
			sortReset      : true, 
			sortRestart    : true
		});
	});
</script>
<?
	if (isset($_POST['update'])) {
		$sql='UPDATE vehicle SET 
		rz="'.$_POST['rz'].'",
		kat="'.$_POST['kat'].'",
		znacka="'.$_POST['znacka'].'",
		typ="'.$_POST['typ'].'",
		vin="'.$_POST['vin'].'",
		barva="'.$_POST['barva'].'",
		vlastnik="'.$_POST['mail'].'",
		adresa="'.$_POST['adresa'].'",
		rcic="'.$_POST['rcic'].'"		
		WHERE GUID="'.$_POST['GUID'].'"';

		echo $sql;
		mysqli_query ($db_connect, $sql);
	}	
?>


<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Supervisor</small><br />Registr vozidel</h1>
        <? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>
	</div>
    
    <br clear=all><br>
    <div class="table-responsive">
        <table class="table table-responsive table-hover tablesorter">
            <thead>
				<tr><td class="nosort"><br><br></td></tr>
                <tr>
                    <th class="bg-info filter-false"></th>                    
                    <th class="bg-info filter-false">RZ</th>
                    <th class="bg-info filter-false">Kat.</th>
                    <th class="bg-info filter-false">Značka</th>
                    <th class="bg-info filter-false">Typ</th>
                    <th class="bg-info filter-false">Barva</th>
                    <th class="bg-info filter-false">Aktivní</th>
                </tr>
            </thead>
            <tbody>
            	<?
					$sql='SELECT * FROM vehicle';
					$result=mysqli_query ($db_connect, $sql);					
                ?>
                
                <? while ($rsX=mysqli_fetch_array($result)):?>
                    <tr class="<? if($rsX['aktivni']==0) echo 'active'?>">
                        <td><a href="/?menu=registr_vehicle_edit&GUID=<? echo $rsX['GUID']?>" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a></td>                       
                        <td><? echo $rsX['rz']?></td>
                        <td><? echo $rsX['kat']?></td>
                        <td><? echo $rsX['znacka']?></td>
                        <td><? echo $rsX['typ']?></td>
                        <td><? echo $rsX['barva']?></td>
                        <td><input id="aktivni" type="checkbox" class="switch_prava form-control" <? if($rsX['aktivni']) echo 'checked'?>></td>
                    </tr>            
                <? endwhile?>
            </tbody>
            <tr>
                <td><button class="button_new_vehicle btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button></td>
                <td><input id="new_vehicle_rz" type="text" class="form-control"/></td>
            </tr>
        </table>
	</div>
</div>