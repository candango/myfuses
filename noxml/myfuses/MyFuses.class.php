<?php

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
	
	
	public function createApplication() {
		
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