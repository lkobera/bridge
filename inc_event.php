<?
/****************** INSERT NOVEHO KURZU********************/

if ($_GET['action']=='update') {

	if ($_POST['start_date']<>'') $_POST['start_date']=date ('Y-m-d', strtotime($_POST['start_date'])); else $_POST['start_date']='';	
	if ($_POST['PPV1_date']<>'') $_POST['PPV1_date']=date ('Y-m-d', strtotime($_POST['PPV1_date'])); else $_POST['PPV1_date']='';
	if ($_POST['PPV2_date']<>'') $_POST['PPV2_date']=date ('Y-m-d', strtotime($_POST['PPV2_date'])); else $_POST['PPV2_date']='';
	if ($_POST['PPV3_date']<>'') $_POST['PPV3_date']=date ('Y-m-d', strtotime($_POST['PPV3_date'])); else $_POST['PPV3_date']='';
	if ($_POST['PPV4_date']<>'') $_POST['PPV4_date']=date ('Y-m-d', strtotime($_POST['PPV4_date'])); else $_POST['PPV4_date']='';
	if ($_POST['PPV5_date']<>'') $_POST['PPV5_date']=date ('Y-m-d', strtotime($_POST['PPV5_date'])); else $_POST['PPV5_date']='';
	if ($_POST['TZBJ_date']<>'') $_POST['TZBJ_date']=date ('Y-m-d', strtotime($_POST['TZBJ_date'])); else $_POST['TZBJ_date']='';
	if ($_POST['ZPOP_date']<>'') $_POST['ZPOP_date']=date ('Y-m-d', strtotime($_POST['ZPOP_date'])); else $_POST['ZPOP_date']='';

	if ($_POST['start_time']=='') $_POST['start_time']='';
	if ($_POST['PPV1_time']=='') $_POST['PPV1_time']='';
	if ($_POST['PPV2_time']=='') $_POST['PPV2_time']='';
	if ($_POST['PPV3_time']=='') $_POST['PPV3_time']='';
	if ($_POST['PPV4_time']=='') $_POST['PPV4_time']='';
	if ($_POST['PPV5_time']=='') $_POST['PPV5_time']='';
	if ($_POST['TZBJ_time']=='') $_POST['TZBJ_time']='';
	if ($_POST['ZPOP_time']=='') $_POST['ZPOP_time']='';

	if ($_GET['id']=='') {
		$sql='	INSERT INTO event
				(
					loc, venue, status, 
					start_date, start_time,
					PPV1_date, PPV1_time,
					PPV2_date, PPV2_time,
					PPV3_date, PPV3_time,
					PPV4_date, PPV4_time,
					TZBJ_date, TZBJ_time,
					ZPOP_date, ZPOP_time
				)
				VALUES
				(
					"'.$_POST['loc'].'","'.$_POST['venue'].'","'.$_POST['status'].'",
					"'.$_POST['start_date'].'","'.$_POST['start_time'].'",
					"'.$_POST['PPV1_date'].'","'.$_POST['PPV1_time'].'",
					"'.$_POST['PPV2_date'].'","'.$_POST['PPV2_time'].'",
					"'.$_POST['PPV3_date'].'","'.$_POST['PPV3_time'].'",
					"'.$_POST['PPV4_date'].'","'.$_POST['PPV4_time'].'",
					"'.$_POST['TZBJ_date'].'","'.$_POST['TZBJ_time'].'",
					"'.$_POST['ZPOP_date'].'","'.$_POST['ZPOP_time'].'"
				)
			';
	}
	else {
		$sql='	UPDATE event
				SET
					loc="'.$_POST['loc'].'",
					venue="'.$_POST['venue'].'",
					status="'.$_POST['status'].'",
					start_date="'.$_POST['start_date'].'",
					start_time="'.$_POST['start_time'].'",
					PPV1_date="'.$_POST['PPV1_date'].'",
					PPV1_time="'.$_POST['PPV1_time'].'",
					PPV2_date="'.$_POST['PPV2_date'].'",
					PPV2_time="'.$_POST['PPV2_time'].'",
					PPV3_date="'.$_POST['PPV3_date'].'",
					PPV3_time="'.$_POST['PPV3_time'].'",
					PPV4_date="'.$_POST['PPV4_date'].'",
					PPV4_time="'.$_POST['PPV4_time'].'",
					TZBJ_date="'.$_POST['TZBJ_date'].'",
					TZBJ_time="'.$_POST['TZBJ_time'].'",
					ZPOP_date="'.$_POST['ZPOP_date'].'",
					ZPOP_time="'.$_POST['ZPOP_time'].'"						
				WHERE ID="'.$_GET['id'].'"
			';
	}
	mysqli_query ($db_connect, $sql);
}








