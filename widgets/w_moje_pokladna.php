<div class="widget widget-blue">
		<?
            $sql='	SELECT SUM(pokladna.prijmy) AS prijmy, SUM(pokladna.vydaje) AS vydaje
                    FROM 
                    (
                    SELECT
                    IFNULL(SUM(platba),0) AS prijmy,
                    0 AS vydaje
                    FROM pokladna_zalohy 
                    WHERE pokladna_zalohy.autor="'.$_COOKIE['userID'].'"
        
                    
                    UNION
                    
                    SELECT
                    0 AS prijmy,
                    IFNULL (SUM(castka),0) AS vydaje
                    FROM pokladna_vydaje
                    WHERE pokladna_vydaje.uzivatel_ucet="'.$_COOKIE['userID'].'"
                    ) AS pokladna
                ';
        	$result=mysqli_query($db_connect,$sql);
        	$sum=mysqli_fetch_array($result);
        ?>
        <table width="100%">
        	<tr><td rowspan="2"><h1><span class="glyphicon glyphicon-piggy-bank"></span></h1></td><td><h2 class="text-right">Moje pokladna</h2></td></tr>
            <tr><td><h3 class="text-right"><?=$sum['prijmy']+$sum['vydaje']?></h3></td></tr>
        </table>
</div>