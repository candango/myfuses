<?php
define( "MYFUSES_ROOT_PATH", dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

require_once "myfuses/core/Application.class.php";
require_once "myfuses/process/MyFusesLifecycle.class.php";

require_once "myfuses/util/Common.classes.php";

class MyFuses {
    
    /**
     * Array of registered applications
     * 
     * @var array
     */
    protected $applications = array();
    
    protected $storedApplicationExtension = ".application.myfuses.php";
    
    /**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
    private static $instance;
    
    public function createApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) {

        $application = MyFusesLifecycle::restoreApplication( $name );
        
        if( $application === null ) {
            $application = new BasicApplication();
	        
	        $application->setName( $name );
	        
	        $application->setPath( 
	           dirname( str_replace( "/", DIRECTORY_SEPARATOR, 
	           $_SERVER[ 'SCRIPT_FILENAME' ] ) ) );
	        
        }
        
        $this->addApplication( $application );
        
        return $application;
    }

    /**
     * Add one application to controller
     *
     * @param Application $application
     */
    public function addApplication( Application $application ) {
        if( count( $this->applications ) == 0 ) {
            $application->setDefault( true );
        }
        
        $this->applications[ $application->getName() ] = $application;
        
        if( Application::DEFAULT_APPLICATION_NAME != $application->getName() ) {
            if( $application->isDefault() ) {
                if( isset( $this->applications[ 
                    Application::DEFAULT_APPLICATION_NAME ] ) ) {
                    $this->applications[ 
                    Application::DEFAULT_APPLICATION_NAME ]->setDefault( 
                        false );
                }
                $this->applications[ Application::DEFAULT_APPLICATION_NAME ] =
                    &$this->applications[ $application->getName() ];
            }
                
        }
    }
    
    /**
     * Returns an existing application
     *
     * @param string $name
     * @return Application
     */
    public function getApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) { 
        	
        if( isset( $this->applications[ $name ] ) ) {
            return $this->applications[ $name ];
        }
    }
    
    /**
     * Returns an array of registered applications
     *
     * @return array
     */
    public function &getApplications() { 
        return $this->applications;
    }
    
    public function doProcess() {
        
    	MyFusesLifecycle::executeProcess( $this );
    	
        MyFusesLifecycle::storeApplications( $this );
        
        
        
        /*$path = $this->getApplication( "TestApp" )->getPath();
        
        $file = $path . "../myfuses.xml";
        
        if( file_exists( $file ) ) {
        	
        	if (! ($xmlparser = xml_parser_create()) ){ 
                die ("Cannot create parser");
        	}
        	
            var_dump( $xmlparser );	
        }*/
        
        
        
    }
    
    /**
     * Returns one instance of MyFuses. Only one instance is creted per requrest.
     * MyFuses is implemmented using the singleton pattern.
     * 
     * @return MyFuses
     * @static 
     */
    public static function getInstance() {
        
        if( is_null( self::$instance ) ) {
            self::$instance = new MyFuses();
        }
        
        return self::$instance; 
    }

    public function getRootParsedPath() {
        $rootParsedPath = "";
        
        if( file_exists( MYFUSES_ROOT_PATH . "parsed" ) && 
            is_readable( MYFUSES_ROOT_PATH . "parsed" ) ) {
            return MyFusesFileHandler::sanitizePath( 
                MYFUSES_ROOT_PATH . "parsed" );
        }
        else {
            return DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR;
        }
    }
    
    public function getStoredApplicationExtension() {
    	return $this->storedApplicationExtension;
    }
}