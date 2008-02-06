<?php
/**
 * RelocateVerb  - RelocateVerb.class.php
 * 
 * This is verb is used when the developer want to redirect the browser to 
 * another url or xfa.
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
 * @category   verbs
 * @package    myfuses.core.verbs
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

/**
 * RelocateVerb  - RelocateVerb.class.php
 * 
 * This is verb is used when the developer want to redirect the browser to 
 * another url or xfa.
 * 
 * PHP version 5
 *
 * @category   verbs
 * @package    myfuses.core.verbs
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class RelocateVerb extends AbstractVerb {
    
    
    private $url;
    
    private $xfa;
    
    public function getUrl() {
        return $this->url;
    }
    
    public function setUrl( $url ) {
        $this->url = $url;
    }
    
    public function getXfa() {
        return $this->xfa;
    }
    
    public function setXfa( $xfa ) {
        $this->xfa = $xfa;
    }
    
    public function getData() {
        $data = parent::getData();
        if( !is_null( $this->getUrl() ) ) {
            $data[ "attributes" ][ "url" ] = $this->getUrl();
        }
        
        if( !is_null( $this->getXfa() ) ) {
            $data[ "attributes" ][ "xfa" ] = $this->getXfa();
        }
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        
        if( isset( $data[ "attributes" ][ "url" ] ) ) {
            $this->setUrl( $data[ "attributes" ][ "url" ] );    
        }
        
        if( isset( $data[ "attributes" ][ "xfa" ] ) ) {
            $this->setXfa( $data[ "attributes" ][ "xfa" ] );    
        }
    }
    

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        $strOut = parent::getParsedCode( $commented, $identLevel );
        
        $strOut .= str_repeat( "\t", $identLevel );
        
        $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();
        
	    $url = ( is_null( $this->getUrl() ) ? $controllerClass . 
	        "::getMySelfXfa( \"" . $this->getXfa() . "\" )" : "\"" . 
	        $this->getUrl() . "\"" );    
	        
	    $strOut .=  $controllerClass . "::sendToUrl( " . $url . " );\n\n";
        
        return $strOut;
    }

}