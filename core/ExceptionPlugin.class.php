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

/**
 * ExceptionPlugin  - AbstractPlugin.php
 *
 * This is a functional abstract MyFuses plugin implementation. One concrete
 * Plugin must extends this class.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      87f78432b64b34c08c9339ca366ad81f3cb94d8c
 */
interface ExceptionPlugin extends Plugin
{
    /**
     * Implement here the logic to handle a exception
     *
     * @param MyFusesRuntimeExeption $exception
     */
    public function handle(MyFusesRuntimeExeption $exception);

}
