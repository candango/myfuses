<?php
require_once "myfuses/util/security/MyFusesBasicCredential.class.php";

interface MyFusesSecurityManager {
    
	const MESSAGE_INFO = "INFO";
	
	const MESSAGE_ERROR = "ERROR";
	
	const MESSAGE_AUTH_ERROR = "AUTH_ERROR";
	
    /**
     * This method creates a new credendtial
     */
    public function createCredential();

    /**
     * Return manager credential
     *
     * @return MyFusesCredential Managed iflux credential
     */
    public function getCredential();

    /**
     * Persists the credential into the session
     *
     * @param IfluxCredential $credential
     */
    public function persistCredential( MyFusesCredential $credential );

    /**
     * Returns true/false if the crendetial is athenticaded
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
     * @param MyFusesAuthenticationListener $listener
     */
    public function addAuthenticationListener( MyFusesAuthenticationListener 
        $listener );
    
    /**
     * Return all authentication listeners registered
     *
     * @return array Array of AuthenticationListeners
     */
    public function getAuthenticationListeners();
        
    /**
     * Add one Authorization Listener to manager
     *
     * @param MyFusesAuthenticationListener $listener
     */
    public function addAuthorizationListener( MyFusesAuthorizationListener 
        $listener );
        
    /**
     * Return all Authorization listeners registered
     *
     * @return array Array of AuthorizationListeners
     */
    public function getAuthorizationListeners();
        
    /**
     * Add one Autentication Listener
     *
     * @param AuthenticationListener $listener
     */
    public function addSecutiyListener( MyFusesSecuriyListener $listener );

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
    
    public function getMessage( $type, $name );
    
    public function setMessage( $type, $name, $message );
    
    public function clearMessages();
    
    public function logout();
    
}

interface MyFusesAuthenticationListener {
    
    public function authenticate( MyFusesSecurityManager $manager );
    
    public function authenticationPerformed( MyFusesSecurityManager $manager );
    
}

interface MyFusesAuthorizationListener {
    
	public function authorize( MyFusesSecurityManager $manager );
	
    public function authorizationPerformed( MyFusesSecurityManager $manager );
    
}

interface MyFusesSecuriyListener extends MyFusesAuthenticationListener {

    public function authorizationPerformed();

}