/********************************SELECT EVENTU***************************************/

$sql='	SELECT 
			ID,
			loc,
			start_date,
			status
		FROM event	
		WHERE loc="'.$_SESSION['loc'].'" ORDER BY start_date DESC';
$result=mysqli_query ($db_connect, $sql);
?>




<div class="container-fluid">
	<div class="page-header">
		<h1><small><? echo $_SESSION['loc']?> | Admin</small><br />Management kurzů</h1>
	</div>
    <div class="row">
        <div class="col-md-10">
            <table class="table">
                <tr>                	
                    <th colspan="5"><a href="?menu=event_edit" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Nový kurz</a></th>
                    <th colspan="4" class="bg-warning"><h4>Management kurzu</h4></th>
                </tr>
                <tr>
	                <th class="bg-info"></th>
                    <th class="bg-info">ID</th>
                    <th class="bg-info">Místo</th>
                    <th class="bg-info">Zahájení</th>
                    <th class="bg-info">Ukončení</th>
                    <th class="bg-warning">Status</th>
                    <th class="bg-warning">&sum;Reg</th>
                    <th class="bg-warning">&sum;Conf</th>
                </tr>
              	<? while ($rsX=mysqli_fetch_array($result)):?>
                	<tr>
                    	<td><a href="?menu=event_edit&id=<?=$rsX['ID']?>" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></button></td>
                        <td><?=$rsX['ID']?></td>
                        <td><?=$rsX['loc']?></td>
                        <td><? if ($rsX['start_date']<>'0000-00-00') echo date('d.m.Y', strtotime($rsX['start_date']))?></td>
                        <td>
                        	<?
								$sql='SELECT MAX(a.PPV) AS end_date FROM
										(
											SELECT PPV1_date AS PPV FROM `event` WHERE ID="'.$rsX['ID'].'"
											UNION ALL
											SELECT PPV2_date FROM `event` WHERE ID="'.$rsX['ID'].'"
											UNION ALL
											SELECT PPV3_date FROM `event` WHERE ID="'.$rsX['ID'].'"
											UNION ALL
											SELECT PPV4_date FROM `event` WHERE ID="'.$rsX['ID'].'"
											UNION ALL
											SELECT TZBJ_date FROM `event` WHERE ID="'.$rsX['ID'].'"
											UNION ALL
											SELECT ZPOP_date FROM `event` WHERE ID="'.$rsX['ID'].'"
										)
										AS a';
								$result2=mysqli_query ($db_connect, $sql);
								$rsB=mysqli_fetch_array($result2);
                            ?>
                        	<? if ($rsB['end_date']<>'0000-00-00') echo date('d.m.Y', strtotime($rsB['end_date']))?>
                        </td>
                        <? 	switch ($rsX['status']) {
								case '0': 
									echo '<td class="statusOrange">Nový</td>';
									break;
								case '1': 
									echo '<td class="statusDGreen">Registrace</td>';
									break;
								case '2':
									echo '<td class="statusLBlue">Aktivní + Registrace</td>';
									break;
								case '3':
									echo '<td class="statusDBlue">Aktivní</td>';
									break;
								case '4':
									echo '<td class="statusGrey">Archiv</td>';
									break;									
							}
						?>
                	</tr>
                <? endwhile?>
                
            </table>
        </div>
	</div>    
    
    
</div>