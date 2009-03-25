<?php
/**
 * Enter description here...
 *
 */
class MyFusesActionException extends MyFusesException {
    
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
    	
        $operationMessageMap = array(
            self::NON_EXISTENT_FUSEACTION => "getNonExistentFuseActionMessage"
        );
        
        list( $msg, $detail ) = 
            $this->$operationMessageMap[ $operation ]( $params );
        
        parent::__construct( $msg, $detail, 
            MyFusesException::NON_EXISTENT_FUSEACTION );
    }
    
    /**
     * Return an array with message and datails of a non-existent 
     * circuit exception
     *
     * @param array $params
     * @return array
     */
    private function getNonExistentFuseActionMessage( $params ) {
        return array(
	        0 => "Could not find the FuseAction \"" . $params[ "actionName" ] .
	            "\" in circuit \"" . $params[ "circuit" ]->getName() .
	            "\" in application \"" . $params[ "application" ]->getName() . 
	            "\".",
	        1 => "The FuseAction  \"" . $params[ "actionName" ] . 
	            "\" wasn't found in circuit \"" . 
	            $params[ "circuit" ]->getName()  . "\" in application \"" .
	            $params[ "application" ]->getName()  . "\". " .
	            "You can check if the FuseAction exists in " .
	            "circuit file \"" . 
	            $params[ "circuit" ]->getCompleteFile() . "\"." );
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */