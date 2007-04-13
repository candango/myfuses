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

	private $object;

	private $method;
	
	private $arguments;

	private $variable;
	
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
		$data[ "name" ] = "invoke";
		$data[ "attributes" ][ "object" ] = $this->getObject();
		$data[ "attributes" ][ "method" ] = $this->getMethod();
		if( !is_null( $this->getArguments() ) ) {
			$data[ "attributes" ][ "argument" ] = $this->getArguments();
		}
		if( !is_null( $this->getVariable() ) ) {
			$data[ "attributes" ][ "variable" ] = $this->getVariable();
		}
		return $data;
	}

	public function setData( $data ) {
		$this->setObject( $data[ "attributes" ][ "object" ] );

		$this->setMethod( $data[ "attributes" ][ "method" ] );

		if( isset( $data[ "children" ] ) ) {
			$this->setArguments( $data[ "children" ] );
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
		//Make identation
		$strOut .= str_repeat( "\t", $identLevel );
		//Verify if it has a variable (Method returns a value)
		if ( !is_null( $this->getVariable() ) ) {
			$strOut .= "\$" . $this->getVariable() . " = ";
		}
		
		//Initiate method call
		$strOut .= "\$" . $this->getObject() . "->" . $this->getMethod() . "(";
		//Verify arguments - Fusebox 5 (strictMode set to true)
		if ( !is_null( $this->getArguments() ) )
			$strOut .= $this->getArguments();
	    //Close method
		$strOut .= ");\n\n";
		
		return $strOut;
	}

	/**
	 * Return the parsed comments
	 *
	 * @return string
	 */
	public function getComments( $identLevel ) {
		$strOut = parent::getComments( $identLevel );
		
		if( !is_null( $this->getVariable() ) ) {
			$strInst = "variable=\"" . $this->getVariable() . "\"";
		}
		$strInst .= " object=\"" . $this->getObject() . "\"";
		$strInst .= " method=\"" . $this->getMethod() . "\"";
		if( !is_null( $this->getArguments() ) ) {
			$strInst .= " arguments=\"" . $this->getArguments() . "\"";
		}
		
		$strOut = str_replace( "__COMMENT__",
		"MyFuses:request:action:invoke " . $strInst, $strOut );
	  
		return $strOut;
	}

}