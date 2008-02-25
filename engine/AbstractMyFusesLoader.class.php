<?php
require_once "myfuses/engine/MyFusesLoader.class.php";

/**
 * Abstract MyFuses loader.<br>
 * 
 *
 */
abstract class AbstractMyFusesLoader implements MyFusesLoader {
    
    private $applicationData = array();
    
    /**
     * Loader application
     * 
     * @var Application
     */
    private $application;
    
    /**
     * Return the application
     *
     * @return Application
     */
    public function getApplication(){
        return $this->application;
    }
    
    /**
     * Set the loader Application
     *
     * @param Application $application
     */
    public function setApplication( Application $application ) {
        $this->application = $application;
    }
    
    public function &getCachedApplicationData() {
        return $this->applicationData;
    }
    
    private function setCachedApplicationData( $applicationData ) {
        $this->applicationData = $applicationData;
    }
    
    public function destroyCachedApplicationData() {
        $this->applicationData = array();
    }
    
    /**
     * Load the application
     *
     */
    public function loadApplication() {
        if( is_file( $this->getApplication()->getCompleteCacheFile() ) ) {
            $this->applicationData = include( 
                $this->getApplication()->getCompleteCacheFile() );
                
            $this->getApplication()->setLastLoadTime( 
                $this->getLastLoadTime() );
                
            $this->getApplication()->setFile( 
                $this->applicationData[ 'application' ]['file'] );    
                
            $this->getApplication()->setMode( $this->getMode() );
            
            if( $this->getApplication()->getMode() == 'development' ) {
                $this->doLoadApplication();
                $this->getApplication()->setParse( true );
            }
            
            if( $this->getApplication()->getMode() == 'production' ) {
                if( $this->applicationWasModified() ) {
                    $this->doLoadApplication();
                    $this->getApplication()->setParse( true );
                }
            }

            MyFuses::getInstance()->getDebugger()->registerEvent( 
                new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                    "Application " . 
                    $this->getApplication()->getName() . " Restored" ) );
        }
        else {
            $this->doLoadApplication();
        }
        
        foreach( $this->applicationData[ 'application' ][ 'children' ] 
            as $child ) {
            if( strtolower( $child[ 'name' ] ) == 'circuits' ) {
                $child[ 'teste' ] = 'buga';
                foreach( $child[ 'children' ] as $circuitChild ) {
                    $this->loadCircuit( $circuitChild );
                }
            }
        }
    }
    
    
    protected function doLoadApplication() {
        $data = $this->getApplicationData();
        
        $data[ 'lastloadtime' ] = time();
        
        $this->applicationData[ 'application' ] = $data;
    }
    
    
    protected function loadCircuit( &$circuitChild ) {
        
        $name = "";
        
        if( isset( $circuitChild[ 'attributes' ][ 'name' ] ) ) {
            $name = $circuitChild[ 'attributes' ][ 'name' ];
        }
        
        if( isset( $circuitChild[ 'attributes' ][ 'alias' ] ) ) {
            $name = $circuitChild[ 'attributes' ][ 'alias' ];
        }
        
        if( $this->getApplication()->getMode() == 'development' ) {
            $this->doLoadCircuit( $name, $data, $circuitChild );
        }
        
        
        if( $this->circuitWasModified( $name ) || 
            $this->applicationWasModified() ) {
            $this->doLoadCircuit( $name, $data, $circuitChild );    
        }
        else {
            $data[ 'attributes' ][ 'parse' ] = false;
        }
        
    }
    
    protected function doLoadCircuit( $name, &$data, &$circuitChild ) {
        $data = $this->getCircuitData( $circuitChild );
        
        $data[ 'attributes' ][ 'lastloadtime' ] = time();

        $data[ 'attributes' ][ 'path' ] = 
            $circuitChild[ 'attributes' ][ 'path' ];
        
        $data[ 'attributes' ][ 'parse' ] = true;    
            
        $this->applicationData[ 'circuits' ][ $name ] = $data;
        
        MyFuses::getInstance()->getDebugger()->registerEvent( 
            new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                "Loading circuit \"" . $name . "\"" ) );
        
    }
    
    private function getLastLoadTime() {
        return $this->applicationData[ 'application' ][ 'lastloadtime' ];
    }
    
    private function getMode() {
        foreach( $this->applicationData[ 'application' ]['children'] 
            as $child ) {
            if( $child[ 'name' ] == 'parameters' ) {
                foreach( $child[ 'children' ] as $pchild ) {
                    if( $pchild[ 'attributes' ][ 'name' ] == 'mode' ) {
                        return $pchild[ 'attributes' ][ 'value' ];        
                    }
                }
            }
        }
        
        return 'development';
    }
    
    private function getCircuitPath( $name ) {
        foreach( $this->applicationData[ 'application' ]['children'] 
            as $child ) {
            if( $child[ 'name' ] == 'circuits' ) {
                foreach( $child[ 'children' ] as $pchild ) {
                    if( isset( $pchild[ 'attributes' ][ 'name' ] ) ) {
                        if( $pchild[ 'attributes' ][ 'name' ] == $name ) {
                            return $pchild[ 'attributes' ][ 'path' ];        
                        }    
                    }
                    if( isset( $pchild[ 'attributes' ][ 'alias' ] ) ) {
                        if( $pchild[ 'attributes' ][ 'alias' ] == $name ) {
                            return $pchild[ 'attributes' ][ 'path' ];        
                        }    
                    }
                    
                }
            }
        }
    }
    
    /**
     * Enter description here...
     *
     * @param int $whichLoader
     * @return AbstractMyFusesLoader
     */
    public static function getLoader( $whichLoader ) {
        
        $loaderArray = array(
            MyFusesLoader::XML_LOADER => "XmlMyFusesLoader"
        );
        
        return new $loaderArray[ $whichLoader ]();
    }
    
    /**
     * Clean all hashed strings ex:#<string>#
     *
     * @param string $hstring
     * @return string
     */
    public static function sanitizeHashedString( $hstring ) {
        // resolving #valriable#'s 
        return  preg_replace( 
            "@([#])([\$|\d|\w|\-\>|\:|\(|\)|\'|\\\"|\[|\]|\s]*)([#])@", 
            "\" . $2 . \"" , $hstring );
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */