<?php
abstract class AbstractCircuitReference implements CircuitReference {
    
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
    
    private $parent;
    
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
    
    public function getParent() {
    	return $this->parent;
    }
    
    public function setParent( $parent ) {
    	$this->parent = $parent;
    }
    
}