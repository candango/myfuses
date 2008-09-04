<?php
require_once "myfuses/plugins/MyFusesAbstractSecurityPlugin.class.php";

class MyFusesBasicSecurityPlugin extends MyFusesAbstractSecurityPlugin{


        public function run() {

            parent::run();
            
            $securityManager = MyFusesAbstractSecurityManager::getInstance();
            
            var_dump( $securityManager );die();

        }

        public function configureSecurityManager( MyFusesSecurityManager $manager ) {
            
        }
}