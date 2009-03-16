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
	
	public function getPath();
	
	public function setPath( $path );
	
	/**
	 * Return the parsed path.
	 * 
	 * @return string
	 */
	public function getParsedPath();
	
	public function getParsedApplicationFile();
	
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
	
	public function getPath() {
        return $this->path;
	}
    
    public function setPath( $path ) {
    	$this->path = $path;
    }
	
    public function getParsedPath() {
    	return MyFusesFileHandler::sanitizePath( 
    	   MyFuses::getInstance()->getRootParsedPath() . 
    	   $this->getName() );
    }
    
    public function getParsedApplicationFile() { 
    	return $this->getParsedPath() . $this->getName() . 
    	   MyFuses::getInstance()->getStoredApplicationExtension();
    }
    
}

class BasicApplication extends AbstractApplication {
	
}