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

require_once MYFUSES_ROOT_PATH . "util/security/" .
    "MyFusesAbstractSecurityManager.php";
require_once MYFUSES_ROOT_PATH . "util/data/MyFusesJsonUtil.php";

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
class MyFusesBasicSecurityManager extends MyFusesAbstractSecurityManager
{
    public function createCredential()
    {
        MyFuses::getInstance()->getDebugger()->registerEvent(
            new MyFusesDebugEvent("MyFusesSecurityManager",
                "MyFuses Security Manager creating credential."));
        if (!isset($_SESSION['MYFUSES_SECURITY_CREDENTIAL'])) {
            MyFuses::getInstance()->getDebugger()->registerEvent(
                new MyFusesDebugEvent("MyfusesSecurityManager",
                    "No credential found. Creating a new one."));

            $credential = new BasicCredential();

            $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] = $credential->getData();
        } else {
            MyFuses::getInstance()->getDebugger()->registerEvent(
                new MyFusesDebugEvent("MyfusesSecurityManager",
                    "Credential found. Checking if isnt expired."));

            $credential = new BasicCredential();
            $credential->setData($_SESSION['MYFUSES_SECURITY_CREDENTIAL']);

            if ($credential->isExpired()) {
                MyFuses::getInstance()->getDebugger()->registerEvent(
                    new MyFusesDebugEvent("MyfusesSecurityManager",
                        "Credential expired. Creating a new one."));
                $credential = new BasicCredential();

                $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] =
                    $credential->getData();
            } else {
                MyFuses::getInstance()->getDebugger()->registerEvent(
                    new MyFusesDebugEvent("MyfusesSecurityManager",
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
