<?php
require_once "myfuses/engine/MyFusesLoader.class.php";

/**
 * Abstract MyFuses loader.<br>
 * 
 *
 */
abstract class AbstractMyFusesLoader implements MyFusesLoader {
    
    private $applicationData = array();
    
    private $applciationLoaderListeners = array();
    
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
            
            MyFuses::getInstance()->getDebugger()->registerEvent( 
                new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                    "Application " . 
                    $this->getApplication()->getName() . " Restored" ) );
                
            $this->getApplication()->setLastLoadTime( 
                $this->getLastLoadTime() );
                
            $this->getApplication()->setFile( 
                $this->applicationData[ 'application' ]['file'] );    
                
            $this->getApplication()->setMode( $this->getMode() );
            
            if( $this->getApplication()->getMode() == 'development' ) {
                $this->doLoadApplication();
            }
            
            if( $this->getApplication()->getMode() == 'production' ) {
                if( $this->applicationWasModified() ) {
                    $this->doLoadApplication();
                }
            }
        }
        else {
            $this->getApplication()->setMode( 'development' );
            $this->doLoadApplication();
        }
        
        foreach( $this->getApplicationLoadListeners() as $listener ) {
            $listener->applicationLoadPerformed( $this, 
                $this->applicationData );
        }
        
        foreach( $this->applicationData[ 'application' ][ 'children' ] 
            as $child ) {
            if( strtolower( $child[ 'name' ] ) == 'circuits' ) {
                foreach( $child[ 'children' ] as $circuitChild ) {
                    $this->loadCircuit( $circuitChild );
                }
            }
        }
        
        if( $this->getApplication()->isDefault() ) {
            if( $this->isToolsAllowed() ) {
                $appReference[ 'path' ] = MyFuses::MYFUSES_ROOT_PATH . 
                "myfuses_tools/";
            
                $this->getApplication()->getController()->
                    createApplication( "myfuses", $appReference );    
            }
        }
        
    }
    
    
    protected function doLoadApplication() {
        $data = $this->getApplicationData();
        
        $data[ 'lastloadtime' ] = time();
        
        $this->applicationData[ 'application' ] = $data;
        
        $this->getApplication()->setParse( true );
        
        MyFuses::getInstance()->getDebugger()->registerEvent( 
            new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                "Application " . 
                $this->getApplication()->getName() . " Loaded" ) );
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
        
        if( $this->getApplication()->getMode() == 'production' ) {
            if( $this->circuitWasModified( $name ) || 
                $this->applicationWasModified() ) {
                $this->doLoadCircuit( $name, $data, $circuitChild );    
            }
            else {
                $this->applicationData[ 'circuits' ][ $name ]
                    [ 'attributes' ][ 'modified' ] = false;
            }    
        }
    }
    
    protected function doLoadCircuit( $name, &$data, &$circuitChild ) {
        $data = $this->getCircuitData( $circuitChild );
        
        $data[ 'attributes' ][ 'lastloadtime' ] = time();

        $data[ 'attributes' ][ 'path' ] = 
            $circuitChild[ 'attributes' ][ 'path' ];
        
        $data[ 'attributes' ][ 'modified' ] = true;    
            
        
        $this->applicationData[ 'circuits' ][ $name ] = $data;
        
        MyFuses::getInstance()->getDebugger()->registerEvent( 
            new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                "Loading circuit \"" . $name . "\"" ) );
        
    }
    
    private function isToolsAllowed() {
        foreach( $this->applicationData[ 'application' ]['children'] 
            as $child ) {
            if( $child[ 'name' ] == 'parameters' ) {
                foreach( $child[ 'children' ] as $pchild ) {
                    if( $pchild[ 'attributes' ][ 'name' ] == 'tools' ) {
                        return ( $pchild[ 'attributes' ][ 'value' ] == 'true' ) 
                            ? true : false;        
                    }
                }
            }
        }
        return false;
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
    
    /**
     * Add one application load listener
     *
     * @param MyFusesApplicationLoaderListener $listener
     */
    public function addApplicationLoadListener( 
        MyFusesApplicationLoaderListener $listener ){
        $this->applciationLoaderListeners[] = $listener;
    }
    
    /**
     * Return all application load listerners
     *
     * @return array
     */
    private function getApplicationLoadListeners() {
        return $this->applciationLoaderListeners;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */