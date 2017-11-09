<h1>MyFuses Tools</h1>
<div style="color:red;"><?=MyFusesApplicationSecurityPlugin::getMessage()?></div>
<?php if (MyFusesApplicationSecurityPlugin::isLogged()) {?>
Main: <a href="<?=xfa("goToStart")?>">Index</a>
<a href="<?=xfa("goToLogout")?>">Logout</a><br>
<?php }?>
