<?
	session_start();

	
	include_once $_GET['inc_path'].'inc_db_connect.php';
	include_once $_GET['inc_path'].'inc_library.php';
	
	$arrayUser=arrayUser();
	
	/**SELECT ZAKU ZE ZKOUSEK*/
	$sql='	SELECT
			zkousky_zaci.*, 
			IFNULL(zkousky_zaci.resultPPV,"") AS resultPPV,
			IFNULL(zkousky_zaci.resultPJ_A,"") AS resultPJ_A,
			IFNULL(zkousky_zaci.resultPJ_B,"") AS resultPJ_B,			
			matrika.UID, matrika.GUID, matrika.jmeno, matrika.prijmeni, matrika.ucitel_A, matrika.ucitel_B
			FROM zkousky_zaci LEFT JOIN matrika
			ON zkousky_zaci.GUID=matrika.GUID
			WHERE zkousky_zaci.ZKid="'.$_GET['ZKid'].'"';
	$result2=mysqli_query ($db_connect, $sql); 
	

	
?>

<table class="table table-responsive table-bordered table-condensed table-hover">
    <tr>
        <th class="bg-info"><? echo $_GET['ZKid']?></th>
        <th class="bg-info">Ev.č.</th>
        <th class="bg-info">Jméno</th>
        <th class="bg-info">Skupina</th>
        <th class="bg-info">Učitel</th>
        <th class="bg-warning">PPV</th>
        <th class="bg-warning">PJ&nbsp;A</th>
        <th class="bg-warning">PJ&nbsp;B</th>
        
        <th class="bg-warning"></th>
    </tr>
    <? while ($radek2=mysqli_fetch_array($result2)):?>
    
    	<?
			$sql='SELECT COUNT(ZKid) AS repro FROM zkousky_zaci WHERE locked=1 AND (resultPJ_A IS NOT NULL OR resultPJ_B IS NOT NULL) AND GUID="'.$radek2['GUID'].'"';
			$result3=mysqli_query ($db_connect, $sql); 
			$radek3=mysqli_fetch_array($result3);
        ?>
    
    
    
         <tr id="<? echo $radek2['ID']?>"><!--ID neni UID zaka ale unikatni ID zaznamu ve zkousky_zaci-->
            <td>
                <? if (($radek2['locked']==0) AND ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1)):?><button class="zkousky_vysledky_delete btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button><? endif?>
            </td>
            <td><? echo $radek2['UID']?></td>
            <td><a href="?menu=karta_zaka&GUID=<?=$radek2['GUID']?>&source=matrika"><? echo strtoupper_all($radek2['prijmeni']).' '.$radek2['jmeno']?></a></td>
            <td><? echo skupina_kod('matrika', $radek2['GUID'])?></td>
        	<td>
				<? echo $arrayUser[$radek2['ucitel_A']]['inicial'].'&nbsp;'.$arrayUser[$radek2['ucitel_B']]['inicial']?>
                <input type="hidden" id="ucitel_A_<? echo $radek2['ID']?>" value="<? echo $radek2['ucitel_A']?>">
                <input type="hidden" id="ucitel_B_<? echo $radek2['ID']?>" value="<? echo $radek2['ucitel_B']?>">
                <input type="hidden" id="repro_<? echo $radek2['ID']?>" value="<? echo $radek3['repro']?>">
            </td>
           
           
           
           
           
           <!--vysledky-->          
           	<? if (($_COOKIE['userID']==$radek2['ucitel_A']) OR ($_COOKIE['userID']==$radek2['ucitel_B']) OR ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1)):?>
	            <td>
					<? if($radek2['PPV']==1):?>
                    	<button 
                        	id="PPV_<? echo $radek2['ID']?>" 
                        	class="zk_switch btn <? if ($radek2['resultPPV']=='') echo 'btn-info';elseif ($radek2['resultPPV']==1) echo 'btn-success disabled';elseif ($radek2['resultPPV']==0) echo 'btn-danger disabled'?>" 
                            value="<? echo $radek2['resultPPV']?>">
                            <span class="glyphicon <? if ($radek2['resultPPV']=='') echo 'glyphicon-unchecked';elseif ($radek2['resultPPV']==1) echo 'glyphicon-thumbs-up';elseif ($radek2['resultPPV']==0) echo 'glyphicon-thumbs-down'?>"></span>
                        </button>
					<? endif?>
					<? if($radek2['PPV']<>1):?><span class="glyphicon glyphicon-remove"></span><? endif?>
                </td>
	            <td>
					<? if($radek2['PJ_A']==1):?>
                    	<button 
                        	id="PJ_A_<? echo $radek2['ID']?>" 
                            class="zk_switch btn <? if ($radek2['resultPJ_A']=='') echo 'btn-info';elseif ($radek2['resultPJ_A']==1) echo 'btn-success disabled';elseif ($radek2['resultPJ_A']==0) echo 'btn-danger disabled'?>" 
                            value="<? echo $radek2['resultPJ_A']?>">
                            <span class="glyphicon <? if ($radek2['resultPJ_A']=='') echo 'glyphicon-unchecked';elseif ($radek2['resultPJ_A']==1) echo 'glyphicon-thumbs-up';elseif ($radek2['resultPJ_A']==0) echo 'glyphicon-thumbs-down'?>"></span>
						</button>
					<? endif?>
					<? if($radek2['PJ_A']<>1):?><span class="glyphicon glyphicon-remove"></span><? endif?>
                </td>
	            <td>
					<? if($radek2['PJ_B']==1):?>
                    	<button 
                        	id="PJ_B_<? echo $radek2['ID']?>" 
                            class="zk_switch btn <? if ($radek2['resultPJ_B']=='') echo 'btn-info';elseif ($radek2['resultPJ_B']==1) echo 'btn-success disabled';elseif ($radek2['resultPJ_B']==0) echo 'btn-danger disabled'?>" 
                            value="<? echo $radek2['resultPJ_B']?>">
                            <span class="glyphicon <? if ($radek2['resultPJ_B']=='') echo 'glyphicon-unchecked';elseif ($radek2['resultPJ_B']==1) echo 'glyphicon-thumbs-up';elseif ($radek2['resultPJ_B']==0) echo 'glyphicon-thumbs-down'?>"></span>
						</button>
					<? endif?>
					<? if($radek2['PJ_B']<>1):?><span class="glyphicon glyphicon-remove"></span><? endif?>
				</td>
	            <td>
                	<button 
                    	id="zk_save_<? echo $radek2['ID']?>" 
                        class="zk_save btn btn-default disabled" 
                        value="">
                        <span class="glyphicon <? if (($radek2['resultPPV']<>'') OR ($radek2['resultPJ_A']<>'') OR ($radek2['resultPJ_B']<>'')) echo 'glyphicon-saved'; else echo 'glyphicon-save'?>"></span>
					</button>
				</td>            
			<? endif?>
            
			
           

        </tr>
    <? endwhile?>
</table>
