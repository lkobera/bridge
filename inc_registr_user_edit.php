<?
	/*SELECT Z MATRIKY*/
	$sql='SELECT uzivatel.*,uzivatel_prava.*  FROM uzivatel JOIN uzivatel_prava ON uzivatel.UZid=uzivatel_prava.UZid WHERE uzivatel.UZid="'.$_GET['UZid'].'"';
	$result=mysqli_query($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
	echo $sql;
	
	/*SELECT PAUSALU*/
	/*A*/
	$sql='SELECT * FROM uzivatel_pausal WHERE UZid="'.$_GET['UZid'].'" AND vykon="A"';
	$result_A=mysqli_query($db_connect, $sql);

	
	/*B*/
	$sql='SELECT * FROM uzivatel_pausal WHERE UZid="'.$_GET['UZid'].'" AND vykon="B"';
	$result_B=mysqli_query($db_connect, $sql);

?>
<div class="container-fluid">
	<h1><small>Supervisor | Registr uživatelů</small><br />Editace uživatele</h1>
<div class="well">   
	<form action="/?menu=registr_user" method="POST">
	<input id="UZid" name="UZid" type="hidden" value="<? echo $_GET['UZid']?>">

    <div class="panel panel-primary">
	    <div class="panel panel-heading"><strong>Osobní údaje</strong></div>
        <div class="panel panel-body">
        
            <div class="row form-group">
                <div class="col-sm-4"><label class="text-danger">Jméno</label><input name="jmeno" class="form-control" type="text" value="<? echo $radek['jmeno']?>" /></div>
                <div class="col-sm-2"><label class="text-danger">Iniciály</label><input name="inicial" class="form-control"  type="text" value="<? echo $radek['inicial']?>" /></div>
                <div class="col-sm-2"><label class="text-danger">Narozen</label><input name="narozen" class="form-control jqdatepicker"  type="text" value="<? echo date('d.m.Y', strtotime ($radek['narozen']))?>" /></div>
            </div>
            <div class="row form-group">
                <div class="col-sm-4"><label class="text-danger">Adresa</label><input name="adresa" class="form-control" type="text" value="<? echo $radek['adresa']?>" /></div>
                <div class="col-sm-2"><label class="text-danger">Telefon</label><input name="tel" class="form-control"  type="text" value="<? echo $radek['tel']?>" /></div>
                <div class="col-sm-4"><label class="text-danger">E-mail</label><input name="mail" class="form-control"  type="text" value="<? echo $radek['mail']?>" /></div>
            </div>
        </div>            
	</div>


    <div class="panel panel-warning">        
        <div class="panel panel-heading"><strong>Logování</strong></div>
        <div class="panel panel-body">
            <div class="row form-group">
                <div class="col-sm-4"><label class="text-danger">Login</label><input name="login" class="form-control" type="text" value="<? echo $radek['login']?>" /></div>
                <div class="col-sm-4"><label class="text-danger">Heslo</label><input name="heslo" class="form-control" type="text" value="<? echo $radek['heslo']?>" /></div>
            </div>
        </div>
    </div>
      
	
    	<button name="update" type="submit" value="1" class="btn btn-info"><span class="glyphicon glyphicon-ok"></span>&nbsp;Uložit</button>
        <a href="?menu=registr_user" class="btn btn-info"><span class="glyphicon glyphicon-remove"></span>&nbsp;Zrušit změny</a>
    </form>
    </div>
	
    
    
    <div class="panel panel-default">        
        <div class="panel panel-heading"><strong>Finance</strong></div>
        <div class="panel panel-body">
            <div class="row form-group">
            
            <!--Tabulka pausalu A-->
            <? if($radek['instr_A']==1):?>
                <div class="col-sm-4">
                	<table class="table table-bordered">
                    	<tr class="bg-info"><th colspan="3"><h4>Výcvik A</h4></th></tr>
                        <tr class="bg-warning">
                        	<th>Datum od</th>
                            <th>Paušál</th>
                            <th></th>
                        </tr>
                        <? $count=1; unset ($rows); $rows=mysqli_num_rows($result_A)?>
                        <? while ($pausal_A=mysqli_fetch_array($result_A)):?>
                        	<tr class="<? if($count===$rows) echo 'success'?>">
                               	<td><? echo date('d.m.Y', strtotime($pausal_A['datum_od']))?></td>
                                <td><? echo $pausal_A['pausal']?></td>
                            </tr>
                            <? $count++;?>
                        <? endwhile?>
                        <tr>
                        	<td><input id="datum_A" type="text" class="jqdatepicker form-control"></td>
                            <td><input id="pausal_A" type="text" class="form-control"></td>
                            <td><button class="button_pausal btn btn-info" value="A"><span class="glyphicon glyphicon-plus"></span></button></td>
                        </tr>

                    </table>
                </div>
                <? endif?>

            <!--Tabulka pausalu B-->
            <? if($radek['instr_B']==1):?>
                <div class="col-sm-4">
                	<table class="table table-bordered">
                    	<tr class="bg-info"><th colspan="3"><h4>Výcvik B</h4></th></tr>
                    	<tr class="bg-warning">
                        	<th>Datum od</th>
                            <th>Paušál</th>
                            <th></th>
                        </tr>
                        <? $count=1; unset ($rows); $rows=mysqli_num_rows($result_B)?>
                        <? while ($pausal_B=mysqli_fetch_array($result_B)):?>
                        	<tr class="<? if($count===$rows) echo 'success'?>">
                               	<td><? echo date('d.m.Y', strtotime($pausal_B['datum_od']))?></td>
                                <td><? echo $pausal_B['pausal']?></td>
                            </tr>
                            <? $count++;?>
                        <? endwhile?>
                        <tr>
                        	<td><input id="datum_B" type="text" class="jqdatepicker form-control"></td>
                            <td><input id="pausal_B" type="text" class="form-control"></td>
                            <td><button class="button_pausal btn btn-info" value="B"><span class="glyphicon glyphicon-plus"></span></button></td>
                        </tr>
                    </table>
                </div>  
              <? endif?>              
                
                
                <div class="col-sm-4"></div>
            </div>
        </div>
    </div>
	
    
    
    
    
    
</div>