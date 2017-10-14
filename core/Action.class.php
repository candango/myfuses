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

require_once "myfuses/core/IParseable.class.php";

/**
 * AbstractAction  - AbstractAction.php
 * 
 * This is a functional abstract MyFuses Action implementation. One concrete
 * Action must extends this class.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f58e20e297c17545ad8f76fed4a1f23c35f2e445
 */
interface Action extends ICacheable, IParseable
{
    /**
     * Return the action name
     *
     * @return string
     */
    public function getName();

    /**
     * Set the action name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Do some action. Concrete action will implement this method.
     */
    public function doAction();

    /**
     * Set custom attribute
     *
     * @param string $namespace
     * @param string $name
     * @param mixed $value
     */
    public function setCustomAttribute($namespace, $name, $value);

    /**
     * Get some especific custom attribute in this action
     *
     * @param string $namespace
     * @param string $name
     * @return mixed $value
     */
    public function getCustomAttribute($namespace, $name);

    /**
     * Return all custom attribute by a given namespace
     *
     * @param string $namespace
     * @return array
     */
    public function getCustomAttributes($namespace);
}
