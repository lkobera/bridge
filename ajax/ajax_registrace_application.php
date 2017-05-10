<?
	session_start();
	include '../inc_db_connect.php';
	$sql='SELECT ApplicationStatus FROM registrace WHERE GUID="'.$_GET['GUID'].'"';
	$result=mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
	
	if ($radek['ApplicationStatus']==3) $sql='UPDATE registrace SET ApplicationStatus=ApplicationStatus-1 WHERE GUID="'.$_GET['GUID'].'"';
	else $sql='UPDATE registrace SET ApplicationStatus=ApplicationStatus+1 WHERE GUID="'.$_GET['GUID'].'"';
	
	mysqli_query ($db_connect, $sql);
	
?>