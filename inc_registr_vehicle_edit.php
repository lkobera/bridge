<?
	/*SELECT Z MATRIKY*/
	$sql='SELECT * FROM vehicle WHERE GUID="'.$_GET['GUID'].'"';
	$result=mysqli_query($db_connect, $sql);
	$radek=mysqli_fetch_array($result);

?>
<div class="container-fluid">
	<h1><small>Supervisor | Registr vozidel</small><br />Editace vozidla</h1>
<div class="well">   
	<form action="/?menu=registr_vehicle" method="POST">
	<input name="GUID" type="hidden" value="<? echo $_GET['GUID']?>">
    <div class="panel panel-primary">
	    <div class="panel panel-heading"><strong>Vozidlo</strong></div>
        <div class="panel panel-body">
        
            <div class="row form-group">
                <div class="col-sm-3"><label class="text-danger">RZ</label><input name="rz" class="form-control" type="text" value="<? echo $radek['rz']?>" /></div>
                <div class="col-sm-1">
                	<label class="text-danger">Kategorie</label>
                    <select name="kat" class="form-control"> 
                    	<option value="A" <? if ($radek['kat']=='A') echo 'selected'?> />A</option>
                        <option value="B" <? if ($radek['kat']=='B') echo 'selected'?> />B</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-3"><label class="text-danger">Značka</label><input name="znacka" class="form-control"  type="text" value="<? echo $radek['znacka']?>" /></div>
                <div class="col-sm-4"><label class="text-danger">Typ</label><input name="typ" class="form-control"  type="text" value="<? echo $radek['typ']?>" /></div>
                <div class="col-sm-4"><label class="text-danger">VIN</label><input name="vin" class="form-control" type="text" value="<? echo $radek['vin']?>" /></div>
                <div class="col-sm-1"><label class="text-danger">Barva</label><input name="barva" class="form-control" type="text" value="<? echo $radek['barva']?>" /></div>              
            </div>
        </div>            
	</div>


    <div class="panel panel-warning">        
        <div class="panel panel-heading"><strong>Vlastník</strong></div>
        <div class="panel panel-body">
            <div class="row form-group">
                <div class="col-sm-3"><label class="text-danger">Jméno</label><input name="vlastnik" class="form-control" type="text" value="<? echo $radek['vlastnik']?>" /></div>
                <div class="col-sm-6"><label class="text-danger">Adresa</label><input name="adresa" class="form-control" type="text" value="<? echo $radek['adresa']?>" /></div>
                <div class="col-sm-3"><label class="text-danger">RČ/IČ</label><input name="rcic" class="form-control" type="text" value="<? echo $radek['rcic']?>" /></div>
            </div>
        </div>
    </div>
      
	
    	<button name="update" type="submit" value="1" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;Uložit</button>
        <a href="?menu=registr_vehicle" class="btn btn-primary"><span class="glyphicon glyphicon-remove"></span>&nbsp;Zrušit změny</a>
    </form>
    </div>
	
   
</div>