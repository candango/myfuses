<?php
require_once "myfuses/util/security/MyFusesAbstractSecurityManager.class.php";
require_once "myfuses/util/data/MyFusesJsonUtil.class.php";

class MyFusesBasicSecurityManager extends MyFusesAbstractSecurityManager {
    
    public function createCredential() {
        MyFuses::getInstance()->getDebugger()->registerEvent(
            new MyFusesDebugEvent( "MyfusesSecurityManager",
                "Myfuses Security Plugin creating credential." ) );
        if(!isset( $_SESSION['MYFUSES_SECURITY_CREDENTIAL'])) {
            MyFuses::getInstance()->getDebugger()->registerEvent(
                new MyFusesDebugEvent( "MyfusesSecurityManager",
                    "No credential found. Creating a new one." ) );
            $credential = new MyFusesBasicCredential();

            $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] =
                $credential->getData();
        }
        else {
            MyFuses::getInstance()->getDebugger()->registerEvent(
                new MyFusesDebugEvent( "MyfusesSecurityManager",
                    "Credential found. Checking if isnt expired." ) );
            $credential = new MyFusesBasicCredential();
            $credential->setData($_SESSION['MYFUSES_SECURITY_CREDENTIAL']);

            if($credential->isExpired()) {
                MyFuses::getInstance()->getDebugger()->registerEvent(
                    new MyFusesDebugEvent( "MyfusesSecurityManager",
                        "Credential expired. Creating a new one." ) );
                $credential = new MyFusesBasicCredential();

                $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] =
                    $credential->getData();
            }
            else {
                MyFuses::getInstance()->getDebugger()->registerEvent(
                    new MyFusesDebugEvent( "MyfusesSecurityManager",
                        "Credential not expired. Increasing navigation time." ) );
                $credential->increaseNavigationTime();
                $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] =
                    $credential->getData();
            }
        }
    }
    
    /**
     * Return registered credential
     *
     * @return MyFusesCredential
     */
    public function getCredential() {
        if(isset($_SESSION['MYFUSES_SECURITY_CREDENTIAL'])) {
            $credential = new MyFusesBasicCredential();
            $credential->setData($_SESSION['MYFUSES_SECURITY_CREDENTIAL']);
            return $credential;
    	}
        return null;
    }
    
    public function persistCredential(MyFusesCredential $credential) {
        $_SESSION['MYFUSES_SECURITY_CREDENTIAL'] = $credential->getData();
    }
    
}
