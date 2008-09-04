<?php
abstract class AbstractSecurityPlugin extends AbstractPlugin {
	
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
        
        $manager = AbstractSecurityManager::getInstance();
        
        $this->configureSecurityManager( $manager );
        
        $manager->createCredential();
        
    }
	
}
