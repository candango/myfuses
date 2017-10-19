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

require_once MyFuses::MYFUSES_ROOT_PATH . "core/ExceptionPlugin.class.php";  

/**
 * AbstractPlugin  - AbstractPlugin.php
 *
 * This is a functional abstract MyFuses plugin implementation. One concrete
 * Plugin must extends this class.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      77f94d464a692c5b2c7d412460fd37f406a15ebb
 */
abstract class AbstractExceptionPlugin extends AbstractPlugin implements
    ExceptionPlugin
{
    public function run() {
    }
}
