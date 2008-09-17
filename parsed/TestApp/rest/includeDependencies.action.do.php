<?php
MyFuses::getInstance()->setCurrentProperties( "preFuseaction", "TestApp.rest.includeDependencies" );

/* rest.includeDependencies: <myfuses:include file="actIncludeDependencies.php"> */
MyFusesContext::includeFile( MyFuses::getInstance()->getApplication( "TestApp" )->getCircuit( "rest" )->getCompletePath(). "/actIncludeDependencies.php" );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


MyFuses::getInstance()->setCurrentPhase( "postFuseaction" );

MyFuses::getInstance()->setCurrentAction( "TestApp.rest.includeDependencies" );

