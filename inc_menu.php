
<nav class="navbar navbar-inverse navbar-fixed-top">
<div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
	  <a href="http://autoskolaeasy.cz" class="navbar-brand">autoškolaeasy.cz</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
	      <li><a href="/?menu=dashboard"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;Info</a></li>
         
          <!--MENU EVIDENCE ZAKU-->
          <? if (($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-pokladna']==1)):?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>&nbsp;Evidence žáků&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                	<? if (($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1)):?>
	                    <li><a href="?menu=matrika">Matriční kniha</a></li>
	                    <li><a href="?menu=praxe">Třídní kniha - praxe</a></li>
                    <? endif?>
                    <? if ($arrayUser[$_COOKIE['userID']]['pravo-pokladna']==1):?>
	                    <li><a href="?menu=platby">Pokladna</a></li>
					<? endif?>
                    <li class="divider visible-xs"></li>
                </ul>
              </li>
          <? endif?>

        <!--MENU ZKOUSKY-->
        <? if (($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1)):?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-road"></span>&nbsp;Zkoušky&nbsp;<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="?menu=zkousky">Kalendář zkoušek</a></li>
            <li><a href="?menu=zkousky_zaci">Přihláška ke zkoušce</a></li>
            <li><a href="/?menu=zkousky_vysledky">Výsledky zkoušek</a></li>
            <li class="divider visible-xs"></li>
          </ul>
        </li>
		<? endif?>
        
        <!--MENU UCITEL-->
        <? if (($arrayUser[$_COOKIE['userID']]['pravo-instr_A']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-instr_B']==1)):?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-briefcase"></span>&nbsp;Nástroje&nbsp;<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="/?menu=ucitel_pokladna">Moje pokladna</a></li>
                <li><a href="/?menu=vykaz">Měsíční výkaz práce</a></li>
                <!--<li><a href="">Změna údajů</a></li>-->
                <li class="divider visible-xs"></li>
                <? if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1):?>
                <li><a href="/?menu=vozova_kniha">Vozová kniha</a></li>
                <? endif?>
              </ul>
            </li>
        <? endif?>

        <!--MENU ADMIN-->
        <? if (($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1) OR ($arrayUser[$_COOKIE['userID']]['pravo-admin']==1)):?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;Admin&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="?menu=event">Management kurzů</a></li>
                	<li><a href="?menu=registrace">Registrace žáků</a></li>
                    <li><a href="?menu=hlasenky">Hlášenky výciku</a></li>
	            	<!--<li><a href="">Tiskové sestavy</a></li>-->
                    <li class="divider visible-xs"></li>                     
                  </ul>
              </li>
          <? endif?>
        
        
        <!--MENU SUP-->
        <? if ($arrayUser[$_COOKIE['userID']]['pravo-supervisor']==1):?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-wrench"></span>&nbsp;Supervisor&nbsp;<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="?menu=reporting">Reporting</a></li>
                <li><a href="">Pokladní deník pro FÚ</a></li>
                <li><a href="/?menu=registr_user">Registr uživatelů</a></li>
                 <li><a href="/?menu=registr_vehicle">Registr vozidel</a></li>
                <li><a href="/?menu=journal_login">Login journal</a></li>
                <li class="divider visible-xs"></li>
              </ul>
            </li>  
		<? endif?>
      </ul>
      
      <!--MENU BRANCH-->
      <ul class="nav navbar-nav navbar-right">
      	<li class="dropdown">
        	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-tasks"></span>&nbsp;<? echo $_SESSION['loc']?>&nbsp;<b class="caret"></b></a>
          	<ul class="dropdown-menu">
              	<? if ($arrayUser[$_COOKIE['userID']]['pravo-liberec']==1):?><li><a class="set_branch" href="?branch=Liberec">Liberec</a></li><? endif?>
            	<? if ($arrayUser[$_COOKIE['userID']]['pravo-praha']==1):?><li><a class="set_branch" href="?branch=Praha">Praha</a></li><? endif?>
            	<? if ($arrayUser[$_COOKIE['userID']]['pravo-jablonec']==1):?><li><a class="set_branch" href="?branch=Jablonec">Jablonec nad Nisou</a></li><? endif?>
          	</ul>
            <li class="divider visible-xs"></li>
		</li>
        <li><a id="button_logout" href="#" data-toggle="tooltip" title="Odhlásit"><span class="glyphicon glyphicon-log-out"></span>&nbsp;<? echo $arrayUser[$_COOKIE['userID']]['jmeno']?></a></li>
      </ul>
    </div>
</div>
</nav>
<br /><br />