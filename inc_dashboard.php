<div class="jumbotron">
    <h1>Bridge | autoškolaeasy.cz</h1>
    <p>
        <? if ($_SERVER['HTTP_HOST']=='dev.autoskolaeasy.cz'):?>development<? endif?>
        <? if ($_SERVER['HTTP_HOST']=='admin.autoskolaeasy.cz'):?>verze<? endif?>
        2.31
    </p>
</div>
<div class="container-fluid">
	 <div class="row visible-xs visible-sm">
	    <div class="col-md-12">
            <div class="btn-group-vertical btn-block">
	            <? if ($arrayUser[$_COOKIE['userID']]['pravo-pokladna']==1):?>
                    <a href="?menu=platby" class="btn btn-lg btn-default">
                        <div class="text-left">
                            <span class="glyphicon glyphicon-piggy-bank"></span>&nbsp;Pokladna
                        </div>
                    </a>
                <? endif?>
                <a href="?menu=praxe" class="btn btn-lg btn-default">
                	<div class="text-left">
	                	<span class="glyphicon glyphicon-th-list"></span>&nbsp;Praktický výcvik
					</div>                       
                </a>
               	<a href="?menu=zkousky_zaci" class="btn btn-lg btn-default">
                	<div class="text-left">
                    	<span class="glyphicon glyphicon-road"></span>&nbsp;Přihláška ke zkoušce
                    </div>
                </a>
               
            </div>       
		</div>
    </div>
	<br />
    <div class=row>
        <div class="col-md-8">
            <div class="panel-group">
            	<? 
					include 'widgets/w_new_version.php';
					include 'widgets/w_novi_zaci.php';
					include 'widgets/w_moje_zkousky.php'
				?>
            </div>
        </div>
        <div class="col-md-4">
            <? 
                include 'widgets/w_moje_pokladna.php';
                include 'widgets/w_vykaz_prace.php';
            ?>
        </div>        
    </div>


	


    
	<? 
		include 'widgets/w_dokumentace.php';
	?>


</div>
