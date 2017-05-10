<div class="container-fluid">
	<div class="page-header">
		<h1><small>Nástroje</small><br />Vozová kniha</h1>		
	</div>



    <div id="ucitel_vykaz" class="col-md-8">
    	<form action="" method="get">
	        <input type="hidden" name="menu" value="vozova_kniha">
            <table class="tablesorter table table-bordered table-responsive table-striped table-condensed">
                <tr class="active">
                    <th>Vozidlo</th>
                    <th>Od</th>
                    <th>Do</th>
                    <th></th>
                </tr>
                <tr class="active">
                    <td>
                        <?
                            $sql='SELECT GUID, rz, znacka, typ FROM vehicle';
                            $rs=mysqli_query ($db_connect, $sql);
                        ?>
                        <select name="vehicle-id" class="form-control">
                            <? while ($rsX=mysqli_fetch_array($rs)): ?>
                                <option value="<?=$rsX['GUID']?>" <? if ($rsX['GUID']==$_GET['vehicle-id']) echo 'selected'?>><?=$rsX['znacka'].' '.$rsX['typ'].' '.$rsX['rz']?></option>
                            <? endwhile?>
                        </select>                
                    </td>
                    <td><input name="datum_od" type="text" class="form-control jqdatepicker" value="<? echo $_GET['datum_od']?>"></td>
                    <td><input name="datum_do" type="text" class="form-control jqdatepicker" value="<? echo $_GET['datum_do']?>"></td>
                    <td><button type="submit" name="submit" value="ok" class="btn btn-default"><span class="glyphicon glyphicon-filter"></span></button></td>
                </tr>
            </table>
        </form>
        
        <? if (!isset($_GET['submit'])) die;?>
        <table class="table table-condensed">
        	<thead>
                <th>Datum</th>
                <th>Čas</th>
                <th>Km</th>
                <th>Ev.č.</th>
                <th>Příjmení a jméno</th>
                <th>Instr.</th>
                <th>Podpis žáka</th>
			</thead>
            <tbody>
            	<?
					if ($_GET['datum_do']='1.1.1970') $_GET['datum_do']=date('d.m.Y');
					$sql='	SELECT
							a.UID, a.prijmeni, a.jmeno,
							b.datum, b.cas_od, b.cas_do, b.km_od, b.km_do, b.signature,
							c.jmeno AS instruktor
							FROM 
								matrika a,
								tk_pj b,
								uzivatel c
							WHERE
								a.GUID=b.GUID
								AND b.ucitel=c.UZid
								AND b.GUID_vehicle="'.$_GET['vehicle-id'].'"								
								AND b.datum>="'.date('Y-m-d', strtotime($_GET['datum_od'])).'"
								AND b.datum<="'.date('Y-m-d', strtotime($_GET['datum_do'])).'"
							ORDER BY b.datum, b.cas_od
					';
					$result=mysqli_query($db_connect,$sql);
					echo $sql;
				?>
                
                <? while ($rsX=mysqli_fetch_array($result)):?>
                	<tr>
                    	<td><?=date('d.m.Y', strtotime($rsX['datum']))?></td>
                        <td>
                        	<? if ($rsX['cas_od']<>$rsX['cas_do']):?>
								<?=date('H:i', strtotime($rsX['cas_od']))?>-<?=date('H:i',strtotime($rsX['cas_do']))?>
                            <? endif?>
                        </td>
                        <td><?=$rsX['km_od']?>-<?=$rsX['km_do']?></td>
                        <td><?=$rsX['UID']?></td>
                        <td><?=strtoupper_all($rsX['prijmeni'])?> <?=$rsX['jmeno']?></td>
                        <td><?=$rsX['instruktor']?></td>
                        <td><img src=<?=$rsX['signature']?> width="100px"/></td>
                    </tr>
                <? endwhile?>
            </tbody>
        </table>
    </div>
</div>