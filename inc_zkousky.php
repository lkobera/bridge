<?
/*UKLADANI A MAZANI VOLANE Z PODSTRANEK*/

	switch ($_GET['action']) {
		case 'delete':
			$sql='DELETE FROM zkousky_terminy WHERE id="'.$_GET['id'].'"';
			mysqli_query ($db_connect, $sql);
			break;
			
		case 'update':
			$sql='	UPDATE zkousky_terminy
					SET datum="'.date ('Y-m-d', strtotime($_POST['datum'])).'"
					,cas="'.$_POST['cas'].'"
					,venue_ID="'.$_POST['zkousky_venue'].'"
					,PPV="'.$_POST['PPV'].'"
					,PJ_A="'.$_POST['PJ_A'].'"
					,PJ_B="'.$_POST['PJ_B'].'"
					WHERE id="'.$_GET['id'].'"';
					echo $sql;
			mysqli_query ($db_connect, $sql);
			break;
	}




  /*SELECT zkouskek a vlozeni do ARRAY*/
  $sql='	  SELECT';
  $sql=$sql.' id, datum ';
  $sql=$sql.' FROM zkousky_terminy';
  $sql=$sql.' WHERE loc="'.$_SESSION['loc'].'"';
  $sql=$sql.' AND datum>="'.date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y'))).'"';
  $result=mysqli_query ($db_connect, $sql);

  
  while ($radek=mysqli_fetch_array ($result)) {
	  $arrayZkousky[$radek['datum']]=$radek['id'];
  }

?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Zkoušky</small><br>Kalendář zkoušek</h1>
        <? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) 
				OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>
	</div>

    <!--VYPIS KALENDARE-->
    <div class="table-responsive">
	    <table class="table table-hover table-bordered table-responsive">
		<!--tady se generuje kalendar-->
		<? for ($x = 0; $x <= 60; $x++):?>
	    	<? 
				$str_date=mktime(0, 0, 0, date('m'), date('d')+$x, date('Y'));
	            $ISOdate=date ('Y-m-d', $str_date);
				$CZdate=date ('d.m.Y', $str_date);
				$dayofweek=date('N', $str_date);
	        ?>
            
			<? if (!isset($arrayZkousky[$ISOdate])):?> <!--den bez zkousek-->
           		<tr id="<? echo $ISOdate?>">
					<td><? echo $CZdate?></td>
                    <? if ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1):?><td><button value="<? echo $ISOdate ?>" class="zkousky_insert btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td><? endif?>
                    <td id="td_<? echo $ISOdate?>"></td>
				</tr>

			<? endif?>
            
            
            <? if (isset($arrayZkousky[$ISOdate])):?><!--den se zkouskami-->
            	<tr id="<? echo $ISOdate?>">
                    <td class="info"><h4><? echo $CZdate?></h4></td>
                    <? if ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1):?><td class="info"><button value="<? echo $ISOdate?>" class="zkousky_insert btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td><? endif?>
                    <td class="warning" id="td_<? echo $ISOdate?>">
                    	<? $_GET['datum']=$ISOdate?>
                        <? include 'ajax/ajax_zkousky_table.php'?>
                    </td>
                </tr>
            <? endif?>
	    <? endfor?>
	    </table>
	</div>
</div>