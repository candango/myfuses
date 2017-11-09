<?php
use Candango\MyFuses\Controller;
?>

You're not logged in MyFuses Tools application. Please inform your
<?=Controller::getApplication()->getName()?> application password:<br>
<form name="frmMyfusesLogin" method="post" 
    action="<?=xfa("goToLogin")?>" >
    <input type="password" name="myfusesLogin">
    <input type="submit">
</form>
