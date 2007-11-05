<?php
require_once "myfuses/core/ICacheable.class.php";
require_once "myfuses/core/IParseable.class.php";

/**
 * 
 *
 */
interface Action extends ICacheable, IParseable {
   
    /**
     * Return the action name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Set the action name
     *
     * @param string $name
     */
    public function setName( $name );
    
    public function doAction();

    public function setCustomAttribute( $namespace, $name, $value );
    
    public function getCustomAttribute( $namespace, $name );
    
    public function getCustomAttributes( $namespace );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */