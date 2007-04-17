<?php
require_once "myfuses/engine/MyFusesLoader.class.php";

/**
 * Abstract MyFuses loader.<br>
 * 
 *
 */
abstract class AbstractMyFusesLoader implements MyFusesLoader {
    
    /**
     * Enter description here...
     *
     * @param Application $application
     */
    public function loadApplication( Application $application ) {
        
        // setting parsed path
        if ( is_null( $application->getParsedPath() ) ) {
            $application->setParsedPath( MyFuses::ROOT_PATH . "store" . DIRECTORY_SEPARATOR . 
                MyFuses::getInstance()->getApplication()->getName() . DIRECTORY_SEPARATOR ) ;
        }
        
        // getting cache file
	    // TODO application load must be like fusebox official
        if( is_file( $application->getCompleteCacheFile() ) ) {
            require_once( $application->getCompleteCacheFile() );
            if( $this->applicationWasModified( $application ) ) {
                $this->doLoad( $application );
                $application->setLastLoadTime( time() );
            }
            else{
                if( $application->getMode() == "development" ) {
	                $this->doLoad( $application );
	                $application->setLastLoadTime( time() );
	            }
            }
        }
        else {
            $this->doLoad( $application );
            $application->setLastLoadTime( time() );
        }
        
        $application->setLoaded( true );
        
    }
    
    /**
     * Enter description here...
     *
     * @param int $whichLoader
     * @return AbstractMyFusesLoader
     */
    public static function getLoader( $whichLoader ) {
        
        $loaderArray = array(
            MyFusesLoader::XML_LOADER => "XMLMyFusesLoader"
        );
        
        return new $loaderArray[ $whichLoader ]();
    }
    
    
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */