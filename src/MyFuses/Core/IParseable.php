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
 * IParseable  - IParseable.php
 *
 * This interface defines all parseable classes.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f58e20e297c17545ad8f76fed4a1f23c35f2e445
 */
interface IParseable
{
    /**
     * Return the parsed code
     *
     * @param boolean $comented
     * @param int $identLevel
     * @return string
     */
    public function getParsedCode($comented, $identLevel);

    /**
     * Return the parsed comments
     *
     * @param int $identLevel
     * @return string
     */
    public function getComments($identLevel);

}
