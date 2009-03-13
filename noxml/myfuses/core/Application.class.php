<?php


interface Application {

	/**
     * Default applicatication name
     * 
     * @var string
     * @static 
     * @final
     */
    const DEFAULT_APPLICATION_NAME = "default";
	
	public function getName();
	
	public function setName( $name ); 
	
	public function setPath();
	
	public function getPath( $path );
	
}

abstract class AbstractApplication implements Application {
	
	/**
     * Application name
     * 
     * @access private
     */
	private $name;
	
	/**
     * Application path
     * 
     * @access private
     */
	private $path;
	
	public function getName() {
		return $this->name;
	}
	
	public function setName( $name ) {
		$this->name = $name;
	}
	
	public function setPath() {
        return $this->path;
	}
    
    public function getPath( $path ) {
    	
    }
	
}

class BasicApplication extends AbstractApplication {
	
}