<?
/*SELECT ze ZKOUSKY_TERMINY*/
	$sql='SELECT * FROM zkousky_terminy WHERE id="'.$_GET['id'].'"';
	$result=mysqli_query ($db_connect, $sql);		
	$radek=mysqli_fetch_array ($result);
?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Zkoušky | Kalendář zkoušek </small><br>Editace zkoušky</h1>
        <? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) 
				OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>
	</div>
    <div class="col-md-6">
	<form action="?menu=zkousky&action=update&id=<? echo $_GET['id']?>" method="post">
        <table class="table table-bordered">
            <tr class="info">
                <th>Zkouška</th>
                <th><? echo $radek['loc']?></th>
            </tr>
            <tr>
                <td>Datum</td>
                <td><input name="datum" class="form-control jqdatepicker" type="text" value="<? echo date ('d.m.Y', strtotime($radek['datum']))?>"></td>
            </tr>
            <tr>
                <td>Čas</td>
                <td><input name="cas" class="form-control jqtimepicker" type="text" value="<? echo $radek['cas']?>"></td>
            </tr>
            <tr>
                <td>Adresa</td>
                <td>
                	<div class="input-group m-b">
                		<select name="zkousky_venue" class="form-control">
		                	<? zkousky_venue($_SESSION['loc'])?>
						</select>
                        <span class="input-group-btn">
	                    	<a href="?menu=registr_venue" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
	                    </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>PPV</td>
                <td><input name="PPV" class="form-control" type="text" value="<? echo $radek['PPV']?>"></td>
            </tr>
            <tr>
                <td>PJ A</td>
                <td><input name="PJ_A" class="form-control" type="text" value="<? echo $radek['PJ_A']?>"></td>
            </tr>
            <tr>
                <td>PJ B</td>
                <td><input name="PJ_B" class="form-control" type="text" value="<? echo $radek['PJ_B']?>"></td>
            </tr>
        </table>
	
        
         <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;Uložit</button>
         <a href="?menu=zkousky" class="btn btn-primary"><span class="glyphicon glyphicon-remove"></span>&nbsp;Zrušit změny</a>
         <a href="?menu=zkousky&action=delete&id=<? echo $_GET['id']?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>&nbsp;Smazat termín</a>
	</form>
    </div>
</div>