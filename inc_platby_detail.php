<?
	session_start();
	
	include_once 'inc_db_connect.php';
	include_once 'inc_library.php';
	
	$cenik=cenik($_SESSION['loc']);
	
	
	/*nazvy polozek v ceniku v hidden inputech pro jquery*/
	foreach ($cenik as $key => $sluzba) {
		echo '<input id="cenik_polozka_'.$key.'" type="hidden" value="'.$sluzba['polozka'].'"/>';
	}
	
	
	
	$ISOtoday=date('Y-m-d');
	$CZtoday=date('d.m.Y');

	
	/*SELECT - pokud prijde uzivatel z comba tak nemá GET[zdroj] - tady se udela workaround aby se zdroj dat zjistil*/
	
	if (!isset($_GET['zdroj'])) {
		$sql='SELECT COUNT(1) AS from_reg FROM registrace WHERE GUID="'.$_GET['GUID'].'"';
		$result=mysqli_query ($db_connect, $sql);
		$radek=mysqli_fetch_array($result);
		
		switch ($radek['from_reg']) {
			case '0': 	
				$_GET['zdroj']='matrika';
				break;
			case '1':
				$_GET['zdroj']='registrace';
				break;
		}
	}
	
	/*SELECT Z MATRIKY/REGISTRACE*/	
	$sql='		SELECT';
	if ($_GET['zdroj']=='matrika') $sql=$sql.' UID, cena,';
	$sql=$sql.'	jmeno, prijmeni, narozen, adresa1, adresa2, cpopisne, psc';
	$sql=$sql.'	FROM '.$_GET['zdroj'];
	$sql=$sql.'	WHERE GUID="'.$_GET['GUID'].'"';
	$result=mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
	
	/*SELECT Z POKLADNY*/
	$sql='		SELECT';
	$sql=$sql.'	datum, platba, autor, cenikID';
	$sql=$sql.'	FROM pokladna_zalohy';
	$sql=$sql.'	WHERE GUID="'.$_GET['GUID'].'"';
	$result2=mysqli_query ($db_connect, $sql);
	
	$zaloha=0;
	$skupina=skupina_kod($_GET['zdroj'], $_GET['GUID']);
		
	
		
?>

<div class="container-fluid noprint">
	<h1><small><?php echo $_SESSION['loc']?> | Evidence žáků | Pokladna</small><br />Evidence plateb</h1>
    
	<? if ($_GET['GUID']=='') {echo '<p>Chyba. Nebyl vybrán žádný klient!</p>'; die;}?>

    <table class="table table-responsive">
		<tr class="info">
        	<th>Ev. č.</th>
            <th>Příjmení a jméno</th>
            <th>Výcvik</th>
            <th>Narozen</th>
		</tr>
        <tr>
        	<td><? echo $radek['UID']?></td>
            <td><a href="?menu=karta_zaka&GUID=<?=$_GET['GUID']?>&source=matrika"><? echo strtoupper_all($radek['prijmeni']).' '.$radek['jmeno']?></a></td>
            <td><? echo $skupina?></td>
            <td><? echo date ('d.m.Y', strtotime ($radek['narozen']))?></td>                        
        </tr>        
	</table>
    
    <div class="col-md-6">
        <table class="table table-responsive table-bordered">
            <tr class="warning">
                <th colspan="2"><h4>Kurzovné</h4></th>
                <th><h4><? echo $radek['cena']?></h4></th>
                <th></th>
            </tr>
            <tr class="active">
        		<th>Datum</th>
                <th>Autor</th>
                <th>Platba</th>
                <th></th>
        	</tr>
            <? while ($radek2=mysqli_fetch_array($result2)):?>
                <tr>
                    <td><? echo date('d.m.Y', strtotime ($radek2['datum']))?></td>
                    <td><? echo $arrayUser[$radek2['autor']]['jmeno']?></td>
                    <td><? echo $radek2['platba']; $zaloha=$zaloha+$radek2['platba']?></td>
                    <td><? echo $cenik[$radek2['cenikID']]['polozka']?></td>
                </tr>
            <? endwhile?>
            
            <? if ($_GET['zdroj']=='registrace' XOR (($radek['cena']-$zaloha)>0)):?>
                <tr>
                    <td colspan="3"><input id="input_kurzovne" type="number" class="form-control" min="0" step="100"/></td>
                    <td><button id="button_pay_kurzovne" type="button" class="btn btn-default disabled" data-toggle="" data-target="#ModalKurzovne"><span class="glyphicon glyphicon-ok"></span></button></td>
                </tr>
            <? endif?>
            
            <tr class="<? if (($radek['cena']==0) OR ($radek['cena']-$zaloha>0)) echo 'info'; else echo 'success'?>">
            	<td colspan="2"><strong>Celkem uhrazeno</strong></td>
                <td><strong><? echo $zaloha?></strong></td>
                <td></td>
            </tr>
            <? if (($_GET['zdroj']=='matrika') AND ($radek['cena']-$zaloha>0)):?>
	            <tr class="warning">
    	            <td colspan="2"><strong>Zbývá uhradit</strong></td>
        	        <td><strong><? echo $radek['cena']-$zaloha?></strong></td>
            	    <td></td>
            	</tr>
            <? endif?>
            
        </table>
	</div>
    
    <!--KONDICE - dostupne je  pokud je uhrazeno kurzovne-->
    <? if ($radek['cena']>0 AND ($radek['cena']-$zaloha)<=0):?>
        <div class="col-md-6">
            <table class="table table-responsive table-bordered">
                <tr class="warning">
                    <th colspan="5"><h4>Doplňkové služby</h4></th>
                    <th></th>
                </tr>
                
                <tr>
                    <td colspan="3">
                        <select id="polozka_kondice" class="form-control">
                            <option value="0">Vyber službu...</option>
                            <? foreach ($cenik as $key => $sluzba):?>
                                    <option value="<? echo $key?>"><? echo $sluzba['polozka']?></option>
                            <? endforeach?>
                        </select>
                        <? foreach ($cenik as $key => $sluzba):?>
                                <input id="cena_kondice_<? echo $key?>" type="hidden" value="<? echo $sluzba['cena']?>" />
                        <? endforeach?>
                        
                    </td>
                    <td>
                        <input class="form-control" id="pocet_kondice" disabled="0" type="number" min="0"/>
                    </td>
                    <td><input id="cena_total" class="form-control" disabled="disabled" /></td>
                    <td><button id="button_pay_kondice" type="button" class="btn btn-default disabled" data-toggle="" data-target="#ModalKurzovne"><span class="glyphicon glyphicon-ok"></span></button></td>
                </tr>
            </table>
        </div>
        <input type="hidden" id="input_kurzovne" />
	<? endif?>
    
    
    <!--omezeni max placene castky-->
    <? if ($_GET['zdroj']=='matrika'):?>
	  	<input id="madati" type="hidden" value="<? echo $radek['cena']-$zaloha?>"/>
	<? endif?>
    <? if ($_GET['zdroj']=='registrace'):?>
		<input id="madati" type="hidden" value="99999"/>
    <? endif?>
  
    

    
