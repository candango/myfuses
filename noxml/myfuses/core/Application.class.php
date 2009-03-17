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
	
    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     */
    public function isDefault();
    
    /**
     * Set if the application is default or not
     * 
     * @param boolean $default
     */
    public function setDefault( $default );
    
	/**
     * Returns the application name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Sets the application name
     *
     * @param string $name
     */
    public function setName( $name );
	
	/**
     * Returns the application path
     *
     * @return string
     */
    public function getPath();
    
    /**
     * Sets the application path
     *
     * @param string $path
     */
    public function setPath( $path );
	
	/**
	 * Return the parsed path.
	 * 
	 * @return string
	 */
	public function getParsedPath();
	
	public function getParsedApplicationFile();
	
	/**
     * Returns if the application is started or not
     * 
     * @return boolean
     */
    public function isStarted();
    
    /**
     * Set if the application is started or not
     * 
     * @param boolean $value
     */
    public function setStarted( $started );
	
    public function getStartTime();
    
	/**
	 * Will fire the onApplicationStart event
	 */
	public function fireApplicationStart();
	
	/**
	 * Will fire the onPreProcess event
	 */
	public function firePreProcess();
	
	/**
	 * Will fire the onPostProcess event
	 */
	public function firePostProcess();
	
}

abstract class AbstractApplication implements Application {
	
	/**
     * Default application flag
     *
     * @var boolean
     */
    private $default = false;
	
    /**
     * Application started state
     * 
     * @var boolean
     */
    private $started = false;
    
	/**
     * Application name
     * 
     * @var string
	 */
	private $name;
	
	/**
     * Application path
     * 
     * @var string
	 */
	private $path;
	
	private $startTime;
	
	public function __construct() {
		$this->startTime = time();
	}
	
    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     */
    public function isDefault(){
        return $this->default;
    }
    
    /**
     * Set if the application is default or not
     * 
     * @param boolean $default
     */
    public function setDefault( $default ) {
        $this->default = $default;
    }
	
    /**
     * Returns the application name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Sets the application name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
	
    /**
     * Returns the application path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Sets the application path
     *
     * @param string $path
     */
    public function setPath( $path ) {
    	$this->path = MyFusesFileHandler::sanitizePath( $path );
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
    
    /**
     * Returns if the application is started or not
     * 
     * @return boolean
     */
    public function isStarted() {
    	return $this->started;
    }
    
    /**
     * Set if the application is started or not
     * 
     * @param boolean $started
     */
    public function setStarted( $started ) {
    	$this->started = $started;
    }
    
    public function getStartTime() {
        return $this->startTime;    
    }
    
    /**
     * Will fire the onApplicationStart event
     */
    public function fireApplicationStart() {
        // fire some action
    }
    
    /**
     * Will fire the onPreProcess event
     */
    public function firePreProcess() {
    	// fire some action
    }
    
    /**
     * Will fire the onPostProcess event
     */
    public function firePostProcess() {
    	// fire some action
    }
    
}

class BasicApplication extends AbstractApplication {
	
}