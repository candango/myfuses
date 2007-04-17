<?php
require_once "myfuses/core/Action.class.php";
require_once "myfuses/core/Circuit.class.php";
require_once "myfuses/core/Verb.class.php";

interface CircuitAction extends Action {
    
    /**
     * Return the action circtui
     * 
     * @return Circuit
     */
    public function getCircuit();
    
    /**
     * Set the action cicuit
     *
     * @param Circuit $circuit
     */
    public function setCircuit( Circuit &$circuit );
    
    /**
     * Return Circuit Action complete name.<br>
     * Complete name is circuit name plus dot plus action name.
     *
     * return string
     */
    public function getCompleteName();
    
    /**
     * Enter description here...
     *
     * @param Verb $verb
     */
    public function addVerb( Verb $verb );
    
    /**
     * Enter description here...
     *
     * @param string $name
     */
    public function getVerb( $name );
    
    /**
     * Enter description here...
     *
     */
    public function getVerbs();
    
    /**
     * Enter description here...
     *
     */
    public function getXFAs();
    
    /**
     * Enter description here...
     *
     * @param string $name
     * @param string $value
     */
    public function addXFA( $name, $value );

}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */