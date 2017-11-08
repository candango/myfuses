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
 * MyFusesApplicationBuilderListener - MyFusesApplicationBuilderListener.php
 *
 * Interface defining an application builder listener
 *
 * @category   controller
 * @package    myfuses.engine
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      80f65515c7288e149b488c889009acb65002d371
 */
interface MyFusesApplicationBuilderListener
{
    public function applicationBuildPerformed(Application $application,
        &$data);
}
