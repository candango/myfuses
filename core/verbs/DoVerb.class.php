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
    
    public function getData() {
        $data[ "name" ] = "do";
        $data[ "attributes" ][ "action" ] = $this->circuitToBeExecutedName . "." . $this->actionToBeExecutedName;
        return $data;
    }
    
    public function setData( $data ) {
        $this->setActionToBeExecuted( $data[ "attributes" ][ "action" ] );
    }
    
    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $comented, $identLevel ) {
        
    }

    /**
     * Return the parsed comments
     *
     * @return string
     */
    public function getComments( $identLevel ) {
        
    }
    
}