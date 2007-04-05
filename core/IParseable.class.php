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
    public function getParsedCode( $comented, $identLevel );
    
    /**
     * Return the parsed comments
     *
     * @return string
     */
    public function getComments( $identLevel );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */