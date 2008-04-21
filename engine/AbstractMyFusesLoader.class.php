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
    
    private function isCached() {
        if( $this->getApplication()->getController()->isMemcacheEnabled() ) {
            return !( $this->applicationData === false );
        }
        else {
            return is_file( $this->getApplication()->getCompleteCacheFile() );
        }
        
    }
    
    /**
     * Load the application
     *
     */
    public function loadApplication() {
        
        if( $this->getApplication()->getController()->isMemcacheEnabled() ) {
            $this->applicationData = unserialize( $this->getApplication()->
                getController()->getMemcache()->get( 
                $this->getApplication()->getTag() ) );        
        }
        
        if( $this->isCached() ) {
            
            if( !$this->getApplication()->getController()->
                isMemcacheEnabled() ) {
                include $this->getApplication()->getCompleteCacheFile();
                // correcting cached application reference
                $this->setApplication( 
                    $this->getApplication()->getController()->getApplication( 
                        $this->application->getName() ) );
                $this->getApplication()->setLoader( $this );                
                $this->applicationData = include( 
                    $this->getApplication()->getCompleteCacheFileData() );
            }
            
            if( $this->getApplication()->isDebugAllowed() ) {
                MyFuses::getInstance()->getDebugger()->registerEvent( 
                    new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                        "Application " . 
                        $this->getApplication()->getName() . " Restored" ) );    
            }
            
            if( $this->getApplication()->getMode() === 'development' ) {
                $this->doLoadApplication();
            }
            
            if( $this->getApplication()->getMode() === 'production' ) {
                if( $this->applicationWasModified() ) {
                    $this->doLoadApplication();
                }
            }
        }
        else {
            $this->doLoadApplication();
        }
        
        foreach( $this->getApplicationLoadListeners() as $listener ) {
            $listener->applicationLoadPerformed( $this, 
                $this->applicationData );
        }
        
        foreach( $this->applicationData[ 'application' ][ 'children' ] 
            as $child ) {
            if( strtolower( $child[ 'name' ] ) === 'circuits' ) {
                foreach( $child[ 'children' ] as $circuitChild ) {
                    $this->loadCircuit( $circuitChild );
                }
            }
        }
        
        if( $this->getApplication()->isDefault() ) {
            if( $this->getApplication()->isToolsAllowed() ) {
                $appReference[ 'path' ] = MyFuses::MYFUSES_ROOT_PATH . 
                "myfuses_tools/";
            
                $this->getApplication()->getController()->
                    createApplication( "myfuses", $appReference );    
            }
        }
        
    }
    
    
    protected function doLoadApplication() {
        $data = $this->getApplicationData();
        
        $this->getApplication()->setLastLoadTime( time() );
        
        $this->applicationData[ 'application' ] = $data;
        
        $this->getApplication()->setParse( true );
        $this->getApplication()->setStore( true );
        
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
        
        try {
            $circuit = $this->getApplication()->getCircuit( $name );
            if( $circuit->getApplication()->getMode() === 'development' ) {
                $this->doLoadCircuit( $name, $data, $circuitChild );
            }
            
            if( $circuit->getApplication()->getMode() === 'production' ) {
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
        catch( MyFusesCircuitException $mfce ) {
            $this->doLoadCircuit( $name, $data, $circuitChild );
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