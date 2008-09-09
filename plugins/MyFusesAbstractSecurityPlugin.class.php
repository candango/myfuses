<?php
require_once 'myfuses/util/security/MyFusesAbstractSecurityManager.class.php';

abstract class MyFusesAbstractSecurityPlugin extends AbstractPlugin {
	
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
        
        $this->configureSecurityManager( $manager );
        
        $manager->createCredential();
        
        $credential = $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ];
        var_dump( $credential->getExpireDate() );
        var_dump( $credential );die();
        
    }
	
}
