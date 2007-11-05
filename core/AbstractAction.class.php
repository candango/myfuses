<?php
require_once "myfuses/core/Action.class.php";

/**
 * Enter description here...
 *
 */
abstract class AbstractAction implements Action {
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    private $name;
    
    /**
     * Custom attributes defined by develloper
     * 
     * @var array 
     */
    protected $customAttributes = array();
    
    /**
     * Enter description here...
     *
     * @return unknown
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Enter description here...
     *
     * @param unknown_type $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    public function setCustomAttribute( $namespace, $name, $value ) {
        $this->customAttributes[ $namespace ][ $name ] = $value;
    }
    
    public function getCustomAttribute( $namespace, $name ) {
        if( isset( $this->customAttributes[ $namespace ][ $name ] ) ) {
            return $this->customAttributes[ $namespace ][ $name ];
        }
        return null;
    }
    
    public function getCustomAttributes( $namespace ) {
        return $this->customAttributes[ $namespace ];
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */