<?php
/**
 * SetVerb  - SetVerb.class.php
 * 
 * This verb create or set one global variable in process exection.
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
 * SetVerb  - SetVerb.class.php
 * 
 * This verb create or set one global variable in process exection.
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
class SetVerb extends AbstractVerb {
    
    private $variableName;
    
    private $value;
    
    public function getVariableName() {
        return $this->variableName;
    }
    
    public function setVariableName( $variableName ) {
        $this->variableName = $variableName;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function setValue( $value ) {
        $this->value = $value;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        $this->setVariableName( $data[ "attributes" ][ "name" ] );
        $this->setValue( $data[ "attributes" ][ "value" ] );
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        $strOut = parent::getParsedCode( $commented, $identLevel );
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "\$" . $this->getVariableName() . " = \"" . 
            $this->getValue() . "\";\n\n";
        return $strOut; 
    }

}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */