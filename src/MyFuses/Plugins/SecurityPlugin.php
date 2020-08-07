<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Plugins;


use Candango\MyFuses\Controller;
use Candango\MyFuses\Core\Plugin;
use Candango\MyFuses\Process\DebugEvent;

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
            $pluginReference = "Candango\\MyFuses\\Plugins\\Sercurity";
            switch($this->getPhase())
            {
                case Plugin::PRE_PROCESS_PHASE:
                    Controller::getInstance()->getDebugger()->registerEvent(
                        new DebugEvent($pluginReference,
                            "Reached Pre Process Phase. Running pre process" .
                            "plugin action."));
                    $this->runPreProcess();
                    break;
                case Plugin::PRE_FUSEACTION_PHASE:
                    Controller::getInstance()->getDebugger()->registerEvent(
                        new DebugEvent($pluginReference,
                            "Reached Pre Fuseaction Phase. Running pre process" .
                            "plugin action."));
                    $this->runPreFuseaction();
                    break;
            }
        }
}
