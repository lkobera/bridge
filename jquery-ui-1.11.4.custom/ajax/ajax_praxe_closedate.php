<?
	session_start();
	include '../inc_db_connect.php';
	
	
	$sql='UPDATE matrika SET status="4", ukoncen="'.date('Y-m-d', strtotime($_GET['closedate'])).'" WHERE GUID="'.$_GET['GUID'].'"';
	mysqli_query ($db_connect, $sql);

?>