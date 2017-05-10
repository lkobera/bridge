<?
	session_start();
	include '../inc_db_connect.php';
	
	$sql='	  	SELECT';
	$sql=$sql.' * FROM zkousky_terminy';
	$sql=$sql.' WHERE loc="'.$_SESSION['loc'].'"';
	$sql=$sql.' AND datum="'.$_GET['datum'].'"';
	$result=mysqli_query ($db_connect, $sql);
	

	$CZdate=date ('d.m.Y', strtotime($_GET['datum']));
	
?>


<td><h3><? echo $CZdate?></h3></td>
<td><button value="<? echo $_GET['datum'] ?>" class="zkousky_insert btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>
<td>
    <table class="table table-bordered">
        <? while ($radek=mysqli_fetch_array($result)):?>
            <tr>
                <td><a href="?menu=zkousky_edit&id=<? echo $radek['id']?>" class="btn btn-info"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;<button class="zkousky-lock btn btn-info"><span class="glyphicon glyphicon-lock"></span></button></td>
                <td><? echo $radek['cas'].'<br>'.$radek['adresa']?></td>
                <td>
                    <span class="badge">PPV <? echo $radek['PPV']?></span>
                    <span class="badge">PJ A <? echo $radek['PJ_A']?></span>
                    <span class="badge">PJ B <? echo $radek['PJ_A']?></span>
                </td>
                <td>
                    <a href="?menu=zkousky_seznam&id=<? echo $radek['id']?>"><button class="btn btn-info"><span class="glyphicon glyphicon-list"></span></button></a>
                </td>
            </tr>
        <? endwhile?>
    </table>
</td>
<script src="../js/myjquery.js"></script>
