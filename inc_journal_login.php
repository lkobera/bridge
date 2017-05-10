<?
	$sql='SELECT * FROM uzivatel_journal ORDER BY timestamp DESC';
	$result=mysqli_query($db_connect,$sql);
?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small><? echo $_SESSION['loc']?> | Supervisor</small><br />Login journal</h1>
        
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if 	($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>
	</div>
    
    <table class="table table-bordered table-responsive table-striped">
    	<tr class="info">
        	<th>Uživatel</th>
            <th>Timestamp</th>
            <th>IP</th>
        </tr>
        <? while ($radek=mysqli_fetch_array($result)):?>
        	<tr>
            	<td><? echo $arrayUser[$radek['UZid']]['jmeno']?></td>
                <td><? echo $radek['timestamp']?></td>
                <td><? echo $radek['IP']?></td>
            </tr>
        <? endwhile?>
    </table>
</div>