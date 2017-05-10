<?
	session_start();

	
	include_once 'inc_db_connect.php';
	include_once 'inc_library.php';
	if (!isset($_GET['dateshift'])) $_GET['dateshift']=1;
	
	/*SELECT terminu zkouskek*/
	$sql='	SELECT
			* FROM zkousky_terminy
			WHERE loc="'.$_SESSION['loc'].'"
			AND datum<="'.date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y'))).'"
			AND datum>="'.date('Y-m-d', mktime(0, 0, 0, date('m')-$_GET['dateshift'], date('d'), date('Y'))).'"
			ORDER BY datum DESC';
	$result=mysqli_query ($db_connect, $sql);
?>

<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Zkoušky</small><br />Výsledky zkoušek</h1>
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
    <div class="accordion">
    	<? while($radek=mysqli_fetch_array($result)): ?>
        	<h3>
				<? if ($radek['locked']==1 ):?><span class="glyphicon glyphicon-lock"></span><? endif?>
				<? echo date('d.m.Y', strtotime($radek['datum'])).'&nbsp;'.date('G:i', strtotime($radek['cas'])).'&nbsp;'.$radek['loc']?>
			</h3>
			<div>
				<div id="<? echo $radek['id']?>" class="zkousky_list">
                    <? 	$_GET['ZKid']=$radek['id'];
						include 'ajax/ajax_zkousky_vysledky_table.php';
					?>
				</div>
            </div>
        <? endwhile?>
	</div>


    <div class="well">
        <a href="?menu=zkousky_vysledky&dateshift=<? echo $_GET['dateshift']+1?>" class="btn btn-info"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Načíst starší</a>
    </div>
</div>
