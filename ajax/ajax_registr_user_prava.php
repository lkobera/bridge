<?
	session_start();
	include '../inc_db_connect.php';

	if ($_GET['column']<>'aktivni') $sql='UPDATE uzivatel_prava SET '.$_GET['column'].'= NOT '.$_GET['column'].' WHERE UZid="'.$_GET['UZid'].'"';
	else $sql='UPDATE uzivatel SET '.$_GET['column'].'= NOT '.$_GET['column'].' WHERE UZid="'.$_GET['UZid'].'"'; /*aktivni je v tab uzivatel proto jinyu query*/

	echo $sql;
	mysqli_query ($db_connect, $sql);
?>