<?php
/**
 * Do file
 *
 */
class DoVerb extends AbstractVerb {
    
    /**
     * Circuit name to be executed
     *
     * @var string
     */
    private $circuitToBeExecutedName;
    
    
    /**
     * Action name to be executed
     *
     * @var string
     */
    private $actionToBeExecutedName;
    
    public function setActionToBeExecuted( $actionName ) {
        $actionNameX = explode( '.', $actionName );
        if ( count( $actionNameX ) < 2 ) {
            $this->circuitToBeExecutedName = $this->getAction()->getCircuit()->getName();
            $this->actionToBeExecutedName = $actionName;
        }
        else {
			$this->circuitToBeExecutedName = $actionNameX[ 0 ];
			$this->actionToBeExecutedName = $actionNameX[ 1 ];
        }
        
    }
    
    public function getParms() {
        $parms[ "action" ] = $this->circuitToBeExecutedName . "." . $this->actionToBeExecutedName;
        return $parms;
    }
    
    public function getCachedCode() {
	    $strOut = "\$verb = AbstractVerb::getInstance( \"DoVerb\", array( \"action\" => \"" . $this->circuitToBeExecutedName . "." . $this->actionToBeExecutedName . "\" ), \$action );\n";
        return $strOut;
	}
    
    /**
     * Fill params
     *
     * @param array $params
     */
    public function setParams( $params ) {
        
        $this->setActionToBeExecuted( $params[ "action" ] );
        
    }
    
}