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
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 *
 * Contributor(s): Michael Alves Lins
 *
 * @category   verb
 * @package    myfuses.core.verbs
 * @author     Michael Alves Lins <malvins@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:InvokeVerb.class.php 00 2007-04-13 11:31:31Z malvins $
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
	
	public function getData() {
		
	    $data = parent::getData();
		
		if( !is_null( $this->getClass() ) ) {
		    $data[ "attributes" ][ "class" ] = $this->getClass();
		}
		else {
		    $data[ "attributes" ][ "object" ] = $this->getObject();    
		}
		
		if( !is_null( $this->getMethod() ) ) {
			$data[ "attributes" ][ "method" ] = $this->getMethod();
			if( !is_null( $this->getArguments() ) ) {
				$data[ "attributes" ][ "argument" ] = $this->getArguments();
			}
		}
		else {
		    $data[ "attributes" ][ "methodcall" ] = $this->getMethodCall();
		}
		
	    if( !is_null( $this->getVariable() ) ) {
			$data[ "attributes" ][ "variable" ] = $this->getVariable();
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
	
			if( isset( $data[ "children" ] ) ) {
				$this->setArguments( $data[ "children" ] );
			}    
	    }
	    else {
	        $this->setMethodCall( $data[ "attributes" ][ "methodcall" ] );
	    }
	    
	    if( isset( $data[ "attributes" ][ "variable" ] ) ) {
		    $this->setVariable( $data[ "attributes" ][ "variable" ] );
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
		
		// Begin method call
		if( !is_null( $this->getMethod() ) ) {
			
			if( !is_null( $this->getClass() ) ) {
			    
			    if( !isset( self::$classCall[ $this->getClass() ] ) ) {

			        $appName = $this->getAction()->getCircuit()->
				        getApplication()->getName();
			        
				    $controllerClass = $this->getAction()->getCircuit()->
				        getApplication()->getControllerClass();
				        
				    $fileCall = $controllerClass . "::getApplication( \"" . $appName .
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
			    
			    // Verify if it has a variable (Method returns a value)
				if ( !is_null( $this->getVariable() ) ) {
					$strOut .= "\$" . $this->getVariable() . " = ";
				}
			     
			    $strOut .= $this->getClass() . "::" . 
			        $this->getMethod() . "(";    
			}
			else {
			    // Verify if it has a variable (Method returns a value)
				if ( !is_null( $this->getVariable() ) ) {
					$strOut .= "\$" . $this->getVariable() . " = ";
				}
			    $strOut .= "\$" . $this->getObject() . "->" . 
			        $this->getMethod() . "(";    
			}
			
			// Verify arguments - Fusebox 5 (strictMode set to true)
			if ( !is_null( $this->getArguments() ) )
				$strOut .= $this->getArguments();
		    // Close method
	        $strOut .= ");\n\n";
		}
		else {
		    $strOut .= "\$" . $this->getObject() . "->" . 
		        $this->getMethodCall() . ";\n\n";
		}
		
		return $strOut;
	}
    
	public static function clearClassCall() {
	    self::$classCall = array();
	}
	
}