<?
	$osnovaZK=osnovaZK();
	
	
	/*SELECT Z MATRIKY / REGISTRACE*/
	$sql='	SELECT * FROM '.$_GET['source'].' WHERE '.$_GET['source'].'.GUID="'.$_GET['GUID'].'"';
		
	$result = mysqli_query ($db_connect, $sql);
	$student=mysqli_fetch_array($result);
	$skupina_kod=skupina_kod($_GET['source'],$student['GUID']);
	$status=switchstatus($student['status']);
	
	
		
	/*SELECT Z POKLADNY*/
	$sql='		SELECT *';
	$sql=$sql.'	FROM pokladna_zalohy';
	$sql=$sql.'	WHERE GUID="'.$_GET['GUID'].'"';
	$result_p=mysqli_query ($db_connect, $sql);
	
	$cenik=cenik($student['loc']);
	$zaloha=0;
	
	/*SELECT ZE ZKOUSEK*/
	
	$sql= '	SELECT 
				zkz.zkID, zkz.PPV, zkz.PJ_A, zkz.PJ_B, zkz.ucitel_A, zkz.ucitel_B, zkz.resultPPV, zkz.resultPJ_A, zkz.resultPJ_B, zkz.repro, zkz.absence, zkz.locked,
				zkt.datum
			FROM zkousky_zaci zkz JOIN zkousky_terminy zkt
			ON zkt.id = zkz.zkID
			WHERE zkz.GUID="'.$_GET['GUID'].'"
			ORDER BY zkt.datum DESC
	';
	$result_zk=mysqli_query ($db_connect, $sql);
?>


