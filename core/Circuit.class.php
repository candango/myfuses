<?php
/**
 * Enter description here...
 *
 */
class Circuit {
    
    /**
     * Circuit name
     *
     * @var string
     */
    private $name;
    
    /**
     * Circuit access type
     *
     * @var integer
     */
    private $access;
    
    /**
     * Cicuit actions
     *
     * @var array
     */
    private $actions;
    
    /**
     * Return the circuit name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set the circuit name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Return the circuit access
     *
     * @return integer
     */
    public function getAccess(){
        return $this->access;
    }
    
    /**
     * Set the circuit access
     *
     * @param integer $access
     */
    public function setAccess( $access ) {
        $this->access;
    }
    
    /**
     * Add one action to circuit
     * 
     * @param Action $action
     */
    public function addAcction( Action $action ) {
         $this->actions[] = $action;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */