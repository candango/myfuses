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

require_once MYFUSES_ROOT_PATH . "core/ICacheable.php";
require_once MYFUSES_ROOT_PATH . "core/IParseable.php";
require_once MYFUSES_ROOT_PATH . "core/CircuitAction.php";

/**
 * Verb  - Verb.php
 *
 * This is MyFuses Verb interface. This interface refers how one verb
 * class can be implemented.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f58e20e297c17545ad8f76fed4a1f23c35f2e445
 */
interface Verb extends ICacheable, IParseable
{
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
    public function setAction(CircuitAction $action);

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
    public function setName($name);

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
    public function setParent(Verb $verb);

    /**
     * Fill instance data
     *
     * @param array $data
     * @return Verb
     */
    public function setData($data);

    public function getData();

    public function getErrorParams();
}
