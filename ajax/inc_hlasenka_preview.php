<?
	session_start();
	
	$sql='	SELECT
			* FROM registrace
			WHERE ApplicationStatus=3
			AND loc="'.$_SESSION['loc'].'"';
			
	$result=mysqli_query ($db_connect, $sql);
	
?>

<table class="table table-condensed table-bordered">
	<tr>
	    <th>Příjmení</th>
    	<th>Jméno</th>
        <th>Skupina</th>
        <th>Narozen</th>
        <th>Ulice</th>
        <th>č.p.</th>
        <th>Obec</th>
        <th>PSČ</th>
        <th><em>Číslo ŘP</em></th>
    </tr>
    <? while ($radek=mysqli_fetch_array($result)):?>
        <tr>
            <td><? echo $radek['prijmeni']?></td>
            <td><? echo $radek['jmeno']?></td>
            <td><? echo skupina_kod('registrace',$radek['GUID'])?></td>
            <td><? echo date('d.m.Y', strtotime($radek['narozen']))?></td>
            <td><? echo $radek['adresa1']?></td>
            <td><? echo $radek['cpopisne']?></td>
            <td><? echo $radek['adresa2']?></td>
            <td><? echo $radek['psc']?></td>
            <td><? echo $radek['rp']?></td>
        </tr>
	<? endwhile?>
</table>