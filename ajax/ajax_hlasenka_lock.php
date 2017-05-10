<? 
	session_start();
	include '../inc_db_connect.php';
	
	$sql='	UPDATE
			slozky
			SET
				zahajeni="'.date('Y-m-d', strtotime($_GET['datum'])).'",
				autor="'.$_GET['autor'].'",
				status="1"
			WHERE folderID="'.$_GET['folderID'].'"';
	mysqli_query ($db_connect, $sql);
	
	
	
	
	$sql='	UPDATE
			matrika
			SET
				status="1",
				zahajen="'.date('Y-m-d', strtotime($_GET['datum'])).'"
			WHERE folderID="'.$_GET['folderID'].'"';
	mysqli_query ($db_connect, $sql);
	
	
?>