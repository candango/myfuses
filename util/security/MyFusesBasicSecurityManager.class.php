<?php
require_once "myfuses/util/security/MyFusesAbstractSecurityManager.class.php";

class MyFusesBasicSecurityManager extends MyFusesAbstractSecurityManager {
    
    private $credential;
    
    public function createCredential() {
        if( !isset( $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] ) ) {
            $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] = 
                new MyFusesBasicCredential();    
        }
    }
    
    public function getCredential() {
        return $this->credential;
    }
    
    public function setCredential( MyFusesCredential $credential ) {
        $this->credential = $credential;
    }
    
}