<div class="container-fluid">
	<div class="page-header">
		<h1><small><? echo $_SESSION['loc']?> | Evidence žáků</small><br />Karta žáka</h1>
			<div class="row">
            	<div class="col-md-6">
                    <div class="panel-group">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                               <h1 class="panel-title">
                               		<span class="glyphicon glyphicon-user"></span>&nbsp;<?=$student['UID']?>&nbsp;<?=strtoupper_all($student['prijmeni']).' '.$student['jmeno']?>                                    
                               </h1>
                            </div>
                            <div class="panel-body">
                                <table class="table">                                
                                	<tr>
                                    	<td colspan="2" class="<?=$status?>"></td>
                                    </tr>
                                    <tr>
                                        <th>Skupina</th>
                                        <td><?=$skupina_kod?></td>
                                    </tr>
                                    <tr>
                                        <th>Narozen</th>
                                        <td><?=date('d.m.Y',strtotime ($student['narozen']))?></td>
                                    </tr>
                                    <tr>
                                        <th>Adresa</th>
                                        <td><?=$student['adresa1']?>&nbsp;<?=$student['cpopisne']?><br /><?=$student['adresa2']?> <?=$student['psc']?></td>
                                    </tr>
                                    <tr>
                                        <th>Kontakt</th>
                                        <td><a href="tel:<?=$student['tel']?>"><?=$student['tel']?></a><br /><a href="mailto:<?=$student['mail']?>"><?=$student['mail']?></a></td>
                                    </tr>
                                </table>
                                <? if (($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)):?>
	                                <a href="?menu=<?=$_GET['source']?>_edit&GUID=<?=$_GET['GUID']?>" class="btn btn-primary"><span class="glyphicon glyphicon-new-window"></span>&nbsp;Editace žáka</a>
                                    <a href="?menu=tridni_kniha&GUID=<?=$_GET['GUID']?>" class="btn btn-primary"><span class="glyphicon glyphicon-new-window"></span>&nbsp;Třídní kniha</a>
                                <? endif?>
                            </div>
						</div>
                        
                        <!--PANEL PLATBY-->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h1 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse-platby">
                                        <div class="row">
                                            <div class="col-xs-9"><span class="glyphicon glyphicon-piggy-bank"></span>&nbsp;Platby</div>
                                            <div class="col-xs-3 text-right"><span class="panel-arrow glyphicon glyphicon-menu-down"></span></div>
                                        </div>		            
                                    </a>
                                </h1>
                            </div>
                            <div id="collapse-platby" class="panel-collapse collapse">
                                  <div class="panel-body">
                                  <table class="table">
	                                <tr class="bg-info">
                                    	<th colspan="2">Kurzovné</th>
                                        <td><?= $student['cena']?></td>
                                        <td></td>
                                    </tr>
                                    <tr class="active">
                                    	<th>Datum</th>
                                        <th>Autor</th>
                                        <th>Platba</th>
                                        <th></th>
                                    </tr>
                                  	<? while ($pokladna=mysqli_fetch_array($result_p)):?>
                                    	<? $zaloha=$zaloha+$pokladna['platba']?>
                                        <tr>
                                        	<td><?=date('d.m.Y', strtotime($pokladna['datum']))?></td>
                                            <td><?= $arrayUser[$pokladna['autor']]['jmeno']?></td>
                                            <td><?=$pokladna['platba']?></td>
                                            <td>
                                            	<? if ($pokladna['pocet']>0):?>
													<?= $cenik[$pokladna['cenikID']]['polozka']?>&nbsp;(<?=$pokladna['pocet']?>)
												<? endif?>
                                            </td>
                                        </tr>
                                    <? endwhile?>
                                    <? if ($student['cena']>$zaloha):?>
                                  		<tr class="bg-danger">
                                    <? else:?>
	                                    <tr class="bg-success">
                                    <? endif?>
                                    	<th colspan="2">Celkem uhrazeno</th>
                                    	<td><strong><?=$zaloha?></strong></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-warning">
                                    	<th colspan="2">Zbývá doplatit</th>
                                        <td><? if ($student['cena']-$zaloha<0) echo "0"; else echo $student['cena']-$zaloha?></td>
                                    </tr>                                
                                  </table>
                                  <? if ($arrayUser[$_COOKIE['userID']]['pravo-pokladna']==1):?>
	                                  <a href="?menu=platby_detail&GUID=<?=$_GET['GUID']?>" class="btn btn-primary"><span class="glyphicon glyphicon-new-window"></span>&nbsp;Pokladna</a>
                                  <? endif?>
                                  </div>
                             </div>
                        </div>     
                        
                        <? if ($_GET['source']=='matrika'):?>
                            <!--PANEL TK PJ-->
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h1 class="panel-title">
                                        <a data-toggle="collapse" href="#collapse-vycvik">
                                            <div class="row">
                                                <div class="col-xs-9"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Výcvik</div>
                                                <div class="col-xs-3 text-right"><span class="panel-arrow glyphicon glyphicon-menu-down"></span></div>
                                            </div>		            
                                        </a>
                                    </h1>
                                </div>
                                <div id="collapse-vycvik" class="panel-collapse collapse">
                                      <div class="panel-body">
                                          <? $readonly=true?>
                                          <? include "ajax/ajax_praxe_table.php"?>
                                          <div class="col-xs-12">
                                              <a href="?menu=praxe_edit&GUID=<?=$_GET['GUID']?>" class="btn btn-primary"><span class="glyphicon glyphicon-new-window"></span>&nbsp;Třídní kniha praxe</a>
                                          </div>
                                      </div>
                                 </div>
                            </div>                                             
                            
                            <!--PANEL ZKOUSKY-->
                            <div class="panel panel-primary">
                                    <div class="panel-heading">
                                    <h1 class="panel-title">
                                        <a data-toggle="collapse" href="#collapse-zkousky">
                                            <div class="row">
                                                <div class="col-xs-9"><span class="glyphicon glyphicon-road"></span>&nbsp;Zkoušky</div>
                                                <div class="col-xs-3 text-right"><span class="panel-arrow glyphicon glyphicon-menu-down"></span></div>
                                            </div>		            
                                        </a>
                                    </h1>
                                </div>
                                <div id="collapse-zkousky" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            <tr class="bg-warning">
                                                <th></th>
                                                <th>PPV</th>
                                                <th>PJ A</th>
                                                <th>PJ B</th>
                                                <th></th>                                            
                                            </tr>
                                            <tr class="bg-info hidden-xs">
                                                <th>Prospěl</th>
                                                <td id="k_ppv"></td>
                                                <td id="k_pja"></td>
                                                <td id="k_pjb"></td>
                                                <td></td>
                                            </tr>                                        
                                            
                                            <? while ($zkouska=mysqli_fetch_array($result_zk)):?>
                                                <tr>
                                                    <td><a href="?menu=zkousky_seznam&id=<?=$zkouska['zkID']?>"><?=date('d.m.Y', strtotime($zkouska['datum']))?></a></td>
                                                    <td>
                                                        <? if ($zkouska['PPV']=='1' AND $zkouska['resultPPV']==1):?>
                                                            <span class="glyphicon glyphicon-thumbs-up"></span>
                                                            <input id="datePPV" type="hidden" value="<?= date('d.m.Y', strtotime($zkouska['datum']))?>" />
                                                        <? elseif ($zkouska['PPV']=='1' AND $zkouska['resultPPV']=='0'):?><span class="glyphicon glyphicon-thumbs-down"></span>
                                                        <? endif?>
                                                    </td>
                                                    <td>
                                                        <? if ($zkouska['PJ_A']=='1' AND $zkouska['resultPJ_A']==1):?>
                                                            <span class="glyphicon glyphicon-thumbs-up"></span>
                                                            <?= $arrayUser[$zkouska['ucitel_A']]['jmeno']?>
                                                            <input id="datePJ_A" type="hidden" value="<?= date('d.m.Y', strtotime($zkouska['datum']))?>" />
                                                        <? elseif ($zkouska['PJ_A']=='1' AND $zkouska['resultPJ_A']=='0'):?>
                                                            <span class="glyphicon glyphicon-thumbs-down"></span>
                                                            <?= $arrayUser[$zkouska['ucitel_A']]['jmeno']?>
                                                        <? elseif ($zkouska['PJ_A']=='1' AND $zkouska['resultPJ_A'] == NULL):?>
                                                            <span class="glyphicon glyphicon-unchecked"></span>	                                                        
                                                        <? endif?>
                                                    </td>
                                                    <td>
                                                        <? if ($zkouska['PJ_B']=='1' AND $zkouska['resultPJ_B']==1):?>
                                                            <span class="glyphicon glyphicon-thumbs-up"></span>
                                                            <?= $arrayUser[$zkouska['ucitel_B']]['jmeno']?>
                                                            <input id="datePJ_B" type="hidden" value="<?= date('d.m.Y', strtotime($zkouska['datum']))?>" />
                                                        <? elseif ($zkouska['PJ_B']=='1' AND $zkouska['resultPJ_B']=='0'):?>
                                                            <span class="glyphicon glyphicon-thumbs-down"></span>
                                                            <?= $arrayUser[$zkouska['ucitel_B']]['jmeno']?>
                                                        <? elseif ($zkouska['PJ_B']=='1' AND $zkouska['resultPJ_B'] == NULL):?>
                                                            <span class="glyphicon glyphicon-unchecked"></span>															                                                        
                                                        <? endif?>
                                                    </td>
                                                    <td>
                                                        <? if ($zkouska['locked']=='1'):?>
                                                            <span class="glyphicon glyphicon-lock"></span>
                                                        <? endif?>
                                                    </td>
                                                </tr>
                                            <? endwhile?>
                                      
                                        </table>
                                        <a href="?menu=zkousky_vysledky" class="btn btn-primary"><span class="glyphicon glyphicon-new-window"></span>&nbsp;Výsledky zkoušek</a>
                                    </div>
                                 </div>
                            </div>
                        <? endif?>
                        
                        
                    </div>
                </div>        
			</div>        
        
        
        
        
      
        
        
	</div>
</div>

<script>
$(document).ready(function(){
    $('#k_ppv').html($('#datePPV').val());
	$('#k_pja').html($('#datePJ_A').val());
	$('#k_pjb').html($('#datePJ_B').val());
});
</script>