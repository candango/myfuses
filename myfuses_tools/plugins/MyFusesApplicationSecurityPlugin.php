<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

use Candango\MyFuses\Controller;
use Candango\MyFuses\Core\AbstractPlugin;

/**
 * MyFusesApplicationSecurityPlugin  - MyFusesApplicationSecurityPlugin.php
 *
 * Implementation of a MyFuses abstract plugin that allows access to the
 * MyFuses tools application based on the password defined on the host app's
 * myfuses.xml password parameter.
 *
 * @category   myfuses_tools
 * @package    myfuses.myfuses_tools.plugins
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      26e7a7c22cba04e6b544efefe4865c905074780a
 */
class MyFusesApplicationSecurityPlugin extends AbstractPlugin
{
    private static $message;

    const SESSION_INDEX = "MYFUSES_APPLICATION_SESSION_PASSWORD";

    public function run()
    {
        session_start();

        $request = Controller::getInstance()->getRequest();
        
        if ($request->getFuseActionName() == "tools.logout") {
            $this->logout();
            $this->goToLogin();
        }

        if ($request->getFuseActionName() != "tools.login") {
            if (!$this->isLogged()) {
                $this->goToLogin();
            }    
        } else {
            if($this->isLogged()) {
                $this->goToStart();
            }
        }

        if (!$this->isLogged()) {
            $this->authorize();
            if ($this->isAuthorized()) {
                $this->goToStart();
            }
            if (!is_null($this->getPostPassword())) {
                self::setMessage("Wrong Password");
            }
        }
    }

    private function authorize()
    {
        if (isset($_POST['myfusesLogin'])) {
            if (!is_null($this->getPostPassword())) {
                if ($this->getPostPassword() ==
                    $this->getApplicationPassword()) {
                    $this->setSessionPassword($this->getPostPassword());
                }
            }
        }
    }

    private function isAuthorized()
    {
        if (isset($_POST['myfusesLogin' ])) {
            if (!is_null($this->getPostPassword())) {
                if ($this->getPostPassword() ==
                    $this->getApplicationPassword()) {
                    return true;
                }
            }
        }
        return false;
    }

    private function setSessionPassword($password)
    {
        $_SESSION[self::SESSION_INDEX] = md5($password);
    }

    private function getApplicationPassword()
    {
        $password = Controller::getInstance()->getApplication()->getPassword();
        return md5($password);
    }

    private function getPostPassword()
    {
        if (isset($_POST['myfusesLogin'])) {
            return md5($_POST['myfusesLogin']);
        }
        return null;
    }

    private function logout(){
        unset($_SESSION[self::SESSION_INDEX]);
    }

    public static function isLogged()
    {
        if (isset($_SESSION[self::SESSION_INDEX ])) {
            return true;
        }
        return false;
    }

    private function goToLogin()
    {
        $request = Controller::getInstance()->getRequest();

        if($this->getApplication()->isDefault()) {
            $request->getAction()->addXFA("goToLogin", "tools.login");
        } else {
            $request->getAction()->addXFA("goToLogin",
                $this->getApplication()->getName() . ".tools.login");
        }

        Controller::sendToUrl(xfa("goToLogin", true, false));
    }
    
    private function goToStart()
    {
        $request = Controller::getInstance()->getRequest();

        if ($this->getApplication()->isDefault()) {
            $request->getAction()->addXFA( "goToStart", 
            $this->getApplication()->getDefaultFuseaction() );    
        } else {
            $request->getAction()->addXFA("goToStart",
                $this->getApplication()->getName() . "." . 
                $this->getApplication()->getDefaultFuseaction());
        }
        Controller::sendToUrl(xfa("goToStart", true, false));
    }

    public static function getMessage()
    {
        return self::$message;
    }

    public static function setMessage($message)
    {
        self::$message = $message;
    }
}
