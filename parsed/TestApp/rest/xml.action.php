<?php
MyFuses::getInstance()->setCurrentProperties( "preProcess", "TestApp.rest.xml" );

foreach( MyFuses::getInstance()->getApplication( "TestApp" )->getPlugins( "preProcess" ) as $plugin ) {
	$plugin->run();
}
foreach( MyFusesContext::getContext() as  $key => $value ) {global $$value;}

/* rest.prefuseaction: <myfuses:include file="dspHeader.php"> */
MyFusesContext::includeFile( MyFuses::getInstance()->getApplication( "TestApp" )->getCircuit( "rest" )->getCompletePath(). "/dspHeader.php" );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


MyFuses::getInstance()->setCurrentProperties( "preFuseaction", "TestApp.rest.xml" );

MyFuses::getInstance()->setCurrentProperties( "process", "TestApp.rest.xml" );

/* rest.xml: <myfuses:do action="rest.includeDependencies"> */
MyFuses::doAction( "TestApp.rest.includeDependencies" );foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


/* rest.xml: <myfuses:xfa name="getData" value="rest.toXml"> */
MyFuses::getInstance()->getRequest()->getAction()->addXFA( "getData", "rest.toXml" );
$XFA[ "getData" ] = "rest.toXml";

/* rest.xml: <myfuses:do action="rest.showData"> */
MyFusesContext::setParameter( "exampleName", "XML Example" );
MyFusesContext::setParameter( "verbName", "data:fromXml" );
MyFusesContext::setParameter( "dataType", "xml" );
MyFusesContext::setParameter( "actName", "rest.toXml" );
MyFuses::doAction( "TestApp.rest.showData" );foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}

MyFusesContext::restoreParameter( "exampleName" );
MyFusesContext::restoreParameter( "verbName" );
MyFusesContext::restoreParameter( "dataType" );
MyFusesContext::restoreParameter( "actName" );

MyFuses::getInstance()->setCurrentPhase( "postFuseaction" );

MyFuses::getInstance()->setCurrentAction( "TestApp.rest.xml" );

MyFuses::getInstance()->setCurrentProperties( "postProcess", "TestApp.rest.xml" );

/* rest.postfuseaction: <myfuses:xfa name="backtoMenu" value="main.menu"> */
MyFuses::getInstance()->getRequest()->getAction()->addXFA( "backtoMenu", "main.menu" );
$XFA[ "backtoMenu" ] = "main.menu";

/* rest.postfuseaction: <myfuses:include file="dspFooter.php"> */
MyFusesContext::includeFile( MyFuses::getInstance()->getApplication( "TestApp" )->getCircuit( "rest" )->getCompletePath(). "/dspFooter.php" );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


