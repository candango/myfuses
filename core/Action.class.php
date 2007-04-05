<?php
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
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */