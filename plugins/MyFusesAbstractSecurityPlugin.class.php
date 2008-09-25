<?php
require_once 'myfuses/util/security/MyFusesAbstractSecurityManager.class.php';

abstract class MyFusesAbstractSecurityPlugin extends AbstractPlugin {
	
    /**
     * Application login fuseaction
     *
     * @var string
     */
    private static $loginAction = "";
    
    
    /**
     * Application authentication fuseaction
     *
     * @var string
     */
    private static $authAction = "";
    
    /**
     * Return application login action
     *
     * @return string
     */
    private static function getLoginAction() {
        return self::$loginAction;
    }
    
    /**
     * Set application login action
     *
     * @param string $loginAction
     */
    private static function setLoginAction( $loginAction ) {
        self::$loginAction = $loginAction;
        MyFuses::getInstance()->getRequest()->getAction()->addXFA( 
                'goToLoginAction', $loginAction );
    }
    
    /**
     * Return application authentication action
     *
     * @return string
     */
    private static function getAuthAction() {
        return self::$authAction;
    }
    
    /**
     * Set application authentication action
     *
     * @param string $authAction
     */
    private static function setAuthAction( $authAction ) {
        self::$authAction = $authAction;
        MyFuses::getInstance()->getRequest()->getAction()->addXFA( 
                'goToAuthAction', $authAction );
    }
    
	public function run() {
		
	    $this->checkSession();
	    
	    switch( $this->getPhase() ) {
            case Plugin::PRE_PROCESS_PHASE:
                $this->runPreProcess();
                break;
        }
	    
	}
	
	private function checkSession() {
        if( !isset( $_SESSION ) ) {
            session_start();
        }
	}
	
    private function runPreProcess() {
        
        $manager = MyFusesAbstractSecurityManager::getInstance();
        
        $manager->createCredential();
        
        $this->configureSecurityManager( $manager );
        
        $credential = $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ];
        
    }
	
    public function configureSecurityManager( MyFusesSecurityManager $manager ) {
            
        // getting login action
        $loginAction = $this->getParameter( 'LoginAction' );
        
        $loginAction = $loginAction[ 0 ];
        
        self::setLoginAction( $loginAction );
        
        $authAction = $this->getParameter( 'AuthAction' );
        
        $authAction = $authAction[ 0 ];
        
        self::setAuthAction( $authAction );
        
        $currentAction = MyFuses::getInstance()->getRequest()->
            getFuseActionName();
        
        if( $loginAction != $currentAction && $authAction != $currentAction ) {
            if( !$manager->isAuthenticated() ) {
                MyFuses::sendToUrl( MyFuses::getMySelfXfa( 
                    'goToLoginAction' ) );
            }
        }
    }
    
}