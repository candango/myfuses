<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Engine;

/**
 * ApplicationBuilderListener - ApplicationBuilderListener
 * Interface defining an application builder listener
 *
 * @category   controller
 * @package    myfuses.Engine
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      80f65515c7288e149b488c889009acb65002d371
 */
interface ApplicationBuilderListener
{
    public function applicationBuildPerformed(Application $application,
        &$data);
}
