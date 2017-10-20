<? 
	if ($_SERVER['HTTP_HOST']=='dev.autoskolaeasy.cz') {
		$db_connect = mysqli_connect ('mysql5-4','dev.107299','matysek2009','dev_107299') or die('Nenavazane spojeni se serverem!');
	}
	if ($_SERVER['HTTP_HOST']=='admin.autoskolaeasy.cz') {
		$db_connect = mysqli_connect ('mysql5-4','aseasy.107299','matysek2009','aseasy_107299') or die('Nenavazane spojeni se serverem!');
	}	
	$result = mysqli_query ($db_connect, "SET NAMES 'utf8'");
?>