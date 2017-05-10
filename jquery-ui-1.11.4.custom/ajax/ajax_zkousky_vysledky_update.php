<?

	session_start();
	include '../inc_db_connect.php';

	$sql='UPDATE zkousky_terminy SET';
	if (isset ($_GET['absence'])) $sql.=' resultPPV="'.$_GET['absence'].'", locked="1"'; /*je-li absence, lockne se zaznam*/

	if (isset ($_GET['resultPPV'])) $sql.=' resultPPV="'.$_GET['resultPPV'].'"';
	if (isset ($_GET['resultPJ_A'])) $sql.=' resultPPV="'.$_GET['resultPJ_A'].'", ucitel_A="'.$_GET['ucitel_A'].'"';
	if (isset ($_GET['resultPJ_B'])) $sql.=' resultPPV="'.$_GET['resultPJ_B'].'", ucitel_B="'.$_GET['ucitel_B'].'"';
	
	if (isset ($_GET['locked'])) $sql.=' locked="'.$_GET['locked'].'"';
	
	$sql.=' WHERE ID="'.$_GET['ID'].'"';
	
	/*mysqli_query ($db_connect, $sql);*/
	

?>