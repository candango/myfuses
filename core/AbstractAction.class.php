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
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */