<?php
/**
 * FuseAction  - FuseAction.class.php
 * 
 * FuseAction is the real action executed by one Circuit. When you acess some
 * Circuit.Action MyFuses will resolve some FuseAction in fact. 
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

require_once MYFUSES_ROOT_PATH . "core/AbstractAction.class.php";
require_once MYFUSES_ROOT_PATH . "core/CircuitAction.class.php";

/**
 * FuseAction  - FuseAction.class.php
 * 
 * FuseAction is the real action executed by one Circuit. When you acess some
 * Circuit.Action MyFuses will resolve some FuseAction in fact. 
 * 
 * This is a functional abstract MyFuses Action implementation. One concrete
 * Action must extends this class.
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
class FuseAction extends AbstractAction implements CircuitAction {
    
    /**
     * The circuit that this fuseaction belongs
     * 
     * @var Circuit
     */
    private $circuit;
    
    /**
     * Verbs registered in this fuseaction
     * 
     * @var array An array of verbs
     */
    private $verbs = array();
    
    /**
     * TODO Check the possibility to create one class that represent the XFA
     * XFA's registered in this fuseaction
     * 
     * @var array An array of strings
     */
    private $xfas = array();
    
    /**
     * Flag that points if circuit is default in fuseaction
     *
     * @var boolean
     */
    private $default = false;
    
    /**
     * Default constructor. Need one circuit to register in the fuseaction.
     * 
     * @param $circuit
     */
    public function __construct( Circuit $circuit ) {
        $this->setCircuit( $circuit );
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#getCompleteName()
     */
    public function getCompleteName() {
        return $this->getCircuit()->getName() . "." . $this->getName();
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#isDefault()
     */
    public function isDefault() {
        return $this->default;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#setDefault()
     */
    public function setDefault( $default ) {
        if( is_null( $default ) ) {
            $this->default = false;
        }
        else {
            if( is_bool( $this->default ) ) {
                $this->default = $default;
            }
            else {
                if( strtolower( $this->default ) == 'true' ) {
                    $this->default = true;
                }
                else {
                    $this->default = false;
                }
            }
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#getCircuit()
     */
    public function &getCircuit() {
        $circuit = $this->circuit;
        
        MyFusesLifecycle::checkCircuit( $circuit );
        
        return $this->circtuit;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#setCircuit()
     */
    public function setCircuit( Circuit &$circuit ) {
        $this->circuit = &$circuit;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#addVerb()
     */
    public function addVerb( Verb $verb ) {
        $this->verbs[] = $verb;
        $verb->setAction( $this );
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#getVerbs()
     */
    public function &getVerbs() {
        return $this->verbs;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#getXfas()
     */
    public function getXfas() {
        return $this->xfas;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#addXFA()
     */
    public function addXFA( $name, $value ) {
        $this->xfas[ $name ] = $value;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#getXfa()
     */
    public function getXfa( $name ) {
        return $this->xfas[ $name ];
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#wasCalledByDo()
     */
    public function wasCalledByDo() {
        return $this->calledByDo;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#setCalledByDo()
     */
    public function setCalledByDo( $calledByDo ) {
        $this->calledByDo = $calledByDo;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitAction#getErrorParams()
     */
    public function getErrorParams() {
        $params = $this->getCircuit()->getErrorParams();
        // FIXME CircuitAction must have a name
        $params[ 'actionName' ] = $this->getName();
        return $params;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Action#doAction()
     */
    public function doAction() {
    
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */