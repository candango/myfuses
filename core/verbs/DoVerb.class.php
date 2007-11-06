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
            try {
                $this->circuitToBeExecutedName = $this->getAction()->getCircuit()->getName();    
            }
	        catch ( MyFusesCircuitException $mfe ) {
	            $mfe->breakProcess();
	        }
            $this->actionToBeExecutedName = $actionName;
        }
        else {
			$this->circuitToBeExecutedName = $actionNameX[ 0 ];
			$this->actionToBeExecutedName = $actionNameX[ 1 ];
        }
        
    }
    
    public function getData() {
        $data = parent::getData();
        $data[ "attributes" ][ "action" ] = $this->circuitToBeExecutedName . "." . $this->actionToBeExecutedName;
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        $this->setActionToBeExecuted( $data[ "attributes" ][ "action" ] );
    }
    
	/**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        try {
	        $action = $this->getAction()->getCircuit()->
	            getApplication()->getCircuit( 
	            $this->circuitToBeExecutedName )->
	            getAction( $this->actionToBeExecutedName );
        }
        catch ( MyFusesCircuitException $mfce ) {
            $mfce->breakProcess();
        }
        catch ( MyFusesFuseActionException $mffae ) {
            $mffae->breakProcess();
        }
        
        $strOut = parent::getParsedCode( $commented, $identLevel );
        // FIXME resolver plugins, persar direito isso
        
        $action->setCalledByDo( true );
        
        $strOut .= $action->getParsedCode( $commented, $identLevel + 1 );
        
        $action->setCalledByDo( false );
        
        return $strOut;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */