<?php 
	ini_set('session.gc_maxlifetime', 3600);
	session_set_cookie_params(3600);
	session_start();

	if ((!isset($_COOKIE['userID'])) OR ($_COOKIE['userID']=='')) {
		unset($_SESSION);
		session_destroy();
	}
	
	if (isset($_POST['backdoor'])) include 'login_process.php';
	
	include 'inc_db_connect.php';
	include 'inc_library.php';

	/*NASTAVEI GLOBALNICH POLI*/
	$_SESSION['osnovaZK']=osnovaZK();
	if (!isset($_SESSION['loc']) OR ($_SESSION['loc']=='')) $_SESSION['loc']=$_COOKIE['branch'];
	if (isset($_GET['branch'])) $_SESSION['loc']=$_GET['branch'];
	
	
	$arrayUser=arrayUser();
	$osnova=osnova();
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>autoškolaeasy | bridge management system</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='https://fonts.googleapis.com/css?family=Work+Sans:400,200&subset=latin-ext' rel='stylesheet' type='text/css'>

	<!--BOOTSTRAP-->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!--<link href="css/bootstrap-theme.css" rel="stylesheet">-->
    <link href="css/admincss.css" rel="stylesheet">
    <link href="css/print.css" rel="stylesheet" type="text/css"  media="print">

	<!--JQUERY-->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery"></script>
	<script src="js/bootstrap.min.js"></script>
    
    <!--JQUERY SPECIALS-->
    <link href="jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
    <link href="jquery-ui-1.11.4.custom/jquery.ui.timepicker.css" rel="stylesheet">
  	<script src="jquery-ui-1.11.4.custom/jquery-ui.js"></script>
    <script src="jquery-ui-1.11.4.custom/jquery.ui.timepicker.js"></script>
    
    <script src="js/myjquery.js"></script>
    
    <!--Tablesorter-->
    <link href="plugins/tablesorter/theme.default.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="plugins/tablesorter/jquery.tablesorter.js"></script>
  	<script type="text/javascript" src="plugins/tablesorter/widget-filter.js"></script>
    <script type="text/javascript" src="plugins/tablesorter/widget-stickyHeaders.js"></script>

<? include 'inc_script.php'?>
</head>



<body>
  <?
 	if (!isset($_COOKIE['userID']) or ($_COOKIE['userID']=='') ) {
		include 'inc_login.php';
		die;
	}
	
 	else {
		
		include 'inc_menu.php';
		
		if ((!isset($_GET['menu'])) OR ($_GET['menu'] == 'dashboard')) include 'inc_dashboard.php';
		
		if ($_GET['menu'] == 'event') include 'inc_event.php';
		if ($_GET['menu'] == 'event_edit') include 'inc_event_edit.php';
		if ($_GET['menu'] == 'hlasenky') include 'inc_hlasenky.php';
		if ($_GET['menu'] == 'hlasenky_detail') include 'inc_hlasenky_detail.php';
		if ($_GET['menu'] == 'registrace') include 'inc_registrace.php';
		if ($_GET['menu'] == 'registrace_edit') include 'inc_registrace_edit.php';
		if ($_GET['menu'] == 'karta_zaka') include 'inc_karta_zaka.php';
		if ($_GET['menu'] == 'matrika') include 'inc_matrika.php';
		if ($_GET['menu'] == 'matrika_edit') include 'inc_matrika_edit.php';
		if ($_GET['menu'] == 'platby') include 'inc_platby.php';
		if ($_GET['menu'] == 'platby_detail') include 'inc_platby_detail.php';	
		if ($_GET['menu'] == 'praxe') include 'inc_praxe.php';
		if ($_GET['menu'] == 'praxe_edit') include 'inc_praxe_edit.php';
		if ($_GET['menu'] == 'zkousky') include 'inc_zkousky.php';
		if ($_GET['menu'] == 'zkousky_seznam') include 'inc_zkousky_seznam.php';
		if ($_GET['menu'] == 'zkousky_edit') include 'inc_zkousky_edit.php';
		if ($_GET['menu'] == 'zkousky_zaci') include 'inc_zkousky_zaci.php';
		if ($_GET['menu'] == 'zkousky_vysledky') include 'inc_zkousky_vysledky.php';
		
		if ($_GET['menu'] == 'tridni_kniha') include 'inc_tridni_kniha.php';
		if ($_GET['menu'] == 'vozova_kniha') include 'inc_vozova_kniha.php';
		
		if ($_GET['menu'] == 'ucitel_invoice_edit') include 'inc_ucitel_invoice_edit.php';
		if ($_GET['menu'] == 'vykaz') include 'inc_vykaz.php';
		
		if ($_GET['menu'] == 'ucitel_pokladna') include 'inc_ucitel_pokladna.php';
		
		if ($_GET['menu'] == 'reporting') include 'inc_reporting.php';
		if ($_GET['menu'] == 'registr_user') include 'inc_registr_user.php';
		if ($_GET['menu'] == 'registr_user_edit') include 'inc_registr_user_edit.php';
		if ($_GET['menu'] == 'registr_venue') include 'inc_registr_venue.php';
		if ($_GET['menu'] == 'registr_vehicle') include 'inc_registr_vehicle.php';
		if ($_GET['menu'] == 'registr_vehicle_edit') include 'inc_registr_vehicle_edit.php';
		if ($_GET['menu'] == 'journal_login') include 'inc_journal_login.php';
	}
  ?>
  
  
  






 

</body>
</html>
