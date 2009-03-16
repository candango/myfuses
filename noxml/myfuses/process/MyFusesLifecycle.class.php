<?php
abstract class MyFusesLifecycle {
    
    
    public static function storeApplications( MyFuses $controller ) {
        
        foreach( $controller->getApplications() as $application ) {
            self::storeApplication( $application );
        }
        
    }
    
    public static function storeApplication( Application $application ) {
        
    	//TODO use real parse dir
        $storeDir = MyFusesFileHandler::sanitizePath( DIRECTORY_SEPARATOR . "tmp" );
        
        $storeDir = MyFusesFileHandler::sanitizePath( $storeDir . $application->getName() );
        
        $storeFile = $storeDir . $application->getName() . ".myfsues.php";
        
        $serializedApp = "<?php\nreturn '" . serialize( $application ) . "';\n\n";
        
        MyFusesFileHandler::createPath( $storeDir );
        
        MyFusesFileHandler::writeFile( $storeFile, $serializedApp );
        
    }
    
}