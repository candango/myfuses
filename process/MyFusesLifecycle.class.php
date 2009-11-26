<?php
abstract class MyFusesLifecycle {
    
    public static function loadApplications( MyFuses $controller ) {
        foreach( $controller->getApplications() as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                self::loadApplication( $application );	
            }
        }
    }
    
    public static function loadApplication( Application $application ) {
    	$loader = new MyFusesXmlLoader();
    	
    	$loader->loadApplication( $application );
    }
    
	public static function createRequest( MyFuses $controller ) {
		$request = new MyFusesRequest();
		
	    $request->setApplication( $controller->getApplication() );
	    
	    $request->setDefaultFuseaction( $controller->getApplication()->getDefaultFuseaction() );
	    
	    $request->setFuseactionVariable( $controller->getApplication()->getFuseactionVariable() );
	    
		$router = new MyFusesBasicRequestRouter();

		$router->grab( $request );
		
		$router->resolve( $request );
		
		$router->release( $request );
		
		$controller->setRequest( $request );
	}
	
	public static function executeProcess( MyFuses $controller ) {
		$application = $controller->getApplication();
		
		if( !$application->isStarted() ) {
			$application->fireApplicationStart();
			$application->setStarted( true );
		}
		
		$application->firePreProcess();
		
		$application->firePostProcess();
	}
	
    public static function storeApplications( MyFuses $controller ) {
        foreach( $controller->getApplications() as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                self::storeApplication( $application );
            }
        }
    }
    
    public static function storeApplication( Application $application ) {
    	$serializedApp = "<?php\nreturn unserialize( '" . 
    	   serialize( $application ) . "' );\n\n";
        
        MyFusesFileHandler::createPath( $application->getParsedPath() );
        
        MyFusesFileHandler::writeFile( 
            $application->getParsedApplicationFile(), $serializedApp );
    }
    
    /**
     * Restores application from stored file if exists
     * 
     * @param $applicationName
     * @return Application The stored application
     */
    public static function restoreApplication( $applicationName ) {
    	$applicationFile = MyFusesFileHandler::sanitizePath( 
           MyFuses::getInstance()->getParsedRootPath() . 
           $applicationName ) . $applicationName . 
           MyFuses::getInstance()->getStoredApplicationFileExtension();
    	
    	if( file_exists( $applicationFile ) ) {
    		
    		return include $applicationFile;
    		
    	}
    	
    	return null;
    }
    
}