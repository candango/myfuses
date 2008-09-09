<?php
require_once "myfuses/util/security/MyFusesBasicCredential.class.php";

interface MyFusesSecurityManager {

    /**
     * This method creates a new credendtial
     */
    public function createCredential();

    /**
     * Return manager credential
     *
     * return IfluxCredential Managed iflux credential
     */
    public function getCredential();

    /**
     * Set manager credential
     *
     * @param IfluxCredential $credential
     */
    public function setCredential( MyFusesCredential $credential );

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

}

interface MyFusesSecuriyListener {

    public function authenticationPerformed(  );

    public function authorizationPerformed(  );

}