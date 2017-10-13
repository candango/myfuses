<?php
/**
 * InvokeVerb - InvokeVerb.class.php
 *
 * MyFuses Invoke Verb class
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
 * The Original Code is MyFuses "a Candango implementation of Fusebox
 * Corporation Fusebox" part .
 *
 * The Initial Developer of the Original Code is Michael Alves Lins.
 * Portions created by Michael Alves Linsa are Copyright (C) 2006 - 2007.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2007.
 * All Rights Reserved.
 *
 * Contributor(s): Michael Alves Lins
 *                 Flavio Goncalves Garcia
 *
 * @category   verb
 * @package    myfuses.core.verbs
 * @author     Michael Alves Lins <malvins@gmail.com>
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:InvokeVerb.class.php 00 2007-04-13 11:31:31Z malvins $
 */

/**
 * InstantiateVerb - InstantiateVerb.class.php
 * 
 * This verb instantiate one object by a given class or wsdl.
 * 
 * PHP version 5
 *
 * @category   verb
 * @package    myfuses.core.verbs
 * @author     Michael Alves Lins <malvins@gmail.com>
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 125
 */
class InvokeVerb extends AbstractVerb {
    
    private $class;
    
    private static $classCall = array();
    
	private $object;

	private $method;
	
	private $methodCall;
	
	private $arguments;

	private $variable;
	
	public function getClass() {
	    return $this->class;
	}
	
	public function setClass( $class ) {
	    $this->class = $class;
	}
	
	public function getObject() {
		return $this->object;
	}

	public function setObject( $object ) {
		$this->object = $object;
	}

	public function getMethod() {
		return $this->method;
	}

	public function setMethod( $method ) {
		$this->method = $method;
	}
	
    public function getMethodCall() {
		return $this->methodCall;
	}

	public function setMethodCall( $methodCall ) {
		$this->methodCall = $methodCall;
	}
	
	public function getArguments() {
		return $this->arguments;
	}

	public function setArguments( $arguments ) {
		
		$args = "";
		
		//Verify arguments - Fusebox 5 (strictMode set to true)
		if ( !is_null( $arguments ) ){
			//Gets the last child postition in arguments array
			$lastChildPos = count( $arguments ) -1;
			//Set the arguments
			foreach ( $arguments as $childPos => $atrr ){
				$args .= $atrr["attributes"]["value"];
				if ( $childPos !==  $lastChildPos )
				$args.= ',';
			}
		}
		
		$this->arguments = $args;
		
	}
	
	public function getVariable() {
		return $this->variable;
	}

	public function setVariable( $variable ) {
		$this->variable = $variable;
	}
	
	/**
     * Return o new strin with all arguments separated by a ','
     *
     * @return string
     */
    private function getArgumentString() {
        $strOut = "";
        if( count( $this->getArguments() ) ) {
            foreach( $this->getArguments() as $key => $argument ){
                $strOut .= ($key == 0 ? "": " , ") . "\"" . $argument .  "\"";
            }
        }
        return $strOut;
    }
    
    public function getData() {
        
        $data = parent::getData();
        
        if( !is_null( $this->getClass() ) ) {
            $data[ "attributes" ][ "class" ] = $this->getClass();
        }
        
        if( !is_null( $this->getObject() ) ) {
            $data[ "attributes" ][ "object" ] = $this->getObject();
        }
        
        if( !is_null( $this->getMethod() ) ) {
            $data[ "attributes" ][ "method" ] = $this->getMethod();
        }
        
        if( !is_null( $this->getArguments() ) ) {
            foreach( $this->getArguments() as $argument ) {
                $child = array();
                $child[ 'name' ] = 'argument';
                $child[ 'namespace' ] = 'myfuses';
                $child[ 'attributes' ][ 'value' ] = $argument;
                $data[ 'children' ][] = $child; 
            }
        }
        
        if(!is_null( $this->getMethodCall() ) ){
            $data[ "attributes" ][ "methodcall" ] = $this->getMethodCall();
        }
        
        if( !is_null( $this->getVariable() ) ) {
            $data[ "attributes" ][ "returnvariable" ] = $this->getVariable();
        }
        
        return $data;
    }
    
