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

namespace Candango\MyFuses\Security;

use Candango\MyFuses\Controller;

/**
 * MyFusesAbstractSecurityManager - MyFusesAbstractSecurityManager.php
 *
 * MyFuses Abstract Security Manager
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @since      2e0c26a744b984d6463db487a51387bb4005488e
 */
abstract class AbstractSecurityManager implements SecurityManager
{
    /**
     * Security Manager listeners
     *
     * @var array
     */
    private $securityListeners = array();

    /**
     * Authentication Manager listeners
     *
     * @var array
     */
    private $authenticationListeners = array();

    /**
     * Authorization Manager listeners
     *
     * @var array
     */
    private $authorizationListeners = array(); 

    private static $instance;

    /**
     * User login field name
     *
     * @var string
     */
    private $userLoginField = "userLogin";

    /**
     * User password field name
     *
     * @var string
     */
    private $userPasswordField = "userPassword";

    public function isAuthenticated()
    {
        $credential = $this->getCredential();
        return $credential->isAuthenticated();
    }

    public function isAuthorized()
    {
        foreach ($this->getSecutiyListeners() as $listener) {
            $listener->authorizationPerformed();
        }
    }

    /**
     * Add one Authentication Listener to manager
     *
     * @param AuthenticationListener $listener
     */
    public function addAuthenticationListener(
        AuthenticationListener $listener
    ) {
        $this->authenticationListeners[] = $listener;
    }

    /**
     * Return all Authentication listeners registered
     *
     * @return array Array of AuthenticationListeners
     */
    public function getAuthenticationListeners()
    {
        return $this->authenticationListeners;
    }

    /**
     * Add one Authorization Listener to manager
     *
     * @param AuthorizationListener $listener
     */
    public function addAuthorizationListener(
        AuthorizationListener $listener
    ) {
        $this->authorizationListeners[] = $listener;
    }

    /**
     * Return all Authorization listeners registered
     *
     * @return array Array of AuthorizationListeners
     */
    public function getAuthorizationListeners()
    {
        return $this->authorizationListeners;
    }

    /**
     * Add one Authentication Listener
     *
     * @param SecuriyListener $listener
     */
    public function addSecutiyListener(SecuriyListener $listener)
    {
        $this->securityListeners[] = $listener;
    }

    /**
     * Return all authentication listeners
     *
     * @return array Array of AuthenticationListeners
     */
    public function getSecutiyListeners()
    {
        return $this->securityListeners;
    }

    /**
     * Return new Basic Security Manager instance
     *
     * @return SecurityManager
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new BasicSecurityManager();
        }
        return self::$instance;
    }

    public function getUserLoginField()
    {
        return $this->userLoginField;
    }

    public function setUserLoginField($userLoginField)
    {
        $this->userLoginField = $userLoginField;
    }

    public function getUserPasswordField()
    {
        return $this->userPasswordField;
    }

    public function setUserPasswordField($userPasswordField)
    {
        $this->userPasswordField = $userPasswordField;
    }

    public function getUserLoginInPost()
    {
        return $_POST[$this->getUserLoginField()];
    }

    public function getUserLoginInGet()
    {
        return $_GET[$this->getUserLoginField()];
    }

    public function getUserLoginInRequest()
    {
        return $_REQUEST[$this->getUserLoginField()];
    }

    public function getUserPasswordInPost()
    {
        return $_POST[$this->getUserPasswordField()];
    }

    public function getUserPasswordInGet() {
        return $_GET[$this->getUserPasswordField()];
    }

    public function getUserPasswordInRequest()
    {
        return $_REQUEST[$this->getUserPasswordField()];
    }

    public function getMessage($type, $name)
    {
    	return isset(
    	    $_SESSION['MYFUSES_SECURITY']['MESSAGES'][
    	        strtoupper($type)][strtoupper($name)]) ?
            $_SESSION['MYFUSES_SECURITY']['MESSAGES'][
                strtoupper($type)][strtoupper($name)] : "";
    }

    public function setMessage($type, $name, $message)
    {
    	$_SESSION['MYFUSES_SECURITY']['MESSAGES'][strtoupper($type)][
    	    strtoupper($name)] = $message;
    }

    public function clearMessages()
    {
    	unset($_SESSION['MYFUSES_SECURITY']['MESSAGES']);
    }

    public function logout()
    {
        session_destroy();
        Controller::sendToUrl(MyFuses::getMySelfXfa("goToIndexAction"));
    }
}
