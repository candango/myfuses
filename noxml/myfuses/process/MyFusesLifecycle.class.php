<?php
abstract class MyFusesLifecycle {
    
    
    public static function storeApplications( MyFuses $controller ) {
        
        foreach( $controller->getApplications() as $application ) {
            self::storeApplication( $application );
        }
        
    }
    
    
    public static function storeApplication( $application ) {
        
        $parsedDir = MyFusesFileHandler::sanitizePath( DIRECTORY_SEPARATOR . "tpm" );
        
    }
    
}