<?php
abstract class MyFusesLifecycle {
    
    
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
        
        foreach( $controller->getApplications() as $application ) {
            self::storeApplication( $application );
        }
        
    }
    
    public static function storeApplication( Application $application ) {
        
    	/*$serializedApp = "<?php\nreturn unserialize( '" . serialize( $application ) . "' );\n\n";
        
        MyFusesFileHandler::createPath( $application->getParsedPath() );
        
        MyFusesFileHandler::writeFile( $application->getParsedApplicationFile(), $serializedApp );*/
        
    }
    
    public static function restoreApplication( $applicationName ) {
    	
    	$applicationFile = MyFusesFileHandler::sanitizePath( 
           MyFuses::getInstance()->getRootParsedPath() . 
           $applicationName ) . $applicationName . 
           MyFuses::getInstance()->getStoredApplicationExtension();
    	
    	if( file_exists( $applicationFile ) ) {
    		
    		return include $applicationFile;
    		
    	}
    	
    	return null;
    }
    
}