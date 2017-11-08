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

namespace Candango\MyFuses\Engine;

/**
 * ApplicationLoaderListener - ApplicationLoaderListener
 * Interface defining an application loader listener.
 *
 * @category   controller
 * @package    myfuses.Engine
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      5fd8d0153c67b1d5ceb21ff9117c98ebb9deda6b
 */
interface ApplicationLoaderListener
{
    public function loadInitialized(BasicApplication $application);

    public function loadPerformed(Loader $loader, &$data);
}
