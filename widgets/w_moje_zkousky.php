<? 
	$sql='	SELECT
			DISTINCT (zt.id),zt.datum, zt.cas, zt.loc
			FROM zkousky_terminy zt
			JOIN
	
			(SELECT
			zz.ZKid
			FROM zkousky_zaci zz
			JOIN
			(SELECT GUID FROM matrika WHERE (ucitel_A="'.$_COOKIE['userID'].'" OR ucitel_B="'.$_COOKIE['userID'].'") AND (status="4" OR status="5")) AS m
			ON m.GUID=zz.GUID) AS zza
			
			ON zt.id=zza.ZKid
			WHERE datum>=NOW() 
			ORDER BY zt.datum ASC
	';
	$result = mysqli_query ($db_connect, $sql);
	$sum=0;
?>
    

<div class="panel panel-primary">
    <div class="panel-heading">
        <h1 class="panel-title">
            <a data-toggle="collapse" href="#collapse-zkousky">
				<div class="row">
	            	<div class="col-xs-9">
    	                <span class="glyphicon glyphicon-road"></span>&nbsp;Moje termíny zkoušek
        	            <span id="badge_zkousky" class="badge statusGreen"></span>
					</div>
                    <div class="col-xs-3 text-right"><span class="panel-arrow glyphicon glyphicon-menu-down"></span></div>
                </div>		            
            </a>
        </h1>
    </div>
    <div id="collapse-zkousky" class="panel-collapse collapse">
          <div class="panel-body">
                <table class="table table-striped table-hover">
                    <? while ($radek=mysqli_fetch_array($result)):?>
                        <? $sum++;?>
                        <tr>
                            <td width="5px" class="statusLBlue"></td>
                            <td width="99%"><h5><?=$radek['loc']?></h5><strong><?=date('d.m.Y', strtotime($radek['datum']))?></strong>&nbsp;<?=$radek['cas']?></td>
                            <td><a href="/?menu=zkousky_seznam&id=<?=$radek['id']?>" class="btn btn-info"><h3><span class="glyphicon glyphicon-expand"></span></h3></a></td>
                        </tr>
                    <? endwhile?>
                </table>          
          </div>
    </div>
</div>


<script>
	$('#badge_zkousky').html(<?=$sum?>);
</script>