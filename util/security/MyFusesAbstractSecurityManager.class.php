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
    
    /**
     * Authentication Manager listeners
     *
     * @var array
     */
    private $authenticationListeners = array();
    
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
    private $userPassword = "userPassword";
    
    public function isAuthenticated() {
        
        $credential = $this->getCredential();
        
        return $credential->isAuthenticated();
    }

    public function isAuthorized(){
        foreach( $this->getSecutiyListeners() as $listener ){
            $listener->authorizationPerformed();
        }
    }
    
    /**
     * Add one Authentication Listener to manager
     *
     * @param MyFusesAuthenticationListener $listener
     */
    public function addAuthenticationListener( 
        MyFusesAuthenticationListener $listener ) {
        $this->authenticationListeners[] = $listener;
    }

    /**
     * Return all Authentication listeners registered
     *
     * @return array Array of AuthenticationListeners
     */
    public function getAuthenticationListeners() {
        return $this->authenticationListeners;
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
     * @return MyFusesSecurityManager
     */
    public static function getInstance() {
        if( is_null( self::$instance ) ) {
            require_once "myfuses/util/security/MyFusesBasicSecurityManager.class.php";
            self::$instance = new MyFusesBasicSecurityManager();
        }
        
        return self::$instance;
    }
    
    public function getUserLoginField(){
        return $this->userLoginField;
    }
    
    public function setUserLoginField( $userLoginField ) {
        $this->userLoginField = $userLoginField;
    }
    
    public function getUserPasswordField(){
        return $this->userPasswordField;
    }
    
    public function setUserPasswordField( $userPasswordField ) {
        $this->userPasswordField = $userPasswordField;
    }
} 