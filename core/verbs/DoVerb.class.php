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
    public function getParsedCode( $commented, $identLevel ) {
        $action = $this->getAction()->getCircuit()->
            getApplication()->getCircuit( "" );
        
        
        $strOut = parent::getParsedCode( $commented, $identLevel );
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "if ( file_exists( \"" . $file . "\" ) ) {\n";
        $strOut .= str_repeat( "\t", $identLevel + 1 );
        $strOut .= "include( \"" . $file . "\" );\n";
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "}\n\n";
        return $strOut;
    }

    /**
     * Return the parsed comments
     *
     * @return string
     */
    public function getComments( $identLevel ) {
        $strOut = parent::getComments( $identLevel );
        $strOut = str_replace( "__COMMENT__",
            "MyFuses:request:action:include file=\"" . 
            $this->getFile() . "\"", $strOut );
        return $strOut;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */