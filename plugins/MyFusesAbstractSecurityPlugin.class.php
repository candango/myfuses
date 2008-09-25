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
     * Return application login action
     *
     * @return string
     */
    private static function getLoginAction() {
        return self::loginAction;
    }
    
    /**
     * Set application login action
     *
     * @param string $loginAction
     */
    private static function setLoginAction( $loginAction ) {
        self::$loginAction = $loginAction;
        MyFuses::getInstance()->getRequest()->getAction()->addXFA( 
                'goToLoginPage', $loginAction );
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
        
        $currentAction = MyFuses::getInstance()->getRequest()->
            getFuseActionName();
        
        if( $loginAction != $currentAction ) {
            if( !$manager->isAuthenticated() ) {
                MyFuses::sendToUrl( MyFuses::getMySelfXfa( 
                    'goToLoginPage' ) );
            }
        }
    }
    
}