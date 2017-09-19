<h1>MyFuses Tools</h1>
<div style="color:red;"><?=MyFusesApplicationSecurityPlugin::getMessage()?></div>
<?php if( MyFusesApplicationSecurityPlugin::isLogged() ){?>
Main: <a href="<?=MyFuses::getMySelfXfa( "goToStart" )?>">Index</a>
<a href="<?=MyFuses::getMySelfXfa( "goToLogout" )?>">Logout</a><br>
<?php }?>