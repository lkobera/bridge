<?
	session_start();
	include_once $_GET['inc_path'].'inc_db_connect.php';
	include_once $_GET['inc_path'].'inc_library.php';
	
	$sql='	SELECT
			zkousky_terminy.*, SUM(zkousky_zaci.PPV) AS usedPPV, SUM(zkousky_zaci.PJ_A) AS usedPJ_A, SUM(zkousky_zaci.PJ_B) AS usedPJ_B, zkousky_venue.adresa
			FROM zkousky_terminy
			LEFT JOIN zkousky_zaci
			ON zkousky_terminy.id=zkousky_zaci.ZKid
			LEFT JOIN zkousky_venue
			ON zkousky_venue.ID=zkousky_terminy.venue_ID
			WHERE zkousky_terminy.loc="'.$_SESSION['loc'].'"
			AND datum="'.$_GET['datum'].'"
			GROUP BY zkousky_terminy.id';
	$result=mysqli_query ($db_connect, $sql);

?>



<table class="table table-bordered">
<? while ($radek=mysqli_fetch_array($result)):?>
	<tr class="<? if ($radek['locked']==1) echo 'active'?>">
		<? if ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1):?>
			<td>
			  <!--SetUp a Lock zkousek je pristupny podle prav-->
				<? if ($radek['locked']==1):?><!--zamceno-->
					<a id="edit<? echo $radek['id'] ?>" href="?menu=zkousky_edit&id=<? echo $radek['id']?>" class="btn btn-info disabled"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
       	         	<button value="<? echo $radek['id']?>" class="zkousky_lock btn btn-default"><span class="glyphicon glyphicon-lock"></span></button>
           		<? endif?>
            
           		<? if ($radek['locked']==0):?><!--odemceno-->
               		<a id="edit<? echo $radek['id'] ?>" href="?menu=zkousky_edit&id=<? echo $radek['id']?>" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;
               		<button value="<? echo $radek['id']?>" class="zkousky_lock btn btn-info"><span class="glyphicon glyphicon-lock"></span></button>
           		<? endif?>
			</td>
   		<? endif?>

	  
		<?
			if ($radek['usedPPV']==NULL) $radek['usedPPV']=0;
			if ($radek['usedPJ_A']==NULL) $radek['usedPJ_A']=0;
			if ($radek['usedPJ_B']==NULL) $radek['usedPJ_B']=0;
		?>


	  </td>
	  <td><? echo $radek['cas'].'<br>'.$radek['adresa']?></td>
		  <td><!--Capacity Badge-->
		  <span class="badge 
			  <? if ($radek['PPV']<$radek['usedPPV']) echo ' statusRed'?>
			  <? if ($radek['PPV']==$radek['usedPPV']) echo ' statusOrange'?>
			  <? if ($radek['PPV']>$radek['usedPPV']) echo ' statusGreen'?>
			  ">PPV 
			  <? echo $radek['usedPPV'].'/'.$radek['PPV']?>
		  </span>
		  <span class="badge
			  <? if ($radek['PJ_A']-$radek['usedPJ_A']<0) echo ' statusRed'?>
			  <? if ($radek['PJ_A']-$radek['usedPJ_A']==0) echo ' statusOrange'?>
			  <? if ($radek['PJ_A']-$radek['usedPJ_A']>0) echo ' statusGreen'?>
			  ">PJ A 
			  <? echo $radek['usedPJ_A'].'/'.$radek['PJ_A']?>
		  </span>
		  <span class="badge
			  <? if ($radek['PJ_B']-$radek['usedPJ_B']<0) echo ' statusRed'?>
			  <? if ($radek['PJ_B']-$radek['usedPJ_B']==0) echo ' statusOrange'?>
			  <? if ($radek['PJ_B']-$radek['usedPJ_B']>0) echo ' statusGreen'?>
			  ">PJ B 
			  <? echo $radek['usedPJ_B'].'/'.$radek['PJ_B']?>
		  </span>
	  </td>
	  <td>
		  <a href="?menu=zkousky_seznam&id=<? echo $radek['id']?>" class="btn btn-info"><span class="glyphicon glyphicon-th-list"></span></a>
	  </td>
  </tr>
<? endwhile?>
</table>