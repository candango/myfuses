<?php
/**
 * Enter description here...
 *
 */
class FuseAction extends AbstractAction implements CircuitAction {
    
    /**
     * Enter description here...
     *
     * @var Circuit
     */
    private $circtuit;
    
    /**
     * Enter description here...
     *
     * @var array
     */
    private $verbs = array();
    
    public function __construct( Circuit $circuit ) {
        $this->setCircuit( $circuit );
    }
    
	/**
     * Enter description here...
     *
     * @return Circuit
     */
    public function getCircuit() {
         return $this->circtuit;
    }
    
    /**
     * Enter description here...
     *
     * @param Circuit $circuit
     */
    public function setCircuit( Circuit &$circuit ) {
        $this->circtuit = &$circuit;
    }
    
    /**
     * Enter description here...
     *
     * @param Verb $verb
     */
    public function addVerb( Verb $verb ) {
        $this->verbs[] = $verb;
        $verb->setAction( $this );
    }
    
    /**
     * Enter description here...
     *
     * @param string $name
     * @return Verb
     */
    public function getVerb( $name ) {
        return $this->verbs[ $name ];
    }
    
    /**
     * Enter description here...
     *
     * @return array
     */
    public function getVerbs() {
        return $this->verbs;
    }
    
    /**
     * Enter description here...
     *
     * @return string
     */
	public function getCachedCode() {
	    $strOut = "\$action = new FuseAction( \$circuit );\n";
        $strOut .= "\$action->setName( \"" . $this->getName() . "\" );";
        $strOut .= $this->getVerbsCachedCode();
        return $strOut;
	}
    
	/**
     * Returns all Action Verbs cache code
     * 
     * @return string
     */
    private function getVerbsCachedCode() {
        
        $strOut = "\n";
        
        foreach( $this->verbs as $verb ) {
            $strOut .= $verb->getCachedCode() . "\n";
            $strOut .= "\$action->addVerb( \$verb );\n\n";
        }
        
        return $strOut;
    }
	
	public function doAction() {
	    
	}
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */