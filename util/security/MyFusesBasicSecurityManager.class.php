<?php
require_once "myfuses/util/security/MyFusesAbstractSecurityManager.class.php";

class BasicSecurityManager extends MyFusesAbstractSecurityManager {
    
    private $credential;
    
    public function createCredential() {
        // TODO create credential here
    }
    
    public function getCredential() {
        return $this->credential;
    }
    
    public function setCredential( MyFusesCredential $credential ) {
        $this->credential = $credential;
    }
    
}