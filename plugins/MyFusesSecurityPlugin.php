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

require_once "myfuses/plugins/MyFusesAbstractSecurityPlugin.php";

/**
 * MyFusesSecurityPlugin  - MyFusesSecurityPlugin.php
 * Basic security plugin implementation to be used on the myfuses.xml.
 * Mapping this plugin will allow the app benefit from all features and
 * workflows defined by MyFusesAbstractSecurityPlugin.
 *
 * @category   controller
 * @package    myfuses.plugins
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      a10e2e12abf0f387df778bf633b4dfa4efd37515
 */
class MyFusesSecurityPlugin extends MyFusesAbstractSecurityPlugin
{
        public function run()
        {
            $this->checkSession();

            switch($this->getPhase())
            {
                case Plugin::PRE_PROCESS_PHASE:
                    $this->runPreProcess();
                    break;
                case Plugin::PRE_FUSEACTION_PHASE:
                    $this->runPreFuseaction();
                    break;
            }
        }
}
