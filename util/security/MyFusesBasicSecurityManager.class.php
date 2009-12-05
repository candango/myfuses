<?php
require_once "myfuses/util/security/MyFusesAbstractSecurityManager.class.php";

class MyFusesBasicSecurityManager extends MyFusesAbstractSecurityManager {
    
    public function createCredential() {
        if( !isset( $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] ) ) {
            $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] = 
                new MyFusesBasicCredential();    
        }
        else {
            $credential = $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ];
            if( $credential->isExpired() ) {
                $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] = 
                    new MyFusesBasicCredential();
            }
            else {
                $credential->increaseNavigationTime();
            }
        }
    }
    
    /**
     * Return registered credential
     *
     * @return MyFusesCredential
     */
    public function getCredential() {
    	if( isset( $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] ) ) {
    	   return $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ];	
    	}
        return null;
    }
    
    public function setCredential( MyFusesCredential $credential ) {
        $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] = $credential;
    }
    
}