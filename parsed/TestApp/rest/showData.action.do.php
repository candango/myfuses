<?php
MyFuses::getInstance()->setCurrentProperties( "preFuseaction", "TestApp.rest.showData" );

/* rest.showData: <myfuses:switch condition="$dataType"> */
switch( $dataType ) {
	case( "json" ) :
		/* rest.showData: <data:fromJson> */
		MyFusesContext::setVariable( "data",  MyFusesJsonUtil::fromJsonUrl( MyFuses::getMySelfXfa( "getData" ) ) );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}

		break;
	case( "xml" ) :
		/* rest.showData: <data:fromXml> */
		MyFusesContext::setVariable( "data",  MyFusesXmlUtil::fromXmlUrl( MyFuses::getMySelfXfa( "getData" ) ) );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}

		break;
}
/* rest.showData: <myfuses:include file="dspData.php"> */
MyFusesContext::includeFile( MyFuses::getInstance()->getApplication( "TestApp" )->getCircuit( "rest" )->getCompletePath(). "/dspData.php" );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}


MyFuses::getInstance()->setCurrentPhase( "postFuseaction" );

MyFuses::getInstance()->setCurrentAction( "TestApp.rest.showData" );

