<h3>Application List</h3>

<ul>
<?foreach( MyFuses::getInstance()->getApplications() as $key => $application ){?>
    <?if( $key != Application::DEFAULT_APPLICATION_NAME ){?>    
    <li>
        <a href="<?=MyFuses::getMySelfXfa( "goToApplicationSummary", true, false ) ?>application=<?=$application->getName()?>">
            <?=$application->getName()?><?=$application->isDefault() ? "*" : ""?>
        </a>
    </li>
    <?}?>
<?}?>
</ul>
<i>* Default application</i>