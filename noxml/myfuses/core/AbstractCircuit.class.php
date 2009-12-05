<?php
abstract class AbstractCircuit implements Circuit {
	
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
	
}