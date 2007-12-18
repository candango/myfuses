<?php
/**
 * InstantiateVerb - InstantiateVerb.class.php
 * 
 * This verb instantiate one object by a given class or wsdl.
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
 * @category   verb
 * @package    myfuses.core.verbs
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
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
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 125
 */
class InstantiateVerb extends AbstractVerb {
    
    /**
     * Name of the class that the verb will instantiate.
     * 
     * @var string
     */
    private $class;
    
    /**
     * Nome of the instance that the verb will instantiate.
     * 
     * @var string
     */
    private $object;
    
    /**
     * Wsdl path.<br>
     * When developer inform the wsld, class will be ignored, and the verb will
     * instantiate a new SoapClient.
     * 
     * @var string
     */
    private $wsdl;
    
    /**
     * Arguments used at object construction
     * 
     * @var array
     */
    private $arguments;
    
    /**
     * Returnt the verb class
     * 
     * @return string
     */
    public function getClass() {
        return $this->class;
    }
    
    /**
     * Set the verb class
     * 
     * @param string $class
     */
    public function setClass( $class ) {
        $this->class = $class;
    }
    
    public function getObject() {
        return $this->object;
    }

    public function setObject( $object ) {
        $this->object = $object;
    }
    
    public function getWsdl() {
        return $this->wsdl;
    }
    
    public function setWsdl( $wsdl ) {
        $this->wsdl = $wsdl;
    }
    
    public function getArguments() {
        return $this->arguments;
    }

    public function setArguments( $arguments ) {
        $this->arguments = $arguments;
    }
    
    public function getData() {
        $data = parent::getData();
        $data[ "name" ] = "instantiate";
        
        if( !is_null( $this->getClass() ) ) {
            $data[ "attributes" ][ "class" ] = $this->getClass();    
        }
        
        $data[ "attributes" ][ "object" ] = $this->getObject();
        
        if( !is_null( $this->getWsdl() ) ) {
            $data[ "attributes" ][ "wsdl" ] = $this->getWsdl();
        }
        
        if( !is_null( $this->getArguments() ) ) {
            $data[ "attributes" ][ "arguments" ] = $this->getArguments();
        }
        return $data;
    }

    public function setData( $data ) {
        
        parent::setData( $data );
        
        if( isset( $data[ "attributes" ][ "wsdl" ] ) ) {
            $this->setWsdl( $data[ "attributes" ][ "wsdl" ] );
        }
        
        if( isset( $data[ "attributes" ][ "class" ] ) ) {
            $this->setClass( $data[ "attributes" ][ "class" ] );
        }
        
        $this->setObject( $data[ "attributes" ][ "object" ] );
        
        if( isset( $data[ "attributes" ][ "arguments" ] ) ) {
            $this->setArguments( $data[ "attributes" ][ "arguments" ] );
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
        
	    $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();
	        
	    $fileCall = $controllerClass . "::getApplication( \"" . $appName .
	        "\" )->getClass( \"" . $this->getClass() . 
	        "\" )->getCompletePath()";

	    $strOut = parent::getParsedCode( $commented, $identLevel );
	    $strOut .= str_repeat( "\t", $identLevel );
	    if( is_null( $this->getWsdl() ) ) {
	        $strOut .= "if ( file_exists( " . $fileCall . " ) ) {\n";
		    $strOut .= str_repeat( "\t", $identLevel + 1 );
		    $strOut .= "require_once( " . $fileCall . " );\n";
		    $strOut .= str_repeat( "\t", $identLevel );
		    $strOut .= "}\n";
		    $strOut .= str_repeat( "\t", $identLevel );
		    $strOut .= "\$" . $this->getObject() . " = new " . 
		        $this->getClass() . "( " . $this->getArguments() . " );\n\n";    
	    }
	    else {
	        $strOut .= "\$" . $this->getObject() . " = new SoapClient" . 
		        "( \"" . $this->getWsdl() . "\" );\n\n";
	    }
	    
	    return $strOut;
	}

}