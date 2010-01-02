<?php
/**
 * CircuitAction  - CircuitAction.class.php
 * 
 * This Action interface defines some circuit methods. This is the base 
 * interface for the Fuseacion class.
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
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2010.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

require_once "myfuses/core/Action.class.php";
require_once "myfuses/core/Circuit.class.php";
require_once "myfuses/core/Verb.class.php";

/**
 * CircuitAction  - CircuitAction.class.php
 * 
 * This Action interface defines some circuit methods. This is the base 
 * interface for the Fuseacion class.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 25
 */
interface CircuitAction extends Action {
    
    /**
     * Return the action circtui
     * 
     * @return Circuit
     */
    public function &getCircuit();
    
    /**
     * Set the action cicuit
     *
     * @param Circuit $circuit
     */
    public function setCircuit( Circuit &$circuit );
    
    /**
     * Return Circuit Action complete name.<br>
     * Complete name is circuit name plus dot plus action name.
     *
     * return string
     */
    public function getCompleteName();
    
    /**
     * Return if the action is default in circuit
     *
     * @return boolean
     */
    public function isDefault();
    
    /**
     * Set default flag in action. This flag points if the action is default in
     * circuit.
     *
     * @param boolean $default
     */
    public function setDefault( $default );
    
    /**
     * Enter description here...
     *
     * @param Verb $verb
     */
    public function addVerb( Verb $verb );
    
    /**
     * Enter description here...
     *
     * @param string $name
     */
    public function getVerb( $name );
    
    /**
     * Enter description here...
     *
     */
    public function &getVerbs();
    
    /**
     * Enter description here...
     *
     */
    public function getXFAs();
    
    /**
     * Enter description here...
     *
     * @param string $name
     * @param string $value
     */
    public function addXFA( $name, $value );
    
    /**
     * 
     */
    public function wasCalledByDo();
    
    /**
     * 
     */
    public function setCalledByDo( $calledByDo );
    
    
    public function getErrorParams();
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */