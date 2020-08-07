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
use Candango\MyFuses\Core\AbstractPlugin;
use Candango\MyFuses\Security\AbstractSecurityManager;
use Candango\MyFuses\Security\SecurityManager;
use Candango\MyFuses\Process\DebugEvent;
use Candango\MyFuses\Util\FileHandler;

/**
 * MyFusesAbstractSecurityPlugin  - MyFusesAbstractSecurityPlugin.php
 * 
 * Plugin that controls all authentication and authorization workflows over an
 * application.
 *
 * @category   plugins
 * @package    myfuses.plugins
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      a10e2e12abf0f387df778bf633b4dfa4efd37515
 */
abstract class AbstractSecurityPlugin extends AbstractPlugin
{
    /**
     * Application login fuseaction
     *
     * @var string
     */
    private static $loginAction = "";

    /**
     * Application logout fuseaction
     *
     * @var string
     */
    private static $logoutAction = "";

    /**
     * Action that plugin will redirect when susseful login ends
     *
     * @var string
     */
    private static $nextAction = "";

    /**
     * Plugin listeners path
     *
     * @var array
     */
    private static $listenersPaths = array("plugins/", "security/");

    /**
     * Application authentication fuseaction
     *
     * @var string
     */
    private static $authenticationAction = "";

    /**
     * Return application login action
     *
     * @return string
     */
    private static function getLoginAction()
    {
        return self::$loginAction;
    }

    /**
     * Set application login action
     *
     * @param string $loginAction
     */
    private static function setLoginAction($loginAction)
    {
        self::$loginAction = $loginAction;
        Controller::getInstance()->getRequest()->getAction()->addXFA(
                "goToLoginAction", $loginAction);
    }

    /**
     * Return application logout action
     *
     * @return string
     */
    private static function getLogoutAction()
    {
        return self::$logoutAction;
    }

    /**
     * Set application logout action
     *
     * @param string $logoutAction
     */
    private static function setLogoutAction($logoutAction)
    {
        self::$logoutAction = $logoutAction;
        Controller::getInstance()->getRequest()->getAction()->addXFA(
                "goToLogoutAction", $logoutAction);
    }

    /**
     * Return next action
     *
     * @return string
     */
    private static function getNextAction()
    {
        return self::$nextAction;
    }

    /**
     * Set next action
     *
     * @param string $nextAction
     */
    private static function setNextAction($nextAction)
    {
        self::$nextAction = $nextAction;
        Controller::getInstance()->getRequest()->getAction()->addXFA(
                "goToNextAction", $nextAction);
    }

    /**
     * Return application authentication action
     *
     * @return string
     */
    private static function getAuthenticationAction()
    {
        return self::$authenticationAction;
    }

    /**
     * Set application authentication action
     *
     * @param string $authenticationAction
     */
    private static function setAuthenticationAction($authenticationAction)
    {
        self::$authenticationAction = $authenticationAction;
        Controller::getInstance()->getRequest()->getAction()->addXFA(
                "goToAuthenticationAction", $authenticationAction);
    }

    /**
     * Return listeners path array
     *
     * @return array
     */
    public static function getListenersPaths()
    {
        return self::$listenersPaths;
    }

    /**
     * Add one path to listeners path array if the path doesn't exists 
     *
     * @param string $path
     */
    public static function addListenersPaths($path)
    {
        if (!in_array($path, self::$listenersPaths)) {
            self::$listenersPaths[] = $path;
        }
    }

	/**
	 * Verify if the session was started. If not start the session.
	 */
	protected function checkSession()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
	}

	/**
	 * Run pre process actions
	 *
	 */
    protected function runPreProcess()
    {
        Controller::getInstance()->getDebugger()->registerEvent(
            new DebugEvent("MyFusesSecurityPlugin",
                "Getting security manager instance"));
        $manager = AbstractSecurityManager::getInstance();
        Controller::getInstance()->getDebugger()->registerEvent(
            new DebugEvent("MyFusesSecurityPlugin",
                "Calling security manager to create a credention based on the" .
                " session state."));
        $manager->createCredential();
        $this->configureListenersPaths();
        $this->configureSecurityManager($manager);
        $this->authenticate($manager);

        // TODO: SO!? What to do with this credential
        $credential = $_SESSION['MYFUSES_SECURITY_CREDENTIAL'];
    }

    /**
     * Run pre process fuseaction
     *
     */
    protected function runPreFuseaction()
    {
    	$manager = AbstractSecurityManager::getInstance();
        if($manager->isAuthenticated()) {
            foreach ($manager->getAuthorizationListeners() as $listener) {
                $listener->authorize($manager);
            }
        }
    }

	/**
	 *
	 *
	 */
    private function configureListenersPaths()
    {
        foreach($this->getParameter("ListenersPath") as $path) {
            if (substr($path, -1) != DIRECTORY_SEPARATOR) {
                $path .= DIRECTORY_SEPARATOR;
            }
            self::addListenersPaths($path);
        }
    }

    /**
     * Configure the security manager plugin
     * 
     * @param $manager
     */
    public function configureSecurityManager(SecurityManager $manager)
    {
        $this->configureParameters($manager);

        $authenticationListeners = $this->getParameter(
            "AuthenticationListener");

        $authorizationListeners = $this->getParameter("AuthorizationListener");

        // TODO: This is too brute force. We need more tought here.
        foreach ($this->getListenersPaths() as $path) {
            if (!FileHandler::isAbsolutePath($path)) {
                $path = $this->getApplication()->getPath() . $path;
            }

            foreach ($authenticationListeners as $listener) {
                if(file_exists($path . $listener . ".php")) {
                    require_once $path . $listener . ".php";

                    $manager->addAuthenticationListener(new $listener());
                }
            }

            foreach ($authorizationListeners as $listener) {
                if (file_exists($path . $listener . ".php")) {
                    require_once $path . $listener . ".php";

                    $manager->addAuthorizationListener(new $listener());
                }
            }
        }
    }

    /**
     * Configure all plugins plugins parameters
     * 
     * @param $manager
     */
    public function configureParameters(SecurityManager $manager)
    {
    	// getting next action
        $nextAction = $this->getParameter("NextAction");

        if (count($nextAction)) {
            $nextAction = $nextAction[0];
        } else {
            $nextAction = $this->getApplication()->getDefaultFuseaction();
        }

        self::setNextAction($nextAction);

        // getting login action
        $loginAction = $this->getParameter("LoginAction");

        if (count($loginAction)) {
            $loginAction = $loginAction[0];
        } else {
            $loginAction = $this->getApplication()->getDefaultFuseaction();
        }

        self::setLoginAction($loginAction);

        // getting logout action
        $logoutAction = $this->getParameter("LogoutAction");

        if (count($logoutAction)) {
            $logoutAction = $logoutAction[0];
        } else {
            $logoutAction = "";
        }

        self::setLogoutAction($logoutAction);

        // getting login action    
        $authenticationAction = $this->getParameter("AuthenticationAction");

        if (count($authenticationAction)) {
            $authenticationAction = $authenticationAction[0];
        } else {
            $authenticationAction = "";
        }

        self::setAuthenticationAction($authenticationAction);
    }

    /**
     * Authenticating user
     *
     * @param SecurityManager $manager
     */
    public function authenticate(SecurityManager $manager)
    {
    	Controller::getInstance()->getRequest()->getAction()->addXFA(
    	    "goToIndexAction",
            $this->getApplication()->getDefaultFuseaction()
        );

    	// getting next action
        $nextAction = self::getNextAction();

        // getting login action
        $loginAction = self::getLoginAction();

        // getting logout action
        $logoutAction = self::getLogoutAction();

        // getting login action    
        $authenticationAction = self::getAuthenticationAction();

        $currentAction = Controller::getInstance()->getRequest()->
            getFuseActionName();

        if ($logoutAction == $currentAction)
        {
            if ($manager->isAuthenticated())
            {
                $manager->logout();
            } else {
                Controller::sendToUrl(
                    Controller::getMySelfXfa("goToLoginAction"));
        	}
        }

        // TODO: This should be moved to the default attribute.
        if ($this->mustAuthenticate())
        {

            if ($loginAction != $currentAction && $authenticationAction !=
                $currentAction)
            {
                if (!$manager->isAuthenticated())
                {
                    Controller::sendToUrl(Controller::getMySelfXfa(
                        "goToLoginAction"));
                }
            }

            if(!$manager->isAuthenticated())
            {
                if (Controller::getInstance()->getRequest()->getFuseActionName()
                    == $this->getAuthenticationAction())
                {
                    $manager->clearMessages();

                    $error = false;

                    foreach ($manager->getAuthenticationListeners() as
                             $listener)
                    {
                        $listener->authenticate($manager);
                    }

                    if(!$manager->isAuthenticated())
                    {
                        Controller::sendToUrl( Controller::getMySelfXfa(
                            "goToLoginAction"));
                    } else {
                        Controller::sendToUrl( Controller::getMySelfXfa(
                            "goToNextAction"));
                    }
                }
            } else {
            	$currentAction = Controller::getInstance(
                )->getRequest()->getFuseActionName();

            	if ($currentAction == $this->getAuthenticationAction() ||
                    $currentAction == $this->getLoginAction())
            	{
                    Controller::sendToUrl(Controller::getMySelfXfa(
                        "goToNextAction"));
                }
            }
        }
    }

    /**
     * Check if the pluging must trigger the authentication
     *
     * @return bool
     */
    public function mustAuthenticate()
    {
        $security = Controller::getInstance()->getCurrentAction(
        )->getSecurity();
        $circuitPermissions = Controller::getInstance(
        )->getCurrentCircuit()->getPermissions();
        $actionPermissions = Controller::getInstance(
        )->getCurrentAction()->getPermissions();
        if ($security == "pessimistic") {
            return true;
        } else if ($security == "optimistic") {
            if($circuitPermissions != "") {
                return true;
            }
            if($actionPermissions != "") {
                return true;
            }
        }
        // If not it is disabled
        return false;
    }
}
