<h3>Application List</h3>

<ul>
<?foreach( MyFuses::getInstance()->getApplications() as $key => $application ){?>
    <?if( $key != Application::DEFAULT_APPLICATION_NAME ){?>    
    <li><?=$application->getName()?><?=$application->isDefault() ? "*" : ""?></li>
    <?}?>
<?}?>
</ul>
<i>* Default application</i>