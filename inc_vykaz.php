<?

	/*nastaveni defaultniho pohledu podle prav uzivatele*/
	if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1) {
			if (isset($_GET['ucitel'])) $_SESSION['ucitel']=$_GET['ucitel'];
	}
		
	else $_SESSION['ucitel']=$_COOKIE['userID'];	


?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small>Nástroje</small><br />Měsíční výkaz práce</h1>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1)
				OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>        
	</div>
	<? if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1):?>
	    <div class="col-md-2 col-sm-4">
		    <select class="form-control" id="vykaz_select_ucitel">
				<?php option_ucitel($_SESSION['ucitel']);?>
			</select>
	        <br>
		</div>
	<? endif?>
    
    
</div>

<div id="ucitel_vykaz" class="col-md-6">
    <? include 'ajax/ajax_ucitel_vykaz_table.php'?>
</div>
