<?php

require_once "myfuses/util/security/MyFusesSecurityManager.class.php";

abstract class MyFusesAbstractSecurityManager 
    implements MyFusesSecurityManager {

    /**
     * Security Manager listeners
     *
     * @var array
     */
    private $securityListeners = array();

    private static $instance;

    public function isAuthenticated(){
        foreach( $this->getSecutiyListeners() as $listener ){
            $listener->authenticationPerformed();
        }
    }

    public function isAuthorized(){
        foreach( $this->getSecutiyListeners() as $listener ){
            $listener->authorizationPerformed();
        }
    }

    /**
     * Add one Autentication Listener
     *
     * @param AuthenticationListener $listener
     */
    public function addSecutiyListener( MyFusesSecuriyListener $listener ) {
        $this->securityListeners[] = $listener;
    }

    /**
     * Return all authentication listeners
     *
     * @return array Array of AuthenticationListeners
     */
    public function getSecutiyListeners() {
        return $this->securityListeners;
    }

    /**
     * Return new Basic Security Manager instance
     *
     * @return SecurityManager
     */
    public static function getInstance() {
        if( is_null( self::$instance ) ) {
            self::$instance = new BasicSecurityManager();
        }
        
        return self::$instance;
    }

} 