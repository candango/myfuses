<?php
MyFuses::getInstance()->setCurrentProperties( "preFuseaction", "TestApp.rest.createData" );

/* rest.createData: <myfuses:instantiate class="Entity" object="data"> */
if ( file_exists( MyFuses::getInstance()->getApplication( "TestApp" )->getClass( "Entity" )->getCompletePath() ) ) {
	require_once( MyFuses::getInstance()->getApplication( "TestApp" )->getClass( "Entity" )->getCompletePath() );
}
MyFusesContext::setVariable( "data", new Entity(  ) );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}

/* rest.createData: <myfuses:invoke object="data" method="setName"> */
MyFusesContext::getVariable( "data" )->setName( "Entity 1" );

/* rest.createData: <myfuses:invoke object="data" method="setValue"> */
MyFusesContext::getVariable( "data" )->setValue( "Value 1" );

/* rest.createData: <myfuses:set name="items" value= array()> */
MyFusesContext::setVariable( "items",  array() );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}

/* rest.createData: <myfuses:loop from="0" to="10" index="$i"> */
for( $i = 0; $i <= 10; $i = $i + 1 ) {
	/* rest.createData: <myfuses:instantiate class="Entity" object="item"> */
	if ( file_exists( MyFuses::getInstance()->getApplication( "TestApp" )->getClass( "Entity" )->getCompletePath() ) ) {
		require_once( MyFuses::getInstance()->getApplication( "TestApp" )->getClass( "Entity" )->getCompletePath() );
	}
	MyFusesContext::setVariable( "item", new Entity(  ) );

foreach( MyFusesContext::getContext() as $key => $value ) {global $$value;}

	/* rest.createData: <myfuses:invoke object="item" method="setName"> */
	MyFusesContext::getVariable( "item" )->setName( "Entity 1 - " .$i );

	/* rest.createData: <myfuses:invoke object="item" method="setValue"> */
	MyFusesContext::getVariable( "item" )->setValue( "Value 1 - " .$i );

	/* rest.createData: <myfuses:set value=array_push( $items, $item )> */
	array_push( $items, $item );
}
/* rest.createData: <myfuses:invoke object="data" method="setItems"> */
MyFusesContext::getVariable( "data" )->setItems(  $items );

MyFuses::getInstance()->setCurrentPhase( "postFuseaction" );

MyFuses::getInstance()->setCurrentAction( "TestApp.rest.createData" );

