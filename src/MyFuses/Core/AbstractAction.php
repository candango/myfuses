<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core;

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
abstract class AbstractAction implements Action
{
    /**
     * Action name
     *
     * @var string
     */
    private $name;

    /**
     * Custom attributes defined by develloper
     * 
     * @var array 
     */
    protected $customAttributes = array();

    /**
     * Return the action name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the action name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setCustomAttribute($namespace, $name, $value)
    {
        $this->customAttributes[$namespace][$name] = $value;
    }

    public function getCustomAttribute($namespace, $name)
    {
        if( isset($this->customAttributes[$namespace][$name])) {
            return $this->customAttributes[$namespace][$name];
        }
        return null;
    }

    public function getCustomAttributes($namespace)
    {
        return $this->customAttributes[$namespace];
    }
}
