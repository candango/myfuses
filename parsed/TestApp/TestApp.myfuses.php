<?php
$application = new BasicApplication( "TestApp" );
$application->setPath( "/var/www/myfuses/testapp/" );
$application->setRewrite( true );
$application->setParsedPath( "/home/fpiraz/phpwork/myfuses/parsed/TestApp/");
$application->setFile( "myfuses.xml" );
$application->setLastLoadTime( 1221653887 );
$application->setLoader( new XmlMyFusesLoader() );

$application->setFuseactionVariable( "fuseaction" );
$application->setDefaultFuseaction( "main.menu" );
$application->setPrecedenceFormOrUrl( "" );
$application->setMode( "development" );
$application->setPassword( "testApp" );
$application->setParsedWithComments( true );
$application->setConditionalParse( false );
$application->setLexiconAllowed( false );
$application->setBadGrammarIgnored( false );
$application->setAssertionsUsed( false );
$application->setScriptLanguage( "php5" );
$application->setScriptFileDelimiter( "php" );
$application->setDebug( true );
$application->setTools( false );
$application->setCharacterEncoding( "ISO-8859-1" );
MyFuses::getInstance()->addApplication( $application );
$circuit = new BasicCircuit();
$circuit->setName( "main" );
$circuit->setPath( "main/" );
$circuit->setFile( "" );
$application->addCircuit( $circuit );
$circuit->setVerbPaths( "a:0:{}" );
$circuit->setAccess( 1 );
$circuit->setLastLoadTime( 0 );
$circuit->setParentName( "" );

$circuit = new BasicCircuit();
$circuit->setName( "basic" );
$circuit->setPath( "basic/" );
$circuit->setFile( "" );
$application->addCircuit( $circuit );
$circuit->setVerbPaths( "a:0:{}" );
$circuit->setAccess( 1 );
$circuit->setLastLoadTime( 0 );
$circuit->setParentName( "main" );

$circuit = new BasicCircuit();
$circuit->setName( "rest" );
$circuit->setPath( "rest/" );
$circuit->setFile( "" );
$application->addCircuit( $circuit );
$circuit->setVerbPaths( "a:1:{s:4:\"data\";s:16:\"core/verbs/data/\";}" );
$circuit->setAccess( 1 );
$circuit->setLastLoadTime( 1221653887 );
$circuit->setParentName( "main" );

$action = new FuseAction( $circuit );
$action->setName( "prefuseaction" );

$data = array( 'name' => 'include', 'namespace' => 'myfuses', 'attributes' => array( 'file' => 'dspHeader.php' ) );
$verb = AbstractVerb::getInstance( $data, $action );

$action->addVerb( $verb );

$circuit->setPreFuseAction( $action );

$action = new FuseAction( $circuit );
$action->setName( "postfuseaction" );

$data = array( 'name' => 'xfa', 'namespace' => 'myfuses', 'attributes' => array( 'name' => 'backtoMenu', 'value' => 'main.menu' ) );
$verb = AbstractVerb::getInstance( $data, $action );

$action->addVerb( $verb );

$data = array( 'name' => 'include', 'namespace' => 'myfuses', 'attributes' => array( 'file' => 'dspFooter.php' ) );
$verb = AbstractVerb::getInstance( $data, $action );

$action->addVerb( $verb );

$circuit->setPostFuseAction( $action );

$circuit = new BasicCircuit();
$circuit->setName( "MYFUSES_GLOBAL_CIRCUIT" );
$circuit->setPath( "/var/www/myfuses/testapp/" );
$circuit->setFile( "" );
$application->addCircuit( $circuit );
$circuit->setVerbPaths( "a:0:{}" );
$circuit->setAccess( 2 );
$circuit->setLastLoadTime( 0 );
$circuit->setParentName( "" );

$class = new ClassDefinition();
$class->setName( "Account" );
$class->setPath( "classes/Account.class.php");
$application->addClass( $class );

$class = new ClassDefinition();
$class->setName( "Entity" );
$class->setPath( "classes/Entity.class.php");
$application->addClass( $class );

$plugin = AbstractPlugin::getInstance( $application, "preProcess", "MyFusesBasicSecurity", "/home/fpiraz/phpwork/myfuses/plugins/", "MyFusesBasicSecurityPlugin.class.php" );
$plugin->addParameter( "teste", "buuu" );

$plugin = AbstractPlugin::getInstance( $application, "preProcess", "BreadCrumb", "/var/www/myfuses/testapp/plugins/", "BreadCrumbPlugin.class.php" );

