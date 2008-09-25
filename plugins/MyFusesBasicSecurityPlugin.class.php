<?php
require_once "myfuses/plugins/MyFusesAbstractSecurityPlugin.class.php";

class MyFusesBasicSecurityPlugin extends MyFusesAbstractSecurityPlugin{


        public function run() {
            parent::run();
            
            
        }

        public function configureSecurityManager( MyFusesSecurityManager $manager ) {
            
            // getting login action
            $loginAction = $this->getParameter( 'LoginAction' );
            
            $loginAction = $loginAction[ 0 ];
            
            $currentAction = MyFuses::getInstance()->getRequest()->
                getFuseActionName();
            
            MyFuses::getInstance()->getRequest()->getAction()->addXFA( 
                'goToLoginPage', $loginAction );
            
            
            if( $loginAction != $currentAction ) {
                if( !$manager->isAuthenticated() ) {
                    MyFuses::sendToUrl( MyFuses::getMySelfXfa( 
                        'goToLoginPage' ) );
                }
            }
            
        }
        
}