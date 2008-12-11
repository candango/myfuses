<?php
/**
 * XfaVerb  - XfaVerb.class.php
 *
 * This is the eXit Fuse Action.
 * 
 * XFA is the verb used to provide exit FuseActions to current Request 
 * FuseAction. 
 * The XFA is used in like this: 
 * <xfa name="viewCart" value="cart.displayCartContents" />
 * and will add in current FuseAction like this:
 * $action->addXfa( "viewCart", "cart.displayCartContents" );
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
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 *
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: ResponseVerb.class.php,v 1.1 2008/12/03 02:35:42 86559109100 Exp $
 */

/**
 * XfaVerb  - XfaVerb.class.php
 *
 * This is the eXit Fuse Action.
 *
 * XFA is the verb used to provide exit FuseActions to current Request
 * FuseAction.
 * The XFA is used in like this:
 * <xfa name="viewCart" value="cart.displayCartContents" />
 * and will add in current FuseAction like this:
 * $action->addXfa( "viewCart", "cart.displayCartContents" );
 *
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 1.1 $
 * @since      Revision 125
 */
class ResponseVerb extends AbstractVerb {
    
    /**
     * XFA value
     * 
     * @var string
     */
    private $type;
    
    /**
     * Return the XFA Value
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * Set the XFA Value
     *
     * @param string $value
     */
    public function setType( $type ) {
        $this->type = $type;
    }
     
    /**
     * Return the XFA data array
     * 
     * @return array
     */
    public function getData() {
        
        $data = parent::getData();
        
        $data[ "attributes" ][ "type" ] = $this->getType();
        
        return $data;
    }
    
    /**
     * Recieve the XFA data array and put and set all properties 
     *
     * @param array $data
     */
    public function setData( $data ) {
        
        parent::setData( $data );
        
        $this->setType( $data[ "attributes" ][ "type" ] );
        
        MyFuses::getInstance()->setResponseType( $this->getType() );
        
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        
        return "";
    }

}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */