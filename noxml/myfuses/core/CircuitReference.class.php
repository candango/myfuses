<?php
require_once MYFUSES_ROOT_PATH . "core/AbstractCircuitReference.class.php";
require_once MYFUSES_ROOT_PATH . "core/BasicCircuitReference.class.php";

interface CircuitReference {
	
	public function getName();
    
    public function setName( $name ); 
    
    public function getPath();
    
    public function setPath( $path );
    
    public function getParent();
    
    public function setParent( $parent );
    
}