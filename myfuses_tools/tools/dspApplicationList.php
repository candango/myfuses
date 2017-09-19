<h3>Application List</h3>

<ul>
<?php foreach( MyFuses::getInstance()->getApplications() as $key => $application ){?>
    <?php if( $key != Application::DEFAULT_APPLICATION_NAME ){?>
    <li>
        <a href="<?=MyFuses::getMySelfXfa( "goToApplicationSummary", true, false ) ?>application=<?=$application->getName()?>">
            <?=$application->getName()?><?=$application->isDefault() ? "*" : ""?>
        </a>
    </li>
    <?php }?>
<?php }?>
</ul>
<i>* Default application</i>