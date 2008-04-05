<?php
/**
 * Enter description here...
 *
 */
class MyFusesApplicationException extends MyFusesException {
    
    /**
     * Non-existent application contant <br>
     * value 1
     * 
     * @var integer
     */
    const NON_EXISTENT_APPLICATION = 1;
    
    
    /**
     * Exception constructor
     *
     * @param array $params
     * @param integer $operation
     */
    public function __construct( $params, $operation ) {
    	
        $operationMessageMap = array(
            self::NON_EXISTENT_APPLICATION => 
                "getNonExistentApplicationMessage"
        );
        
        list( $msg, $detail ) = 
            $this->$operationMessageMap[ $operation ]( $params );
        
        parent::__construct( $msg, $detail, 
            MyFusesException::NON_EXISTENT_CIRCUIT );
    }
    
    /**
     * Return an array with message and datails of a non-existent 
     * circuit exception
     *
     * @param array $params
     * @return array
     */
    private function getNonExistentApplicationMessage( $params ) {
        return array(
	        0 => "Could not find the application \"" . 
	           $params[ "applicationName" ] . "\".",
	        1 => "The application  \"" . $params[ "applicationName" ] . 
	            "\" wasn't found in MyFuses context." );
    }
    
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */