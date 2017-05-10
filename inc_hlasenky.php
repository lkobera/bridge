<?

/*SELECT HLASENEK*/
$sql='SELECT 
		slozky.*, uzivatel.jmeno
		FROM slozky
		LEFT JOIN uzivatel
		ON slozky.autor=uzivatel.UZid
		WHERE slozky.loc="'.$_SESSION['loc'].'"
		AND status>=0
		ORDER BY id DESC';


	$result=mysqli_query ($db_connect, $sql);

?>


<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Admin</small><br>Hlášenky výcviku</h1>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) 
				OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>        
	</div>
	<div class="col-sm-6">    
        <table class="table table-condensed table-bordered table-hover">
            <? while ($radek=mysqli_fetch_array($result)):?>        
                <tr id="<? echo $radek['folderID']?>" class="<? if ($radek['status']==0) echo 'warning'; else echo 'info'?>">
                    <td><h4><small><? echo $radek['loc']?></small>&nbsp;<br><? echo $radek['id']?></h4></td>
                    <td>
                    	<? if($radek['status']==0):?>
                        	<label>zahájení</label><input id="hlasenkadatum_<? echo $radek['folderID']?>" class="hlasenkadatum form-control jqdatepicker" type="text">
                        <? endif?>
                        <? if($radek['status']==1):?>
	                    	zahájení: <? echo date('d.m.Y', strtotime($radek['zahajeni']))?><br>autor:&nbsp;<? echo $arrayUser[$radek['autor']]['jmeno']?>
						<? endif?>
					</td>
                    <td><br>
                    	<? if($radek['status']==0):?>
							<button id="button_hlasenka_ok_<? echo $radek['folderID']?>" class="button_hlasenka_ok btn btn-default disabled"><span class="glyphicon glyphicon-ok"></span></button>
						<? endif?>
                    	<a href="?menu=hlasenky_detail&folderID=<? echo $radek['folderID']?>" class="btn btn-info"><span class="glyphicon glyphicon-list"></span></a>
					</td>
                </tr>
                <tr><td colspan="3"></td></tr>
                
                
            
            <? endwhile?>
        </table>
        <input type="hidden" id="autor" value="<? echo $_COOKIE['userID']?>">
	</div>
</div>
