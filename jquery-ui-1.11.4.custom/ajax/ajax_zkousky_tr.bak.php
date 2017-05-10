<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='	  	SELECT';
	$sql=$sql.' zkousky_terminy.*, COUNT(zkousky_zaci.PPV) AS usedPPV, COUNT(zkousky_zaci.PJ_A) AS usedPJ_A, COUNT(zkousky_zaci.PJ_B) AS usedPJ_B ';
	$sql=$sql.' FROM zkousky_terminy';
	$sql=$sql.' LEFT JOIN zkousky_zaci';
	$sql=$sql.' ON zkousky_terminy.id=zkousky_zaci.ZKid';
	$sql=$sql.' WHERE loc="'.$_SESSION['loc'].'"';
	$sql=$sql.' AND datum="'.$_GET['datum'].'"';
	$sql=$sql.' GROUP BY zkousky_terminy.id';
	$result=mysqli_query ($db_connect, $sql);
	

	$CZdate=date ('d.m.Y', strtotime($_GET['datum']));
	
?>

<div id="">
<tr class="info">
    <td><h3><? echo $CZdate?></h3></td>
    <td><button value="<? echo $_GET['datum']?>" class="zkousky_insert btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>
    <td>
        <table class="table table-bordered">
            <? while ($radek=mysqli_fetch_array($result)):?>
                <tr>
                    <td><a href="?menu=zkousky_edit&id=<? echo $result['id']?>" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;<button class="btn btn-info"><span class="glyphicon glyphicon-lock"></span></button></td>
                    <td><? echo $result['cas'].'<br>'.$result['adresa']?></td>
                    <td>
                        <span class="badge">PPV <? echo $result['PPV']?></span>
                        <span class="badge">PJ A <? echo $result['PJ_A']?></span>
                        <span class="badge">PJ B <? echo $result['PJ_A']?></span>
                    </td>
                    <td>
                        <a href="?menu=zkousky_seznam&id=<? echo $result['id']?>"><button class="btn btn-info"><span class="glyphicon glyphicon-list"></span></button></a>
                    </td>
                </tr>
            <? endwhile?>
        </table>
    </td>
</tr>