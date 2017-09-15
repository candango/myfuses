<?php
/**
 * Enter description here...
 *
 */
class MyFusesCircuitException extends MyFusesException {
    
    /**
     * Non-existent circuit contant <br>
     * value 1
     * 
     * @var integer
     */
    const NON_EXISTENT_CIRCUIT = 1;
    
    
    /**
     * Exception constructor
     *
     * @param array $params
     * @param integer $operation
     */
    public function __construct( $params, $operation ) {
    	$msg = "";
        $detail = "";

        switch ($operation){
            case self::NON_EXISTENT_CIRCUIT:
                list( $msg, $detail ) = $this->getNonExistentCircuitMessage($params);
                break;
            case self::USER_TRYING_ACCESS_INTERNAL_CIRCUIT:
                list( $msg, $detail ) = $this->getUserTryingAccessInternalCircuitMessage($params);
                break;

        }

        parent::__construct( $msg, $detail, $operation);
    }
    
    /**
     * Return an array with message and datails of a non-existent 
     * circuit exception
     *
     * @param array $params
     * @return array
     */
    private function getNonExistentCircuitMessage( $params ) {
        return array(
	        0 => "Could not find the circuit \"" . $params[ "circuitName" ] .
	            "\" in application \"" . $params[ "application" ]->getName() . 
	            "\".",
	        1 => "The circuit  \"" . $params[ "circuitName" ] . 
	            "\" wasn't found in application \"" . 
	            $params[ "application" ]->getName()  . "\". " .
	            "You can check this in circuits session of the \"" . 
	            $params[ "application" ]->getCompleteFile() . "\" file." );
    }
    
    private function getUserTryingAccessInternalCircuitMessage( $params ) {
        return array(
	        0 => "The Circuit \"" . $params[ "circuitName" ] .
	            "\" in application \"" . $params[ "application" ]->getName() . 
	            "\" is a <b>internal</b> Circuit.",
	        1 => "You cannot access the circuit  \"" . 
	            $params[ "circuitName" ] . " by a browser " . 
	            "You can check this in circuit access parameter of the \"" . 
	            $params[ "application" ]->getCompleteFile() . "\" file." );
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */