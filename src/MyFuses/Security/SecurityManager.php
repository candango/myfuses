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

/**
 * MyFusesSecurityManager - MyFusesSecurityManager.php
 *
 * MyFuses Security Manager interface.
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @since      2e0c26a744b984d6463db487a51387bb4005488e
 */
interface SecurityManager
{
	const MESSAGE_INFO = "INFO";

	const MESSAGE_ERROR = "ERROR";

	const MESSAGE_AUTH_ERROR = "AUTH_ERROR";

    /**
     * This method creates a new credential
     */
    public function createCredential();

    /**
     * Return manager credential
     *
     * @return Credential Managed iflux credential
     */
    public function getCredential();

    /**
     * Persists the credential into the session
     *
     * @param Credential $credential
     */
    public function persistCredential(Credential $credential);

    /**
     * Returns true/false if the credential is authenticated
     *
     * @return boolean
     */
    public function isAuthenticated();

    /**
     * Return true/fase if the credential is authorized
     *
     * @return boolean
     */
    public function isAuthorized();

    /**
     * Add one Authentication Listener to manager
     *
     * @param AuthenticationListener $listener
     */
    public function addAuthenticationListener(AuthenticationListener
        $listener);

    /**
     * Return all authentication listeners registered
     *
     * @return array Array of AuthenticationListeners
     */
    public function getAuthenticationListeners();

    /**
     * Add one Authorization Listener to manager
     *
     * @param AuthorizationListener $listener
     */
    public function addAuthorizationListener(AuthorizationListener
        $listener);

    /**
     * Return all Authorization listeners registered
     *
     * @return array Array of AuthorizationListeners
     */
    public function getAuthorizationListeners();

    /**
     * Add one Authentication Listener
     *
     * @param SecuriyListener $listener
     */
    public function addSecutiyListener(SecuriyListener $listener);

    /**
     * Return all authentication listeners
     *
     * @return array Array of AuthenticationListeners
     */
    public function getSecutiyListeners();

    public function getUserLoginField();

    public function getUserPasswordField();

    public function setUserPasswordField( $userPasswordField );

    public function getUserLoginInPost();

    public function getUserLoginInGet();

    public function getUserLoginInRequest();

    public function getUserPasswordInPost();

    public function getUserPasswordInGet();

    public function getUserPasswordInRequest();

    public function getMessage($type, $name);

    public function setMessage($type, $name, $message);

    public function clearMessages();

    public function logout();
}

/**
 * AuthenticationListener - SecurityManager.php
 *
 * MyFuses Authentication Listener
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @since      2e0c26a744b984d6463db487a51387bb4005488e
 */
interface AuthenticationListener
{
    public function authenticate(MyFusesSecurityManager $manager);

    public function authenticationPerformed(MyFusesSecurityManager $manager);
}

/**
 * AuthorizationListener - SecurityManager.php
 *
 * MyFuses Authorization Listener
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @since      2e0c26a744b984d6463db487a51387bb4005488e
 */
interface AuthorizationListener
{
	public function authorize(SecurityManager $manager);

    public function authorizationPerformed(SecurityManager $manager);
}

/**
 * SecuriyListener - SecurityManager.php
 *
 * MyFuses Security Listener
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @since      2e0c26a744b984d6463db487a51387bb4005488e
 */
interface MyFusesSecuriyListener extends
    AuthenticationListener,
    AuthorizationListener
{
}
