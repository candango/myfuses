<?php
/**
 * IContextRegisterable  - IContextRegisterable.class.php
 * 
 * Interface that defines any object that can be registred in context.
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
 * @package    myfuses.context
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: IContextRegisterable.class.php 7 2006-08-10 14:03:05Z piraz $
 * @since      Revision 3
 */
 
/**
 * IContextRegisterable  - IContextRegisterable.class.php
 * 
 * Interface that defines any object that can be registred in context.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.context
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 7 $
 * @since      Revision 3
 * @abstract
 */
interface IContextRegisterable {
    
    /**
     * Returns the xml that represents the registration into the context
     * 
     * @param string identation
     * @return string The registration XML
     * @access public
     */
	public function getContextXML( $identation = "" );
    
    /**
     * Sets all entity attibutes based in a given SimpleXMLElement
     * 
     * @param SimpleXMLElement element
     * @access public
     */
	public function setAttributesFromContext( SimpleXMLElement $element );

}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */