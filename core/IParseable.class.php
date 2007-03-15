<?php
/**
 * Enter description here...
 *
 */
interface IParseable {
    
    /**
     * Return the parsed code
     * 
     * @return string
     */
    public function getCode();
    
    /**
     * Return the parsed comments
     *
     * @return string
     */
    public function getComments();
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */