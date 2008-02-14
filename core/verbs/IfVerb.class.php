<?php
/**
 * IfVerb  - IfVerb.class.php
 * 
 * This is a conditional verb. Using one condition IfVerb will switch the 
 * processes execution by true or false queues.
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
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2007.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

require_once "myfuses/core/verbs/InvokeVerb.class.php";

/**
 * IfVerb  - IfVerb.class.php
 * 
 * This is a conditional verb. Using one condition IfVerb will switch the 
 * processes execution by true or false queues.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 125
 */
class IfVerb extends AbstractVerb {
    
    
    private $condition;
    
    private $trueVerbs = array();
    
    private $falseVerbs = array();
    
    public function getCondition() {
        return $this->condition;
    }
    
    public function setCondition( $condition ) {
        $this->condition = $condition;
    }

    public function getData() {
        $data = parent::getData();
        
        $data[ "attributes" ][ "condition" ] =  $this->getCondition();
        
        if( count( $this->trueVerbs ) ) {
            $child[ "name" ] = "true";
            foreach( $this->trueVerbs as $verb ) {
                $child[ "children" ][] = $verb->getData();
            }
            $data[ "children" ][] = $child;
        }
        
        unset( $child );
        
        if( count( $this->falseVerbs ) ) {
            $child[ "name" ] = "false";
            foreach( $this->falseVerbs as $verb ) {
                $child[ "children" ][] = $verb->getData();
            }
            $data[ "children" ][] = $child;
        }
        
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        
        $this->setCondition( $data[ "attributes" ][ "condition" ] );
        
        if( isset( $data[ "children" ] ) ) {
	        if( count( $data[ "children" ] ) ) {
	            
	            foreach( $data[ "children" ] as $child ) {
	                
	                $type = $child[ "name" ];
	                
	                if ( count( $child[ "children" ] ) ) {
	                    $this->setIfVerbs( $type, $child[ 'children' ] );    
	                }
	                
	            }
	        }    
        }
        
    }
    
    private function setIfVerbs( $type, $children ) {
        
        $method = "";
        
        if( $type == 'true' ) {
            $method = "addTrueVerb";
        }
        else {
            $method = "addFalseVerb";
        }
           
        foreach( $children as $child ){
            $verb = AbstractVerb::getInstance( serialize( $child ), $this->getAction() );
            if( !is_null( $verb ) ) {
                $this->$method( $verb );
            }
        }
        
    }
    
    /**
     * Add a true verb
     *
     * @param Verb $verb
     */
    public function addTrueVerb( Verb $verb ) {
       $this->trueVerbs[] = $verb;
       $verb->setParent( $this ); 
    }
    
	/**
     * Add a false verb
     *
     * @param Verb $verb
     */
    public function addFalseVerb( Verb $verb ) {
       $this->falseVerbs[] = $verb;
       $verb->setParent( $this ); 
    }
	
    /**
	 * Return the parsed code
	 *
	 * @return string
	 */
    public function getParsedCode( $commented, $identLevel ) {
	    $strOut = parent::getParsedCode( $commented, $identLevel );
	    
	    $trueOccour = false;
	    
	    if( count( $this->trueVerbs ) ) {
	        $strOut .= str_repeat( "\t", $identLevel );
	        
	        $strOut .= "if( " . $this->getCondition() . " ) {\n";
		    
		    foreach(  $this->trueVerbs as $verb ) {
		        $strOut .= $verb->getParsedCode( $commented, $identLevel + 1 );
		    }
		    
		    $strOut .= str_repeat( "\t", $identLevel );
		    
		    $strOut .= "}\n";
		    
		    $trueOccour = true;
	    }
        InvokeVerb::clearClassCall();
	    if( count( $this->falseVerbs ) ) {
	        
	        $strOut .= str_repeat( "\t", $identLevel );
	        
	        if( $trueOccour ) {
	            $strOut .= "else {\n";
	        }
	        else {
	            $strOut .= "if( !( " . $this->getCondition() . " ) ) {\n";
	        }
	        
	        foreach(  $this->falseVerbs as $verb ) {
	            $strOut .= $verb->getParsedCode( $commented, $identLevel + 1 );
	        }

	        $strOut .= str_repeat( "\t", $identLevel );

	        $strOut .= "}\n";
	        
	    }
	    InvokeVerb::clearClassCall();
	    return $strOut;
    }
    
}