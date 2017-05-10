<?
if ($_GET['id']<>'') {
	$sql='SELECT event.*, venue.* FROM event JOIN venue ON event.venue=venue.ID WHERE event.ID="'.$_GET['id'].'"';
	$result=mysqli_query ($db_connect, $sql);
	$rsX=mysqli_fetch_array($result);
}
?>


<div class="container-fluid">
	<div class="page-header">
		<h1><small><? echo $_SESSION['loc']?> | Admin | Management kurzů</small><br />Editace kurzu</h1>
	</div>
   	<div class="col-md-8">
    	<form action="?menu=event&action=update&id=<? echo $_GET['id']?>" method="post">
            <table class="table table-bordered">
                <tr>
                    <th class="bg-info">Místo</th>
                    <td>
                    	<select class="form-control" name="loc">
                        	<option value="<?=$_SESSION['loc']?>"><?=$_SESSION['loc']?></option>
                        </select>
					</td>
                </tr>
                <tr>
                    <th class="bg-info">Status</th>
                    <td>
                    	<? 	/*ochrana - jen 1 evet ve statusu Reg Open*/
							$sql='SELECT COUNT(ID) AS open FROM event WHERE loc="'.$_SESSION['loc'].'" AND status>0 AND status<3';
							$result=mysqli_query ($db_connect, $sql);
							$rsOp=mysqli_fetch_array($result);
						?>
                    	<select class="form-control" name="status">
                        	<option value="0" <? if($rsX['status']=='0') echo 'selected'?>>Nový</option>                         
                            <? if (($rsOp['open']==0) OR (($rsX['status']>0) AND ($rsX['status']<3))):?>
	                            <option value="1" <? if($rsX['status']=='1') echo 'selected'?>>Registrace</option>
                                <option value="2" <? if($rsX['status']=='2') echo 'selected'?>>Aktivní + registrace</option>
							<? endif?>                        	
                            <option value="3" <? if($rsX['status']=='3') echo 'selected'?>>Aktivní</option>
                            <option value="4" <? if($rsX['status']=='4') echo 'selected'?>>Archiv</option>
                        </select>
					</td>
                </tr>
                <tr>
                	<th class="bg-info">Místo</th>
                    <td>
                    	<? 	/*venue*/
							$sql='SELECT * FROM venue WHERE loc="'.$_SESSION['loc'].'"';
							$result=mysqli_query ($db_connect, $sql);
							$rsV=mysqli_fetch_array($result);
						?>                    
                    	<select class="form-control" name="venue">
                        	<option value="<?=$rsV['ID']?>" <? if($rsX['venue']==$rsV['ID']) echo 'selected'?>><?=$rsV['venue']?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="bg-info">Zahájení</th>
                    <td><input name="start_date" type="text" class="form-control jqdatepicker" value="<? if (($_GET['id']<>NULL) AND ($rsX['start_date']<>'0000-00-00')) echo date('d.m.Y', strtotime ($rsX['start_date']))?>"/></td>
                    <td><input name="start_time" type="text" class="form-control jqtimepicker" value="<? if (($_GET['id']<>'') AND ($rsX['start_time']<>'00:00:00')) echo $rsX['start_time']?>"/></td>
                </tr>
                <tr>
                    <th>PPV 1</th>
                    <td><input name="PPV1_date" type="text" class="form-control jqdatepicker" value="<? if (($_GET['id']<>'') AND ($rsX['PPV1_date']<>'0000-00-00')) echo date('d.m.Y', strtotime ($rsX['PPV1_date']))?>"/></td>
                    <td><input name="PPV1_time" type="text" class="form-control jqtimepicker" value="<? if (($_GET['id']<>'') AND ($rsX['PPV1_time']<>'00:00:00')) echo $rsX['PPV1_time']?>"/></td>
                </tr>
                <tr>
                    <th>PPV 2</th>
                    <td><input name="PPV2_date" type="text" class="form-control jqdatepicker" value="<? if (($_GET['id']<>'') AND ($rsX['PPV2_date']<>'0000-00-00')) echo date('d.m.Y', strtotime ($rsX['PPV2_date']))?>"/></td>
                    <td><input name="PPV2_time" type="text" class="form-control jqtimepicker" value="<? if (($_GET['id']<>'') AND($rsX['PPV2_time']<>'00:00:00')) echo $rsX['PPV2_time']?>"/></td>
                </tr>
                <tr>
                    <th>PPV 3</th>
                    <td><input name="PPV3_date" type="text" class="form-control jqdatepicker" value="<? if (($_GET['id']<>'') AND ($rsX['PPV3_date']<>'0000-00-00')) echo date('d.m.Y', strtotime ($rsX['PPV3_date']))?>"/></td>
                    <td><input name="PPV3_time" type="text" class="form-control jqtimepicker" value="<? if (($_GET['id']<>'') AND ($rsX['PPV3_time']<>'00:00:00')) echo $rsX['PPV3_time']?>"/></td>
                </tr>
                <tr>
                    <th>PPV 4</th>
                    <td><input name="PPV4_date" type="text" class="form-control jqdatepicker" value="<? if (($_GET['id']<>'') AND ($rsX['PPV4_date']<>'0000-00-00')) echo date('d.m.Y', strtotime ($rsX['PPV4_date']))?>"/></td>
                    <td><input name="PPV4_time" type="text" class="form-control jqtimepicker" value="<? if (($_GET['id']<>'') AND ($rsX['PPV4_time']<>'00:00:00')) echo $rsX['PPV4_time']?>"/></td>
                </tr>
                <tr>
                    <th>TZBJ</th>
                    <td><input name="TZBJ_date" type="text" class="form-control jqdatepicker" value="<? if (($_GET['id']<>'') AND ($rsX['TZBJ_date']<>'0000-00-00')) echo date('d.m.Y', strtotime ($rsX['TZBJ_date']))?>"/></td>
                    <td><input name="TZBJ_time" type="text" class="form-control jqtimepicker" value="<? if (($_GET['id']<>'') AND ($rsX['TZBJ_time']<>'00:00:00')) echo $rsX['TZBJ_time']?>"/></td>
                </tr>
                <tr>
                    <th>ZPOP</th>
                    <td><input name="ZPOP_date" type="text" class="form-control jqdatepicker" value="<? if (($_GET['id']<>'') AND ($rsX['ZPOP_date']<>'0000-00-00')) echo date('d.m.Y', strtotime ($rsX['ZPOP_date']))?>"/></td>
                    <td><input name="ZPOP_time" type="text" class="form-control jqtimepicker" value="<? if (($_GET['id']<>'') AND ($rsX['ZPOP_time']<>'00:00:00')) echo $rsX['ZPOP_time']?>"/></td>
                </tr>
            </table>
           
            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;Uložit</button>
            <a href="?menu=event" class="btn btn-primary"><span class="glyphicon glyphicon-remove"></span>&nbsp;Zrušit změny</a>                    
        </form>
   </div>   
</div>