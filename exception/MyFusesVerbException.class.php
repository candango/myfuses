<?php
/**
 * Enter description here...
 *
 */
class MyFusesVerbException extends MyFusesException {
    
    /**
     * Non-existent circuit contant <br>
     * value 1
     * 
     * @var integer
     */
    const MISSING_REQUIRED_ATTRIBUTE = 1;
    
    /**
     * Exception constructor
     *
     * @param array $params
     * @param integer $operation
     */
    public function __construct( $params, $operation ) {
    	
        $operationMessageMap = array(
            self::MISSING_REQUIRED_ATTRIBUTE => "getMissingRequiredAttributeMessage",
            self::USER_TRYING_ACCESS_INTERNAL_CIRCUIT => 
                "getUserTryingAccessInternalCircuitMessage"
        );
        
        list( $msg, $detail ) = 
            $this->$operationMessageMap[ $operation ]( $params );
        
        parent::__construct( $msg, $detail, 
            self::MISSING_REQUIRED_ATTRIBUTE );
    }
    
    /**
     * Return an array with message and datails of a non-existent 
     * circuit exception
     *
     * @param array $params
     * @return array
     */
    private function getMissingRequiredAttributeMessage( $params ) {
        return @array(
	        0 => "You have one \"" . $params[ "verbName" ] . 
	            "\" verb with a missing \"" . $params[ "attrName" ] . 
	            "\" attribute in fuseaction \"" . $params[ "actionName" ] . 
	            "\" in circuit \"" . $params[ "circuitName" ] .
	            "\" in application \"" . $params[ "appName" ] . 
	            "\".",
	        1 => "Check the  \"" . $params[ "circuitFile" ] . 
	            "\" file in fuseaction \"" . $params[ "actionName" ] . 
	            " and inform the missing \"" . $params[ "attrName" ] . 
	            "\" attribute." );
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