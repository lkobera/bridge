<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='		UPDATE tk_pj
				SET '.$_POST['column'].'="'.$_POST['value'].'"
				WHERE ID="'.$_POST['ID'].'"';

	mysqli_query ($db_connect, $sql);
	

?>
