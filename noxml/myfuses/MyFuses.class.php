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
        }
        
        $this->addApplication( $application );
        
        return $application;
    }

    public function addApplication( Application $application ) {
    	$this->applications[ $application->getName() ] = $application; 
    }
    
    public function getApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) { 
        
        if( isset( $this->applications[ $name ] ) ) {
            return $this->applications[ $name ];
        }
    }
    
    public function getApplications() { 
        return $this->applications;
    }
    
    public function doProcess() {
        
        MyFusesLifecycle::storeApplications( $this );
        
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