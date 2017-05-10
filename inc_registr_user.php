<!-- Tady se spousti tablesort + tablefilter -->
<script>
	$(function(){
		$('.tablesorter').tablesorter({
			widgets        : ['filter','stickyHeaders'],
			
			usNumberFormat : false,
			sortReset      : true, 
			sortRestart    : true
		});
	});
</script>
<?
	if (isset($_POST['update'])) {
		$sql='UPDATE uzivatel SET 
		jmeno="'.$_POST['jmeno'].'",
		inicial="'.$_POST['inicial'].'",
		narozen="'.date('Y-m-d', strtotime($_POST['narozen'])).'",
		adresa="'.$_POST['adresa'].'",
		tel="'.$_POST['tel'].'",
		mail="'.$_POST['mail'].'",
		login="'.$_POST['login'].'",
		heslo="'.$_POST['heslo'].'"		
		WHERE UZid="'.$_POST['UZid'].'"';

		mysqli_query ($db_connect, $sql);
		
		$arrayUser=arrayUser();
	}
	
	if (isset($_GET['action'])) {
		switch ($_GET['action']) {
			case version_reset:
				$sql='UPDATE uzivatel SET version_trigger=1';
				mysqli_query ($db_connect, $sql);
				break;
		}
	}
?>


<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Supervisor</small><br />Registr uživatelů</h1>
        <? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>
	</div>
    
    <a href="?menu=registr_user&action=version_reset" class="btn btn-primary">Reset verze</a>
    <br clear=all><br>
    <div class="table-responsive">
        <table class="table table-responsive table-hover tablesorter">
            <thead>
				<tr><td class="nosort"><br><br></td></tr>
                <tr>
                    <th class="bg-info filter-false"></th>
                    <th class="bg-info filter-false">UZid</th>
                    <th class="bg-info filter-false">Jméno</th>
                    <th class="bg-info filter-false">Telefon<br>E-mail</th>
                    <th class="bg-warning filter-false">Supervisor</th>
                    <th class="bg-warning filter-false">Admin</th>
                    <th class="bg-warning filter-false">Pokladna</th>
                    <th class="bg-warning filter-false">Instr A</th>
                    <th class="bg-warning filter-false">Instr B</th>
                    <th class="bg-warning filter-false">Liberec</th>
                    <th class="bg-warning filter-false">Praha</th>
                    <th class="bg-warning filter-false">Jablonec</th>
                    <th class="bg-warning filter-false">Kontraktor</th>
                    <th class="bg-warning filter-false">Aktivní</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($arrayUser as $user):?>
                    <tr id="<? echo $user['UZid']?>" class="<? if($user['aktivni']==0) echo 'active'?>">
                        <td><a href="/?menu=registr_user_edit&UZid=<? echo $user['UZid']?>" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a></td>
                        <td><? echo $user['UZid']?></td>
                        <td><? echo $user['jmeno']?></td>
                        <td></td>
                        <td><input id="supervisor" type="checkbox" class="switch_prava form-control" <? if($user['pravo-supervisor']) echo 'checked'?> <? if($user['UZid']==1)echo 'disabled'?>></td>
                        <td><input id="admin" type="checkbox" class="switch_prava form-control" <? if($user['pravo-admin']) echo 'checked'?>></td>
                        <td><input id="pokladna" type="checkbox" class="switch_prava form-control" <? if($user['pravo-pokladna']) echo 'checked'?>></td>
                        <td><input id="instr_A" type="checkbox" class="switch_prava form-control" <? if($user['pravo-instr_A']) echo 'checked'?>></td>
                        <td><input id="instr_B" type="checkbox" class="switch_prava form-control" <? if($user['pravo-instr_B']) echo 'checked'?>></td>
                        <td><input id="liberec" type="checkbox" class="switch_prava form-control" <? if($user['pravo-liberec']) echo 'checked'?>></td>
                        <td><input id="praha" type="checkbox" class="switch_prava form-control" <? if($user['pravo-praha']) echo 'checked'?>></td>
                        <td><input id="jablonec" type="checkbox" class="switch_prava form-control" <? if($user['pravo-jablonec']) echo 'checked'?>></td>
                        <td><input id="kontraktor" type="checkbox" class="switch_prava form-control" <? if($user['pravo-kontraktor']) echo 'checked'?>></td>
                        <td><input id="aktivni" type="checkbox" class="switch_prava form-control" <? if($user['aktivni']) echo 'checked'?>></td>
                    </tr>            
                <? endforeach?>
            </tbody>
            <tr>
                <td><button class="button_new_user btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button></td>
                <td colspan="2"><input id="new_user_name" type="text" class="form-control"/></td>
            </tr>
        </table>
	</div>
</div>