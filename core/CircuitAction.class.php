<?php
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
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */