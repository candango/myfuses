<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Security;

use Candango\MyFuses\Controller;
use Candango\MyFuses\Process\DebugEvent;

/**
 * MyFusesBasicSecurityManager - MyFusesBasicSecurityManager.php
 *
 * MyFuses Basic Security Manager
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Goncalves Garcia <piraz at candango.org>
 * @since      7117f031f18f8fa583d344b573d3fbf574e42652
 */
class BasicSecurityManager extends AbstractSecurityManager
{
    public function createCredential()
    {
        Controller::getInstance()->getDebugger()->registerEvent(
            new DebugEvent("MyFusesSecurityManager",
                "MyFuses Security Manager creating credential."));
        if (!isset($_SESSION['MYFUSES_SECURITY_CREDENTIAL'])) {
            Controller::getInstance()->getDebugger()->registerEvent(
                new DebugEvent("MyfusesSecurityManager",
                    "No credential found. Creating a new one."));

            $credential = new BasicCredential();

            $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] = $credential->getData();
        } else {
            Controller::getInstance()->getDebugger()->registerEvent(
                new DebugEvent("MyfusesSecurityManager",
                    "Credential found. Checking if isnt expired."));

            $credential = new BasicCredential();
            $credential->setData($_SESSION['MYFUSES_SECURITY_CREDENTIAL']);

            if ($credential->isExpired()) {
                Controller::getInstance()->getDebugger()->registerEvent(
                    new DebugEvent("MyfusesSecurityManager",
                        "Credential expired. Creating a new one."));
                $credential = new BasicCredential();

                $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] =
                    $credential->getData();
            } else {
                Controller::getInstance()->getDebugger()->registerEvent(
                    new DebugEvent("MyfusesSecurityManager",
                        "Credential not expired. Increasing navigation " .
                        "time."));
                $credential->increaseNavigationTime();
                $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] =
                    $credential->getData();
            }
        }
    }

    /**
     * Return registered credential
     *
     * @return Credential
     */
    public function getCredential()
    {
        if(isset($_SESSION['MYFUSES_SECURITY_CREDENTIAL'])) {
            $credential = new BasicCredential();
            $credential->setData($_SESSION['MYFUSES_SECURITY_CREDENTIAL']);
            return $credential;
    	}
        return null;
    }

    public function persistCredential(Credential $credential)
    {
        $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] = $credential->getData();
    }
}
