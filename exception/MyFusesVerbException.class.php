<?php
/**
 * MyFusesVerbException - MyFusesVerbException.class.php
 * 
 * Class that handles all verbs exptions.
 * 
 * PHP version 5
 * 
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * This product includes software developed by the Fusebox Corporation 
 * (http://www.fusebox.org/).
 * 
 * The Original Code is Fuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2007.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

/**
 * MyFusesVerbException - MyFusesVerbException.class.php
 * 
 * Class that handles all verbs exptions.
 * 
 * PHP version 5
 *
 * @category   exception
 * @package    myfuses.exception
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class MyFusesVerbException extends MyFusesException {
   
    /**
     * Missing require attribute error constant <br>
     * value 1
     * 
     * @var integer
     */
    const MISSING_REQUIRED_ATTRIBUTE = 1;
    
    /**
     * Missing namespace error constant <br>
     * value 1
     * 
     * @var integer
     */
    const MISSING_NAMESPACE = 2;
    
    /**
     * Non-existent verb error constant<br>
     * value 2
     * 
     * @var integer
     */
    const NON_EXISTENT_VERB = 3;
    
    /**
     * Exception constructor
     *
     * @param array $params
     * @param integer $operation
     */
    public function __construct( $params, $operation ) {
    	
        $operationMessageMap = array(
            self::MISSING_REQUIRED_ATTRIBUTE => 
                "getMissingRequiredAttributeMessage",
            self::MISSING_NAMESPACE => "getMissingNamespaceMessage",
            self::NON_EXISTENT_VERB => 
                "getNonExistentVerbMessage"
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
	            "\" and inform the missing \"" . $params[ "attrName" ] . 
	            "\" attribute." );
    }
    
    private function getMissingNamespaceMessage( $params ) {
        return array(
	       0 => "You have one \"" . $params[ "verbName" ] . 
	            "\" verb with undefined namespace " . 
	            "in fuseaction \"" . $params[ "actionName" ] . 
	            "\" in circuit \"" . $params[ "circuitName" ] .
	            "\" in application \"" . $params[ "appName" ] . 
	            "\".",
	       1 => "Check your Custom Verb and verify the reason why the " . 
	            "namespace wasn't informed." );
    }
    
    private function getNonExistentVerbMessage( $params ) {
        return array(
	        0 => "You have a non existent \"" . $params[ "verbName" ] . 
	            "\" verb with in fuseaction \"" . $params[ "actionName" ] . 
	            "\" in circuit \"" . $params[ "circuitName" ] .
	            "\" in application \"" . $params[ "appName" ] . 
	            "\".",
	        1 => "Check the  \"" . $params[ "circuitFile" ] . 
	            "\" file in fuseaction \"" . $params[ "actionName" ] . 
	            "\" and fix this error." );
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */