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

<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Supervisor</small><br />Reporting</h1>
        <? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>
	</div>
 
    <div class="btn-group">
      	<button type="button" class="btn btn-primary">...</button>
      	<div class="btn-group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Zkoušky <span class="caret"></span></button>
        	<ul class="dropdown-menu" role="menu">
          		<li><a href="?menu=reporting&page=ppv">PPV</a></li>
	        </ul>
    	</div>
    </div>   
<br><br>


<?	switch ($_GET['page']) {
		case 'ppv':?>
       	
        	<form class="form-inline" action="index.php?menu=reporting&page=ppv" method="get">
            	<input name="datumod" class="jqdatepicker form-control" type="date" value="<?=$_GET['datumod']?>">
                <input name="datumdo" class="jqdatepicker form-control" type="date" value="<?=$_GET['datumdo']?>">
                <input name="menu" value="reporting" type="hidden">
                <input name="page" value="ppv" type="hidden">
                <button class="btn btn-default" type="submit">Hledej</button>
            </form>
            <br><br>
        	<? 
				if((!isset($_GET['datumod'])) OR  $_GET['datumod']=='') $_GET['datumod']='1.1.1970';
				if((!isset($_GET['datumdo'])) OR $_GET['datumdo']=='') $_GET['datumdo']='1.1.2500';
				$sql='
					SELECT
						m.GUID,
						m.jmeno,
						m.prijmeni,
						zkt.datum,
						zkt.loc,
						zkt.id,
						m.ucitel_A,
						m.ucitel_B
						
						FROM matrika m
						JOIN zkousky_zaci zkz
						ON m.GUID=zkz.GUID
						
						JOIN zkousky_terminy zkt
						ON zkz.ZKid=zkt.id
						WHERE 
							zkz.PPV="1" 
							AND zkt.datum>="'.date('Y-m-d', strtotime($_GET['datumod'])).'"
							AND zkt.datum<="'.date('Y-m-d', strtotime($_GET['datumdo'])).'"
						
						
					
					
					';
				
				$result = mysqli_query ($db_connect, $sql);				
			
			
			?>
			<table class="table tablesorter">
            	<thead>
                	<tr class="bg-info">
                    	<th class="nosort filter-false"></th>
                    	<th class="sort">Datum</th>
                        <th class="sort">Loc</th>
                    	<th class="sort">Instruktor A</th>
                        <th class="sort">Instruktor B</th>
                        <th class="sort">Příjmení a jméno</th>
                        <th class="sort">Skupina</th>
                    </tr>
                </thead>
                <tbody>
                	<? while ($radek = mysqli_fetch_array ($result)):?>
                	<tr>
                    	<td><a href="?menu=zkousky_seznam&id=<?=$radek['id']?>" class="btn btn-info"><span class="glyphicon glyphicon-search"></span></a></td>
                    	<td><?=date('d.m.Y', strtotime($radek['datum']))?></td>
                        <td><?=$radek['loc']?></td>
                        <td><? echo $arrayUser[$radek['ucitel_A']]['jmeno']?></td>
                        <td><? echo $radek['ucitel_B'].' '.$arrayUser[$radek['ucitel_B']]['jmeno']?></td>
                        <td><?=$radek['prijmeni'].' '.$radek['jmeno']?></td>
                        <td><?=skupina_kod('matrika',$radek['GUID'])?></td>
                    </tr>
                    <? endwhile?>
                </tbody>
            </table>

	
	
<? }?>
	







    
</div>