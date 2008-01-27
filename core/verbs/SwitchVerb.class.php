<?php
/**
 * SwitchVerb  - SwitchVerb.class.php
 * 
 * This is a conditional verb. You can use this verb to switch between multiples
 * processes queues.
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

/**
 * SwitchVerb  - SwitchVerb.class.php
 * 
 * This is a conditional verb. You can use this verb to switch between multiples
 * processes queues.
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
 * @since      Revision 219
 */
class SwitchVerb extends AbstractVerb {
    
    /**
     * Variable to be switched
     *
     * @var string
     */
    private $variable;
    
	/**
     * Case verbs collection
     *
     * @var array
     */
    private $caseVerbs = array();
    
    /**
     * Default verbs collection
     *
     * @var array
     */
    private $defaultVerbs = array();
    
    /**
     * Return the switch variable
     *
     * @return string
     */
    public function getVariable() {
        return $this->variable;
    }
    
    /**
     * Set the switch variable
     *
     * @param string $variable
     */
    public function setVariable( $variable ) {
        $this->variable = $variable;
    }

    public function getData() {
        $data = parent::getData();
        
        $data[ "attributes" ][ "variable" ] =  $this->getVariable();
        
        if( count( $this->caseVerbs ) ) {
            
            foreach( $this->caseVerbs as $key => $verbs ) {
                $child = null;
                $child[ "name" ] = "case";
                $child[ "namespace" ] = "myfuses";
                $child[ "attributes" ][ "value" ] = $key;
                foreach( $verbs as $verb ) {
                    $child[ 'children' ][]  = $verb->getData();    
                }
                $data[ "children" ][] = $child;
            }
            
        }
        
        if( count( $this->defaultVerbs ) ) {
            $child = null;
            $child[ "name" ] = "default";
            $child[ "namespace" ] = "myfuses";
            foreach( $this->defaultVerbs as $verb ) {
                $child[ 'children' ][]  = $verb->getData();    
            }
            $data[ "children" ][] = $child;
        }
        
        return $data;
    }
    
    public function setData( $data ) {
        
        parent::setData( $data );
        
        if( isset( $data[ "attributes" ][ "variable" ] ) ) {
            $this->setVariable( $data[ "attributes" ][ "variable" ] );
        }
        else  {
            $params = $this->getErrorParams();
            $params[ 'attrName' ] = "variable";
            throw new MyFusesVerbException( $params, 
                MyFusesVerbException::MISSING_REQUIRED_ATTRIBUTE );
        }
        
        if( isset( $data[ "children" ] ) ) {
	        if( count( $data[ "children" ] ) ) {
	            foreach( $data[ "children" ] as $child ) {
	                switch( $child[ 'name' ] ) {
	                    case 'case':
	                        $this->setCaseVerbs( $child );
	                        break;
	                    case 'default':
	                        $this->setDefaultVerbs( $child );
	                        break;
	                }
	                
	            }
	            
	        }    
        }
        
    }
    
    /**
     * Set the switch case verbs
     *
     * @param array $caseChild
     */
    private function setCaseVerbs( $caseChild ) {
        if( isset( $caseChild[ "attributes" ][ "value" ] ) ) {
            $this->caseVerbs[ $caseChild[ "attributes" ][ "value" ] ] = array();
            
            if( isset( $caseChild[ "children" ] ) ) {
    	        if( count( $caseChild[ "children" ] ) ) {
        	        foreach( $caseChild[ "children" ] as $child ){
                        $verb = AbstractVerb::getInstance( serialize( $child ), 
                            $this->getAction() );
                        $verb->setParent( $this );
                        if( !is_null( $verb ) ) {
                            $this->caseVerbs[ 
                                $caseChild[ "attributes" ][ "value" ] ][] = 
    	                        $verb;
                        }
                    }
    	            
    	        }    
            }
        }
        else  {
            $params = $this->getErrorParams();
            $params[ 'verbName' ] = 'case';
            $params[ 'attrName' ] = "variable";
            throw new MyFusesVerbException( $params, 
                MyFusesVerbException::MISSING_REQUIRED_ATTRIBUTE );
        }
        
    }
    
    /**
     * Set the switch default verbs
     *
     * @param array $defaultChild
     */
    private function setDefaultVerbs( $defaultChild ) {
        if( isset( $defaultChild[ "children" ] ) ) {
	        if( count( $defaultChild[ "children" ] ) ) {
	            foreach( $defaultChild[ "children" ] as $child ){
                    $verb = AbstractVerb::getInstance( serialize( $child ), 
                        $this->getAction() );
                    $verb->setParent( $this );
                    if( !is_null( $verb ) ) {
                        $this->defaultVerbs[] = 
	                        $verb;
                    }
                    
                }
	        }    
        }
        
    }
    
    /**
	 * Return the parsed code
	 *
	 * @return string
	 */
    public function getParsedCode( $commented, $identLevel ) {
	    $strOut = parent::getParsedCode( $commented, $identLevel );
	    
	    $switchOccour = false;
	    
	    if( count( $this->caseVerbs ) || count( $this->defaultVerbs ) ) {
	        $switchOccour = true;
	    }
	    
	    if( $switchOccour ) {
	        $strOut .= str_repeat( "\t", $identLevel );
	        $strOut .= "switch( " . $this->getVariable() . " ) {\n";
	    }
	    
        foreach( $this->caseVerbs as $key => $caseVerbs ) {
            if( count( $caseVerbs ) ) {
                $strOut .= str_repeat( "\t", $identLevel + 1 );
                $strOut .= "case( \"" . $key . "\" ) :\n";
                foreach( $caseVerbs as $verb ) {
                    $strOut .= $verb->getParsedCode( $commented, $identLevel + 2 );
                }
                $strOut .= str_repeat( "\t", $identLevel + 2 );
                $strOut .= "break;\n";
                InvokeVerb::clearClassCall();
            }
		}
	    
        
        if( count( $this->defaultVerbs ) ) {
            $strOut .= str_repeat( "\t", $identLevel + 1 );
            $strOut .= "default :\n";
            foreach( $this->defaultVerbs as $verb ) {
                $strOut .= $verb->getParsedCode( $commented, $identLevel + 2 );
            }
            $strOut .= str_repeat( "\t", $identLevel + 2 );
            $strOut .= "break;\n";
            InvokeVerb::clearClassCall();
        }
		
		
        if( $switchOccour ) {
	        $strOut .= str_repeat( "\t", $identLevel );
	        $strOut .= "}\n";
	    }
	    return $strOut;
    }
    
}