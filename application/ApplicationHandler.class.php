<?php
/**
 * ApplicationHandler - ApplicationHandler.class.php
 * 
 * @author Flávio Gonçalves Garcia <fpiraz@gmail.com>
 */
class ApplicationHandler {
    
    
    const MYFUSES_APP_FILE = "myfuses.xml";
    
    const MYFUSES_PHP_APP_FILE = "myfuses.xml.php";
    
    
    public function getApplicationInstance( $appName ) {
        return new Application( $appName );
    }
    
    public function loadApplication( Application $application ) {
        
        $this->chooseApplicationFile( $application );
        
        var_dump( $application );
        
    }
    
    private function chooseApplicationFile( Application $application ) {
        if ( is_file( $application->getPath() . self::MYFUSES_APP_FILE ) ) {
            $application->setFile( self::MYFUSES_APP_FILE );
            return true;
        }
        
        if ( is_file( $application->getPath() . self::MYFUSES_PHP_APP_FILE ) ) {
            $application->setFile( self::MYFUSES_PHP_APP_FILE );
            return true;
        }
        
        return false;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */