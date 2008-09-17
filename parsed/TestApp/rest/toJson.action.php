<?php
MyFuses::getInstance()->setCurrentProperties( "preProcess", "TestApp.rest.toJson" );

foreach( MyFuses::getInstance()->getApplication( "TestApp" )->getPlugins( "preProcess" ) as $plugin ) {
	$plugin->run();
}
foreach( MyFusesContext::getContext() as  $key => $value ) {global $$value;}

/* rest.prefuseaction: <myfuses:include file="dspHeader.php"> */
MyFusesContext::includeFile( MyFuses::getInstance()->getApplication( "TestApp" )->getCircuit( "rest" )->getCompletePath(). "/dspHeader.php" );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


MyFuses::getInstance()->setCurrentProperties( "preFuseaction", "TestApp.rest.toJson" );

MyFuses::getInstance()->setCurrentProperties( "process", "TestApp.rest.toJson" );

/* rest.toJson: <myfuses:do action="rest.includeDependencies"> */
MyFuses::doAction( "TestApp.rest.includeDependencies" );foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


/* rest.toJson: <myfuses:do action="rest.createData"> */
MyFuses::doAction( "TestApp.rest.createData" );foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


/* rest.toJson: <data:toJson> */
ob_clean();
print( MyFusesJsonUtil::toJson(  $data ) );
die();
MyFuses::getInstance()->setCurrentPhase( "postFuseaction" );

MyFuses::getInstance()->setCurrentAction( "TestApp.rest.toJson" );

MyFuses::getInstance()->setCurrentProperties( "postProcess", "TestApp.rest.toJson" );

/* rest.postfuseaction: <myfuses:xfa name="backtoMenu" value="main.menu"> */
MyFuses::getInstance()->getRequest()->getAction()->addXFA( "backtoMenu", "main.menu" );
$XFA[ "backtoMenu" ] = "main.menu";

/* rest.postfuseaction: <myfuses:include file="dspFooter.php"> */
MyFusesContext::includeFile( MyFuses::getInstance()->getApplication( "TestApp" )->getCircuit( "rest" )->getCompletePath(). "/dspFooter.php" );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


