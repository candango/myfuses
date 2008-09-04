<?php
require_once "iflux/plugins/myfuses/AbstractSecurityPlugin.class.php";

class BasicSecurityPlugin extends AbstractSecurityPlugin{


        public function run() {

            parent::run();
            
            $securityManager = AbstractSecurityManager::getInstance();

        }

        public function configureSecurityManager( MyFusesSecurityManager $manager ) {
            
        }
}