	public function setData( $data ) {
		
	    parent::setData( $data );
	    
	    if( isset( $data[ "attributes" ][ "class" ] ) ) {
		    $this->setClass( $data[ "attributes" ][ "class" ] );
		}
		else {
		    $this->setObject( $data[ "attributes" ][ "object" ] );    
		}
	    
	    if( isset( $data[ "attributes" ][ "method" ] ) ) {
		    $this->setMethod( $data[ "attributes" ][ "method" ] );    
	    }
	    
	    if( isset( $data[ "children" ] ) ) {
            foreach( $data[ "children" ] as $child ) {
                if( $child[ 'name' ] == 'argument' ) {
                    if( isset( $child[ 'attributes' ][ 'value' ] ) ) {
                        $this->arguments[] = $child[ 'attributes' ][ 'value' ];
                    }
                    else  {
                        $params = $this->getErrorParams();
                        $params[ 'verbName' ] = "argument";
                        $params[ 'attrName' ] = "value";
                        throw new MyFusesVerbException( $params, 
                            MyFusesVerbException::MISSING_REQUIRED_ATTRIBUTE );
                    }
                }
            }
        }
	    
	    
	    if( isset( $data[ "attributes" ][ "methodcall" ] ) ) {
	        $this->setMethodCall( $data[ "attributes" ][ "methodcall" ] );
	    }
	    
	    if( isset( $data[ "attributes" ][ "returnvariable" ] ) ) {
		    $this->setVariable( $data[ "attributes" ][ "returnvariable" ] );
        }
	}

	/**
	 * Return the parsed code
	 *
	 * @return string
	 */
	public function getParsedCode( $commented, $identLevel ) {
		$appName = $this->getAction()->getCircuit()->
		    getApplication()->getName();
		
		$strOut = parent::getParsedCode( $commented, $identLevel );
		// Make identation
		$strOut .= str_repeat( "\t", $identLevel );
		
    	if( !is_null( $this->getClass() ) ) {
            if( !isset( self::$classCall[ $this->getClass() ] ) ) {

                $appName = $this->getAction()->getCircuit()->
                    getApplication()->getName();
                
                $controllerClass = $this->getAction()->getCircuit()->
                    getApplication()->getControllerClass();
                    
                $fileCall = $controllerClass . 
                    "::getInstance()->getApplication( \"" . $appName .
                    "\" )->getClass( \"" . $this->getClass() . 
                    "\" )->getCompletePath()";
                
                $strOut .= "if ( file_exists( " . $fileCall . " ) ) {\n";
                $strOut .= str_repeat( "\t", $identLevel + 1 );
                $strOut .= "require_once( " . $fileCall . " );\n";
                $strOut .= str_repeat( "\t", $identLevel );
                $strOut .= "}\n";
                $strOut .= str_repeat( "\t", $identLevel );
                
                self::$classCall[ $this->getClass() ] = "called";
                
            }
    	}
		
    	if( !is_null( $this->getVariable() ) ) {
    	    $strOut .= "MyFusesContext::setVariable( \"" .
                $this->getVariable() . "\", ";
    	}
    	
		// Begin method call
		if( !is_null( $this->getMethod() ) ) {
            $hasParentheses = false;
            #TODO: Test if the parentheses was closed.
            if (strpos($this->getMethod(),'(') !== false) {
                $hasParentheses = true;
            }
			if( is_null( $this->getClass() ) ) {
                $strOut .= "MyFusesContext::getVariable( \"" .
                    $this->getObject() . "\" )->" .
                    $this->getMethod();
                if(!$hasParentheses) {
                    $strOut .= "( ";
                }

			}
			else {
                $strOut .= $this->getClass() . "::" . 
                    $this->getMethod();
                if(!$hasParentheses) {
                    $strOut .= "( ";
                }
			}
		    
			//TODO: Verify arguments - Fusebox 5 (strictMode set to true)
			if ( !is_null( $this->getArguments() ) ){
                $strOut .= $this->getArgumentString();
			}
            // Close method
			if(!$hasParentheses) {
                $strOut .= " )";
            }
		}
		else {
            $strOut .= "\$" . $this->getObject() . "->" .
                $this->getMethodCall();
		}
		
        if( is_null( $this->getVariable() ) ) {
            $strOut .= ";\n\n";
        }
		else {
            $strOut .= " );\n";
            $strOut .= $this->getContextRestoreString();
        }
        
		return $strOut;
	}
    
	public static function clearClassCall() {
	    self::$classCall = array();
	}
	
}
