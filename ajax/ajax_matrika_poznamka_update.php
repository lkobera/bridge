<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='	UPDATE
			matrika
			SET 
				poznamka="'.$_GET['text'].'",
				poznamka_ext="'.$_GET['text_ext'].'"
			WHERE GUID="'.$_GET['GUID'].'"';
			mysqli_query($db_connect,$sql);
?>
