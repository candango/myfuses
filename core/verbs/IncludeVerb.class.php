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

require_once "myfuses/core/verbs/ParameterizedVerb.class.php";

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
class IncludeVerb extends ParameterizedVerb {
    
    /**
     * Verb file
     *
     * @var string
     */
    private $file;
    
    /**
     * The circuit name
     * 
     * @var string
     */
    private $circuitName = "";
    
    /**
     * The include content variable
     * 
     * @var string
     */
    private $contentVariable;
    
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
    
    /**
     * Return the circuit name
     *
     * @return string
     */
    public function getCircuitName() {
        return $this->circuitName;
    }
    
    /**
     * Set the circuit circuitName
     *
     * @param string $circuitName
     */
    public function setCircuitName( $circuitName ) {
        $this->circuitName = $circuitName;
    }
    
    /**
     * Return the content variable
     *
     * @return string
     */
    public function getContentVariable() {
        return $this->contentVariable;
    }
    
    /**
     * Set the content variable
     *
     * @param string $contentVariable
     */
    public function setContentVariable( $contentVariable ) {
        $this->contentVariable = $contentVariable;
    }
    
    public function getData() {
        $data = parent::getData();
        $data[ "attributes" ][ "file" ] = $this->getFile();
        
        if( !is_null( $this->getContentVariable() ) ) {
            $data[ "attributes" ][ "contentvariable" ] = 
                $this->getContentVariable();
        }
        
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );

        foreach($data[ 'attributes' ] as $attributeName => $attribute) {
            switch (strtolower($attributeName)) {
                case "circuit":
                    $this->setCircuitName($attribute);
                    break;
                case "contentvariable":
                case "variable":
                    $this->setContentVariable($attribute);
                    break;
                case "file":
                case "template":
                    $file = $attribute;
                    if(!MyFusesFileHandler::hasExtension($file, "php")) {
                        $file .= ".php";
                    }
                    $this->setFile($file);
                    break;
            }
        }
    }
    
	/**
     * Return the real parsed code
     *
     * @return string
     */
    public function getRealParsedCode( $commented, $identLevel ) {
        $appName = $this->getAction()->getCircuit()->
            getApplication()->getName();
            
        if( $this->getCircuitName() != "" ) {
            $circuitName = $this->getCircuitName();    
        }
        else {
            $circuitName = $this->getAction()->getCircuit()->getName();
        }
        
        
        $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();
        
        $fileCall = $controllerClass . "::getInstance()->getApplication( \"" . 
            $appName . "\" )->getCircuit( \"" . 
            $circuitName . "\" )->getCompletePath()";
        
        $strOut = str_repeat( "\t", $identLevel );
        
        $strOut .= $this->getIncludeFileString( $fileCall . "." . 
            " DIRECTORY_SEPARATOR . \"" . $this->getFile() . "\"", 
            $this->getContentVariable() );
        
        return $strOut;
    }

}