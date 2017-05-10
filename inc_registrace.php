<!-- Tady se spousti tablesort + tablefilter -->

<?
/*NASTAVENI DEFAULTNIHO POHLEDU*/
if (!isset($_GET['request'])) $_GET['request']=0;

/*UKLADANI Z EDITACE*/
	if (isset($_POST['registrace_save'])) {
		
		/*zapis nove registrace - zapisu prazdny radek se statusem 9, zeptam se na jeho GUID, vratim mu status 0 a pak ho poslu dal k updatu pres GUID*/
		if ($_POST['registrace_save']=='') {
			$sql='INSERT INTO registrace (GUID, datum, loc, ApplicationStatus) VALUES (UUID(),NOW(), "'.$_SESSION['loc'].'", 9)';
			mysqli_query($db_connect,$sql);
			$sql='SELECT GUID FROM registrace WHERE ApplicationStatus=9 LIMIT 1';
			$result=mysqli_query($db_connect,$sql);
			$radek=mysqli_fetch_array($result);
			$_POST['registrace_save']=$radek['GUID'];
			$sql='UPDATE registrace SET ApplicationStatus=0 WHERE GUID="'.$radek['GUID'].'"';
			mysqli_query($db_connect,$sql);
		}
		
		$sql='	UPDATE registrace SET
				prijmeni="'.$_POST['prijmeni'].'"
				,loc="'.$_POST['loc'].'"
				,jmeno="'.$_POST['jmeno'].'"
				,narozen="'.date('Y-m-d', strtotime($_POST['narozen'])).'"
				,adresa1="'.$_POST['adresa1'].'"
				,cpopisne="'.$_POST['cpopisne'].'"
				,adresa2="'.$_POST['adresa2'].'"
				,psc="'.$_POST['psc'].'"
				,tel="'.$_POST['tel'].'"
				,mail="'.$_POST['mail'].'"
				,rc="'.$_POST['rc'].'"
				,cOP="'.$_POST['cOP'].'"
				,mistonar="'.$_POST['mistonar'].'"
				,obcanstvi="'.$_POST['obcanstvi'].'"
				,rp="'.$_POST['rp'].'"
		
				,maAM="'.$_POST['maAM'].'"
				,maA1="'.$_POST['maA1'].'"
				,maA2="'.$_POST['maA2'].'"
				,maA="'.$_POST['maA'].'"
				,maB="'.$_POST['maB'].'"
				,maC="'.$_POST['maC'].'"
				,maD="'.$_POST['maD'].'"
				,maE="'.$_POST['maE'].'"
				,maT="'.$_POST['maT'].'"
		
				,request="'.$_POST['request'].'"
		
				,chceAM="'.$_POST['chceAM'].'"
				,chceA1="'.$_POST['chceA1'].'"
				,chceA2="'.$_POST['chceA2'].'"
				,chceA="'.$_POST['chceA'].'"
				,chceB="'.$_POST['chceB'].'"
				WHERE GUID="'.$_POST['registrace_save'].'"';

		mysqli_query($db_connect,$sql);
	}



?>



<div class="container-fluid">
	<div class="page-header">
		<h1><small><?php echo $_SESSION['loc']?> | Admin</small><br />Registrace žáků</h1>
		<? /*  ****************************PRAVA HLAVNI PRISTUP****************************** */
			if (
				($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) 
				OR ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1)
				);
			else {echo '<p class="bg-danger">Neoprávněný přístup!</p>'; die;}
		?>        
	</div>
    
   		<!--menu pro desktop-->
    	<div class="hidden-xs btn-group btn-group-justified ">
    		<a href="?menu=registrace&request=0" class="btn <? if ($_GET['request']==0):?>btn-primary active<? endif?><? if ($_GET['request']<>0):?>btn-info<? endif?>"><span class="glyphicon glyphicon-tag"></span>&nbsp;Nový výcvik</a>
    		<a href="?menu=registrace&request=1" class="btn <? if ($_GET['request']==1):?>btn-primary active<? endif?><? if ($_GET['request']<>1):?>btn-info<? endif?>"><span class="glyphicon glyphicon-tag"></span>&nbsp;Přezkoušení po zákazu</a>
	        <a href="?menu=registrace&request=2" class="btn <? if ($_GET['request']==2):?>btn-primary active<? endif?><? if ($_GET['request']<>2):?>btn-info <? endif?> disabled"><span class="glyphicon glyphicon-tag"></span>&nbsp;Kondiční výcvik</a>
       	</div>
        
        <!--menu pro mobily-->
    	<div class="visible-xs btn-group btn-block btn-group-vertical ">
    		<a href="?menu=registrace&request=0" class="btn btn-lg <? if ($_GET['request']==0):?>btn-primary active<? endif?><? if ($_GET['request']<>0):?>btn-info<? endif?>"><span class="glyphicon glyphicon-tag"></span>&nbsp;Nový výcvik</a>
    		<a href="?menu=registrace&request=1" class="btn btn-lg <? if ($_GET['request']==1):?>btn-primary active<? endif?><? if ($_GET['request']<>1):?>btn-info<? endif?>"><span class="glyphicon glyphicon-tag"></span>&nbsp;Přezkoušení po zákazu</a>
	        <a href="?menu=registrace&request=2" class="btn btn-lg <? if ($_GET['request']==2):?>btn-primary active<? endif?><? if ($_GET['request']<>2):?>btn-info<? endif?> disabled"><span class="glyphicon glyphicon-tag"></span>&nbsp;Kondiční výcvik</a>
       	</div>        
  
    <br><br>
    <div id="registrace_content">
		<?
            if (isset($_GET['request'])) {
                if (isset($_GET['request'])) include 'ajax/ajax_registrace_vycvik.php';
            }
        ?>
    </div>
	

</div>


