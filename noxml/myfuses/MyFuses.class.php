<?php
require_once "myfuses/core/Application.class.php";


class MyFuses {
	
	/**
     * Array of registered applications
     * 
     * @var array
     */
    protected $applications = array();
	
	/**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
	private static $instance;
	
	
	public function createApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
            
        $application = new BasicApplication();
        
        $application->setName( $name );
        
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
	
}