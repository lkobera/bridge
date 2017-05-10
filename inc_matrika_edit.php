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
		<h1><small><?php echo $_SESSION['loc']?> | Evidence žáků | Matriční kniha</small><br />Editace studenta</h1>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) 
				OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>        
	</div>
	
    <form class="form" method="post" action="index.php?menu=matrika">
	 
   	<div class="well">  	
        <!--PANEL  OSOBNI UDAJE-->
        <div class="panel panel-primary">
            <div class="panel panel-heading"><strong>Osobní údaje</strong></div>
            <div class="panel panel-body">
                
                <div class="row form-group">
                    <div class="col-sm-4"><label class="text-danger">Příjmení *</label><input name="prijmeni" class="form-control" type="text" value="<? echo $radek['prijmeni']?>" /></div>
                    <div class="col-sm-4"><label class="text-danger">Jméno *</label><input name="jmeno" class="form-control" type="text" value="<? echo $radek['jmeno']?>" /></div>
                    <div class="col-sm-2"><label class="text-danger">Narozen *</label><input name="narozen" class="form-control jqdatepicker"  maxlength="10" type="text" value="<? echo date('d.m.Y', strtotime ($radek['narozen']))?>" /></div>
                </div>
                    
                <div class="row form-group">
                    <div class="col-sm-4"><label class="text-danger">Ulice *</label><input name="adresa1" class="form-control" type="text" value="<? echo $radek['adresa1']?>" /></div>
                    <div class="col-sm-2"><label class="text-danger">č.p. *</label><input name="cpopisne" class="form-control" type="text" value="<? echo $radek['cpopisne']?>" /></div>
                    <div class="col-sm-4"><label class="text-danger">Obec *</label><input name="adresa2" class="form-control" type="text" value="<? echo $radek['adresa2']?>" /></div>
                    <div class="col-sm-2"><label class="text-danger">PSČ *</label><input name="psc" class="form-control" type="text" maxlength="5" value="<? echo $radek['psc']?>" /></div>
                </div>
                    
                <div class="row form-group">
                     <div class="col-sm-4"><label class="text-danger">Telefon *</label><input name="tel" class="form-control" type="text" value="<? echo $radek['tel']?>" /></div>
                     <div class="col-sm-4"><label class="text-danger">E-mail *</label><input name="mail" class="form-control" type="text" value="<? echo $radek['mail']?>" /></div>
                </div>
                
                <div class="row form-group bg-active">
                    <div class="col-sm-2"><label class="text-muted">Rodné číslo</label><input name="rc" class="form-control" type="text" value="<? echo $radek['rc']?>" /></div>
                    <div class="col-sm-2"><label class="text-muted">Číslo dokladu totožnosti</label><input name="cOP" class="form-control" type="text" value="<? echo $radek['cOP']?>" /></div>
                    <div class="col-sm-4"><label class="text-muted">Místo narození</label><input name="mistonar" class="form-control" type="text" value="<? echo $radek['mistonar']?>" /></div>
                    <div class="col-sm-2">
                        <label class="text-muted">Občanství</label>
                        <select name="obcanstvi" class="form-control">
                            <option value="">Vyberte z nabídky</option>
                            <option value="Česká republika"<? if ($radek['obcanstvi']=='Česká republika') echo selected?>>Česká republika</option>
                            <option value="jiná"<? if ($radek['obcanstvi']=='jiná') echo selected?>>Ostatní státy</option>                        
                        </select>
                    </div>
                </div>            
            </div>
        </div>
        
        <!--SETUP SKUPIN-->
        <div class="row form-group">
        
            <!--MA RP-->
            <div class="col-sm-4">
                <div class="panel panel-info">
                    <div class="panel panel-heading"><label>Je držitelem ŘP</label><input name="rp" type="text" class="form-control" value="<? echo $radek['rp']?>"/></div>
                    <div class="panel panel-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <tr><td>AM</td><td><input name="maAM" value="1" class="form-control" type="checkbox" <? if ($radek['maAM']==1) echo 'checked'?> /></td></tr>
                            <tr><td>A1</td><td><input name="maA1" value="1" class="form-control" type="checkbox" <? if ($radek['maA1']==1) echo 'checked'?>/></td></tr>
                            <tr><td>A2</td><td><input name="maA2" value="1" class="form-control" type="checkbox" <? if ($radek['maA2']==1) echo 'checked'?>/></td></tr>
                            <tr><td>A</td><td><input name="maA" value="1" class="form-control" type="checkbox" <? if ($radek['maA']==1) echo 'checked'?>/></td></tr>
                            <tr><td>B</td><td><input name="maB" value="1" class="form-control" type="checkbox" <? if ($radek['maB']==1) echo 'checked'?>/></td></tr>
                            <tr><td>C</td><td><input name="maC" value="1" class="form-control" type="checkbox" <? if ($radek['maC']==1) echo 'checked'?>/></td></tr>
                            <tr><td>D</td><td><input name="maD" value="1" class="form-control" type="checkbox" <? if ($radek['maD']==1) echo 'checked'?>/></td></tr>
                            <tr><td>E</td><td><input name="maE" value="1" class="form-control" type="checkbox" <? if ($radek['maE']==1) echo 'checked'?>/></td></tr>
                            <tr><td>T</td><td><input name="maT" value="1" class="form-control" type="checkbox" <? if ($radek['maT']==1) echo 'checked'?>/></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!--CHCE RP-->
            <div class="col-sm-4">
                <div class="panel panel-info">
                    <div class="panel panel-heading">
                        <label>Žádá o</label>
                        <select name="request" class="form-control">
                            <option value="0" <? if ($radek['request']==0) echo 'selected'?>>standardní výcvik</option>
                            <option value="1" <? if ($radek['request']==1) echo 'selected'?>>přezkoušení způsobilosti</option>
                            <option value="2" <? if ($radek['request']==2) echo 'selected'?>>kondiční jízdy</option>
                        </select>
                    </div>
                    <div class="panel panel-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <tr><td>AM</td><td><input name="chceAM" class="form-control" value="1" type="checkbox" <? if ($radek['chceAM']==1) echo 'checked'?>/></td></tr>
                            <tr><td>A1</td><td><input name="chceA1" class="form-control" value="1" type="checkbox" <? if ($radek['chceA1']==1) echo 'checked'?>/></td></tr>
                            <tr><td>A2</td><td><input name="chceA2" class="form-control" value="1" type="checkbox" <? if ($radek['chceA2']==1) echo 'checked'?>/></td></tr>
                            <tr><td>A</td><td><input name="chceA" class="form-control" value="1" type="checkbox" <? if ($radek['chceA']==1) echo 'checked'?>/></td></tr>
                            <tr><td>B</td><td><input name="chceB" class="form-control" value="1" type="checkbox" <? if ($radek['chceB']==1) echo 'checked'?>/></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!--STATUS-->
            <div class="col-sm-4">
				<div class="panel panel-info">
            		<div class="panel panel-heading">
                		<label>Status výcviku</label>
                	</div>
					<div class="panel panel-body">
            			<div class="col-md-8">
                    		<select name="status" class="form-control">
                        		<option value="1" <? if ($radek['status']==1):?>selected<? endif?>>zahájen</option>
                            	<option value="2" <? if ($radek['status']==2):?>selected<? endif?>>čeká na výcvik</option>
                            	<option value="3" <? if ($radek['status']==3):?>selected<? endif?>>ve výcviku</option>
                            	<option value="4" <? if ($radek['status']==4):?>selected<? endif?>>před zkouškou</option>
                            	<option value="5" <? if ($radek['status']==5):?>selected<? endif?>>repro</option>
                            	<option value="6" <? if ($radek['status']==6):?>selected<? endif?>>archiv</option>
	                        </select>
						</div>
                    </div>
        		</div>
        	</div>
            
            
			<!--CENA-->
        	<div class="col-sm-4">
				<div class="panel panel-warning">
            		<div class="panel panel-heading">
                		<label>Kurzovné</label>
                	</div>
					<div class="panel panel-body">
            		<div class="col-md-4"><label class="text-muted">CZK</label><input name="cena" class="form-control" type="text" value="<? echo $radek['cena']?>" /></div>
        		</div>
        	</div>
		</div>
        

       	<div class="col-sm-12">
			<button name="registrace_save" value="<? echo $_GET['GUID']?>" type="submit" class="btn btn-primary"?><span class="glyphicon glyphicon-ok"></span>&nbsp;Uložit</button>
	       	<a href="?menu=matrika" class="btn btn-primary"><span class="glyphicon glyphicon-remove"></span>&nbsp;Zrušit změny</a>
		</div>
	</div>
	        
    </form>
    </div>
</div>