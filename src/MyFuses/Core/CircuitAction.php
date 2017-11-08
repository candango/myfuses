<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core;

/**
 * AbstractAction  - AbstractAction.php
 * 
 * This Action interface defines some circuit methods. This is the base interface
 * for Fuseacion.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f58e20e297c17545ad8f76fed4a1f23c35f2e445
 */
interface CircuitAction extends Action
{
    /**
     * Return the action circuit
     * 
     * @return Circuit
     */
    public function &getCircuit();

    /**
     * Set the action circuit
     *
     * @param Circuit $circuit
     */
    public function setCircuit(Circuit &$circuit);

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
    public function setDefault($default);

    /**
     * Return the pemissions parameter
     * 
     * @return string
     */
    public function getPermissions();

    /**
     * Set the circuit action permissions parameter
     * 
     * @param $permissions
     */
    public function setPermissions($permissions);

    /**
     * Return security mode
     *
     * @return string
     */
    public function getSecurity();

    /**
     * Set security mode
     *
     * @param $security
     */
    public function setSecurity($security);

    /**
     * Enter description here...
     *
     * @param Verb $verb
     */
    public function addVerb(Verb $verb);

    /**
     * Enter description here...
     *
     * @param string $name
     */
    public function getVerb($name);

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
    public function addXFA($name, $value);

    /**
     * 
     */
    public function wasCalledByDo();

    /**
     * 
     */
    public function setCalledByDo($calledByDo);

    public function getErrorParams();
}
