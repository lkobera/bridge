<?
	if (isset($_POST['add_venue'])) {
		$sql='	INSERT INTO zkousky_venue 
					(loc, adresa)
				VALUES
					("'.$_SESSION['loc'].'", "'.$_POST['add_venue'].'")
				';
		mysqli_query ($db_connect, $sql);
	}
?>




<?
	$sql='SELECT * FROM zkousky_venue WHERE loc="'.$_SESSION['loc'].'"';
	$result=mysqli_query ($db_connect, $sql);
?>


<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Zkoušky</small><br>Registr zkušebních míst</h1>
        <? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) 
				OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>
	</div>
    <div class="row">
        <div class="col-xs-6">
            <table class="table table-hover">
                <? while ($radek=mysqli_fetch_array($result)):?>
                    <tr>
                        <td><?=$radek['adresa']?></td>
                        <td></td>                    
                    </tr>
                <? endwhile?>
                <tr>
                    <form action="" method="POST">
                        <td><input name="add_venue" class="form-control" type="text" /></td>
                        <td><button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button></td>
                    </form>
                </tr>
            </table>
        </div>
	</div>
    <a href="?menu=zkousky" class="btn btn-primary"><span class=" 	glyphicon glyphicon-backward"></span>&nbsp;Zpět do kalendáře</a>
</div>