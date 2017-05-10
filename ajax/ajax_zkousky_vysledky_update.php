<?

	session_start();
	include '../inc_db_connect.php';
	
	/*GUID*/
	$sql='SELECT GUID FROM zkousky_zaci WHERE ID="'.$_GET['ID'].'"';
	$result=mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
	
	
	
	/*************************ULOZIM VYSLEDEK DO ZAZNAMU ZKOUSKY**************************/
	if (($_GET['resultPPV']=='') OR ($_GET['resultPPV']=='undefined')) $_GET['resultPPV']='NULL';
	if (($_GET['resultPJ_A']=='') OR ($_GET['resultPJ_A']=='undefined')) $_GET['resultPJ_A']='NULL';
	if (($_GET['resultPJ_B']=='') OR ($_GET['resultPJ_B']=='undefined')) $_GET['resultPJ_B']='NULL';
	
	if (($_GET['ucitel_A']=='') OR ($_GET['ucitel_A']=='undefined')) $_GET['ucitel_A']='NULL';
	if (($_GET['ucitel_B']=='') OR ($_GET['ucitel_B']=='undefined')) $_GET['ucitel_B']='NULL';
	
	if ($_GET['repro']>0) $_GET['repro']=1; else $_GET['repro']=0;

	$sql='	UPDATE 
			zkousky_zaci 
			SET 
				resultPPV='.$_GET['resultPPV'].',
				resultPJ_A='.$_GET['resultPJ_A'].',
				resultPJ_B='.$_GET['resultPJ_B'].',
				ucitel_A='.$_GET['ucitel_A'].',
				ucitel_B='.$_GET['ucitel_B'].',
				locked=1,
				repro="'.$_GET['repro'].'"
			
			WHERE ID="'.$_GET['ID'].'"';

	mysqli_query ($db_connect, $sql);

	
	/***********************************UPDATUJU STAV V MATRICE***********************************/
	if ($_GET['resultPPV']<>'NULL') $set='PPV='.$_GET['resultPPV'].',';
	if ($_GET['resultPJ_A']<>'NULL') $set.='PJ_A='.$_GET['resultPJ_A'].',';
	if ($_GET['resultPJ_B']<>'NULL') $set.='PJ_B='.$_GET['resultPJ_B'].',';

	$sql=' 	UPDATE
			matrika
			SET
				'.$set.'
				status=5
			WHERE GUID="'.$radek['GUID'].'"';

	mysqli_query ($db_connect, $sql);
?>