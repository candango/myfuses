<?php
/**
 * Verb  - Verb.class.php
 * 
 * This is MyFuses Verb interface. This interface refers how one verb
 * class can be implemented.
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
 * @package    myfuses.core
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

require_once "myfuses/core/IParseable.class.php";
require_once "myfuses/core/CircuitAction.class.php";

/**
 * Verb  - Verb.class.php
 * 
 * This is MyFuses Verb interface. This interface refers how one verb
 * class can be implemented.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.cores
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 25
 */
interface Verb extends IParseable {
    
    /**
     * Return the verb Action
     *
     * @return Action
     */
    public function getAction();
    
    /**
     * Set the verb Action
     *
     * @param CircuitAction $action
     */
    public function setAction( CircuitAction $action );
    
    /**
     * Return the verb name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Set the verb name
     *
     * @param string $name
     */
    public function setName( $name );
    
    /**
     * Return the verb parent
     *	
     * @return Verb
     */
    public function getParent();
    
    /**
     * Set the verb parent
     *
     * @param Verb $verb
     */
    public function setParent( Verb $verb );
    
    /**
     * Fill instance data
     *
     * @param array $params
     * @return Verb
     */
    public function setData( $data );
    
    public function getData();
    
    public function getErrorParams();
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */