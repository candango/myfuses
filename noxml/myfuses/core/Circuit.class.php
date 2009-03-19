<?php
interface Circuit {

	public function getName();
	
	public function setName( $name ); 
	
	public function getPath();
	
	public function setPath( $path );
	
}

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

class BasicCircuit extends AbstractCircuit {
	
}

interface CircuitReference {
	
	public function getName();
    
    public function setName( $name ); 
    
    public function getPath();
    
    public function setPath( $path );
    
    public function getParent();
    
    public function setParent( $parent );
    
}

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

class BasicCircuitReference extends AbstractCircuitReference {
    
}