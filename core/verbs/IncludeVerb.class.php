<?php
/**
 * IncludeVerb  - IncludeVerb.class.php
 * 
 * This verb includes one file in processes exection.
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
 * IncludeVerb  - IncludeVerb.class.php
 * 
 * This verb includes one file in processes exection.
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
require_once "myfuses/core/verbs/ParameterizedVerb.class.php";

class IncludeVerb extends ParameterizedVerb {
    
    /**
     * Verb file
     *
     * @var string
     */
    private $file;
    
    /**
     * Return the verb file
     *
     * @return string
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Set the verb file
     *
     * @param string $file
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
    public function getData() {
        $data = parent::getData();
        $data[ "attributes" ][ "file" ] = $this->getFile();
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        $file = "";
        if( isset( $data[ "attributes" ][ "file" ] ) ) {
            $file = $data[ "attributes" ][ "file" ];
        }
        
        if( isset( $data[ "attributes" ][ "template" ] ) ) {
            $file = $data[ "attributes" ][ "template" ];
        }
        
        $this->setFile( $file );
    }
    
	/**
     * Return the real parsed code
     *
     * @return string
     */
    public function getRealParsedCode( $commented, $identLevel ) {
        $appName = $this->getAction()->getCircuit()->
            getApplication()->getName();
        $circuitName = $this->getAction()->getCircuit()->getName();
        
        $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();
        
        $fileCall = $controllerClass . "::getApplication( \"" . $appName . 
            "\" )->getCircuit( \"" . $circuitName . "\" )->getCompletePath()";
        
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "if ( file_exists( " . $fileCall . " . \"" . 
            $this->getFile() . "\" ) ) {\n";
        $strOut .= str_repeat( "\t", $identLevel + 1 );
        $strOut .= "include( " . $fileCall . " . \"" . 
            $this->getFile() . "\" );\n";
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "}\n\n";
        return $strOut;
    }

}