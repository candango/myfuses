<?php
/**
 * Parameterized Verb  - ParameterizedVerb.class.php
 * 
 * This is an abstract verb that handlers parameters.
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
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: RelocateVerb.class.php 245 2008-02-13 18:02:49Z piraz $
 */

/**
 * Parameterized Verb  - ParameterizedVerb.class.php
 * 
 * This is an abstract verb that handlers parameters.
 * 
 * PHP version 5
 *
 * @category   verbs
 * @package    myfuses.core.verbs
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 245 $
 * @since      Revision 245
 */
abstract class ParameterizedVerb extends AbstractVerb {
    
    /**
     * Verb parameters
     *
     * @var array
     */
    private $parameters = array();
    
    /**
     * Return verb parameters
     *
     * @return array
     */
    public function getParameters(){
        return $this->parameters;
    }
    
    /**
     * Set verb parameters
     *
     * @param string $name
     * @param string $value
     */
    public function addParameter( $name, $value ) {
        $this->parameters[ $name ] = $value;
    }
    
    public function getData() {
        $data = parent::getData();
        
        if( !is_null( $this->getParameters() ) ) {
            foreach( $this->getParameters() as $name => $value ) {
                $child = array();
                $child[ 'name' ] = 'parameter';
                $child[ 'namespace' ] = 'myfuses';
                $child[ 'attributes' ][ 'name' ] = $name;
                $child[ 'attributes' ][ 'value' ] = $value;
                $data[ 'children' ][] = $child; 
            }
        }
        
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        if( isset( $data[ "children" ] ) ) {
            foreach( $data[ "children" ] as $child ) {
                $name = null;
                $value = null;
                if( $child[ 'name' ] == 'parameter' ) {
                    if( isset( $child[ 'attributes' ][ 'name' ] ) ) {
                        $name = $child[ 'attributes' ][ 'name' ];
                    }
                    else  {
                        $params = $this->getErrorParams();
                        $params[ 'verbName' ] = "parameter";
                        $params[ 'attrName' ] = "name";
                        throw new MyFusesVerbException( $params, 
                            MyFusesVerbException::MISSING_REQUIRED_ATTRIBUTE );
                    }
                    if( isset( $child[ 'attributes' ][ 'value' ] ) ) {
                        $value = $child[ 'attributes' ][ 'value' ];
                    }
                }
                $this->addParameter( $name, $value );
            }
        }
        
    }
    
    /**
     * Return the real parsed code
     *
     * @return string
     */
    public abstract function getRealParsedCode( $commented, $identLevel );
    

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        $strOut = parent::getParsedCode( $commented, $identLevel );
        
        $id = uniqid();
        
        foreach( $this->getParameters() as $name => $value ) {
            $strOut .= str_repeat( "\t", $identLevel );
            $strOut .=  "MyFusesCodeHandler::setParameter( \"" . $name . "\", \"" . $value . "\" );\n";
        }
        
        
        $strOut .= $this->getRealParsedCode( $commented, $identLevel );
        
        foreach( $this->getParameters() as  $name => $value ) {
            $strOut .= str_repeat( "\t", $identLevel );
            $strOut .=  "MyFusesCodeHandler::restoreParameter( \"" . $name . "\" );\n";
        }
        $strOut .=  "\n";
        
        return $strOut;
    }
    
}