</div>



<!-- PAYMENT Modal KURZOVNE -->
<div class="modal fade" id="ModalKurzovne" role="dialog">
    <div class="modal-dialog">

     <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              	<p><? echo $CZtoday?></p>
              	<h4 class="modal-title">autoškolaeasy.cz | Stvrzenka na zálohu</h4>
				<p>Není daňovým dokladem. Konečné vyúčtování je k dispozici po ukončení výuky a výcviku.</p>
				
            </div>
            <div class="modal-body">
            	<p><strong>Příjem od</strong></p>
				<p><? echo strtoupper_all($radek['prijmeni']).' '.$radek['jmeno'].' | '. date ('d.m.Y', strtotime ($radek['narozen'])).' | '.$radek['adresa1'].' '.$radek['cpopisne'].' '.$radek['adresa2'].' '.$radek['psc']?></p>

               	<p><strong>Účel platby</strong></p><!--rozliseni mezi kurzovnym a kondickama (pres jquery)-->
                <p>
					<? if ((($radek['cena']-$zaloha)>0) OR ($radek['cena']==0) ):?><p><? echo skupina_kod($_GET['zdroj'], $_GET['GUID'])?></p><? endif?>
                    <div id="invoice_ucel"></div>
				</p>
				
                
                <p><strong>Částka Kč</strong></p>
               	<p><div id="invoice_price"></div></p>
                

                
            </div>
            <div class="modal-footer">
            	<input id="invoice_GUID" type="hidden" value="<? echo $_GET['GUID']?>" />
                <input id="invoice_UID" type="hidden" value="<? echo $radek['UID']?>" />
                <input id="invoice_datum" type="hidden" value="<? echo $ISOtoday?>" />
                <input id="invoice_autor" type="hidden" value="<? echo $_COOKIE['userID']?>" />
                <input id="invoice_zdroj" type="hidden" value="<? echo $_GET['zdroj']?>" />
                
                <!--blok informaci pro doplnkove sluzby-->
                <input id="invoice_ID" type="hidden" value="" />
                <input id="invoice_pocet" type="hidden" value="" />

            
	           	<button type="button" class="btn btn-info" onClick="window.print()"><span class="glyphicon glyphicon-print"></span>&nbsp;Tisk</button>               
                <button type="button" id="button_zauctovat" class="btn btn-warning"><span class="glyphicon glyphicon-ok"></span>&nbsp;Zaúčtovat</button>
                <div class="printonly"><br><br><br><p class="text-right">podpis a razítko</p></div>
            </div>
        </div>
  
    </div>
</div>