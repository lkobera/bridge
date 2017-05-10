<?
	session_start();
	include '../inc_db_connect.php';

	$sql='UPDATE matrika 
		SET 
			'.$_GET['column'].'="'.$_GET['value'].'",
			
			status=CASE
			 	WHEN status=1 THEN 2
				ELSE status
			END
			
			WHERE GUID="'.$_GET['GUID'].'"';
			echo $sql;
	mysqli_query ($db_connect, $sql);
	
?>