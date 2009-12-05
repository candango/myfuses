<?php
require_once MYFUSES_ROOT_PATH . "core/AbstractCircuit.class.php";
require_once MYFUSES_ROOT_PATH . "core/BasicCircuit.class.php";

interface Circuit {

	public function getName();
	
	public function setName( $name ); 
	
	public function getPath();
	
	public function setPath( $path );
	
}