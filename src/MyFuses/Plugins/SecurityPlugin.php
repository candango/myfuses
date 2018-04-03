<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

require_once MYFUSES_ROOT_PATH . "plugins/MyFusesAbstractSecurityPlugin.php";

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
class SecurityPlugin extends AbstractSecurityPlugin
{
        public function run()
        {
            $this->checkSession();

            switch($this->getPhase())
            {
                case Plugin::PRE_PROCESS_PHASE:
                    MyFuses::getInstance()->getDebugger()->registerEvent(
                        new MyFusesDebugEvent("MyFusesSecurityPlugin",
                            "Reached Pre Process Phase. Running pre process" .
                            "plugin action."));
                    $this->runPreProcess();
                    break;
                case Plugin::PRE_FUSEACTION_PHASE:
                    MyFuses::getInstance()->getDebugger()->registerEvent(
                        new MyFusesDebugEvent("MyFusesSecurityPlugin",
                            "Reached Pre Fuseaction Phase. Running pre process" .
                            "plugin action."));
                    $this->runPreFuseaction();
                    break;
            }
        }
}
