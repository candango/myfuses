<?php
/**
 * MyFusesAbstractSecurityPlugin  - MyFusesAbstractSecurityPlugin.class.php
 * 
 * Plugin that controls all authentication and authorization flow 
 * over an application.
 * 
 * PHP version 5
 * 
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * This product includes software developed by the Fusebox Corporation 
 * (http://www.fusebox.org/).
 * 
 * The Original Code is Fuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2007.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   plugins
 * @package    myfuses.plugins
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: IParseable.class.php 205 2007-12-18 12:47:40Z flavio.garcia $
 */

require_once 'myfuses/util/security/MyFusesAbstractSecurityManager.class.php';

/**
 * MyFusesAbstractSecurityPlugin  - MyFusesAbstractSecurityPlugin.class.php
 * 
 * Plugin that controls all authentication and authorization flow 
 * over an application.
 * 
 * PHP version 5
 *
 * @category   plugins
 * @package    myfuses.plugins
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 205 $
 * @since      Revision 25
 */
abstract class MyFusesAbstractSecurityPlugin extends AbstractPlugin {
    
    /**
     * Application login fuseaction
     *
     * @var string
     */
    private static $loginAction = "";
    
    /**
     * Plugin listeners path
     *
     * @var array
     */
    private static $listenersPath = array( 'plugins/' );
    
    /**
     * Application authentication fuseaction
     *
     * @var string
     */
    private static $authenticationAction = "";
    
    /**
     * Return application login action
     *
     * @return string
     */
    private static function getLoginAction() {
        return self::$loginAction;
    }
    
    /**
     * Set application login action
     *
     * @param string $loginAction
     */
    private static function setLoginAction( $loginAction ) {
        self::$loginAction = $loginAction;
        MyFuses::getInstance()->getRequest()->getAction()->addXFA( 
                'goToLoginAction', $loginAction );
    }
    
    /**
     * Return application authentication action
     *
     * @return string
     */
    private static function getAuthenticationAction() {
        return self::$authenticationAction;
    }
    
    /**
     * Set application authentication action
     *
     * @param string $authAction
     */
    private static function setAuthenticationAction( $authenticationAction ) {
        self::$authenticationAction = $authenticationAction;
        MyFuses::getInstance()->getRequest()->getAction()->addXFA( 
                'goToAuthenticationAction', $authenticationAction );
    }
    
    /**
     * Return listeners path array
     *
     * @return array
     */
    public static function getListenersPath() {
        return self::$listenersPath;
    }
    
    /**
     * Add one path to listeners path array if the path doesn't exists 
     *
     * @param string $path
     */
    public static function addListenerPath( $path ) {
        if( !in_array( $path, self::$listenersPath ) ) {
            self::$listenersPath[] = $path;    
        }
    }
    
	public function run() {
		
	    $this->checkSession();
	    
	    switch( $this->getPhase() ) {
            case Plugin::PRE_PROCESS_PHASE:
                $this->runPreProcess();
                break;
        }
	    
	}
	
	/**
	 * Verify if the session was started. If not start the session
	 */
	private function checkSession() {
        if( !isset( $_SESSION ) ) {
            session_start();
        }
	}
	
	/**
	 * Run pre process actions
	 *
	 */
    private function runPreProcess() {
        
        $manager = MyFusesAbstractSecurityManager::getInstance();
        
        $manager->createCredential();
        
        $this->configurePlugin();
        
        $this->configureSecurityManager( $manager );
        
        $this->authenticate( $manager );
        
        $credential = $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ];
        
    }
    
	/**
	 * Configure plugin reading the his parameters
	 *
	 */
    private function configurePlugin() {
        
        foreach( $this->getParameter( 'ListenersPath' ) as $path ) {
            self::addListenerPath( $path );
        }
        
    }
    
    public function configureSecurityManager( 
        MyFusesSecurityManager $manager ) {
        
        $authenticationListeners = $this->getParameter( 
            'AuthenticationListener' );
        
        foreach( $this->getListenersPath() as $path ) {
            if( !MyFusesFileHandler::isAbsolutePath( $path ) ) {
                $path = $this->getApplication()->getPath() . $path;
            }
            
            foreach( $authenticationListeners as $listener ) {
                if( file_exists( $path . $listener . ".class.php" ) ) {
                    require_once $path . $listener . ".class.php";
                    
                    $manager->addAuthenticationListener( new $listener() );
                }
                
            }
        }
        
    }
    
    /**
     * Authenticating user
     *
     * @param MyFusesSecurityManager $manager
     */
    public function authenticate( MyFusesSecurityManager $manager ) {
        
        if( ( strtolower( MyFuses::getInstance()->getRequest()->getAction()->
            getCustomAttribute( "security", "enabled" ) ) != "false" ) ) {
                
            MyFuses::getInstance()->getRequest()->getAction()->addXFA( 
                    'goToIndexAction', 
                    $this->getApplication()->getDefaultFuseaction() );
            
            // getting login action
            $loginAction = $this->getParameter( 'LoginAction' );
            
            $loginAction = $loginAction[ 0 ];
            
            self::setLoginAction( $loginAction );
            
            $authenticationAction = $this->getParameter( 
                'AuthenticationAction' );
            
            $authenticationAction = $authenticationAction[ 0 ];
            
            self::setAuthenticationAction( $authenticationAction );
            
            $currentAction = MyFuses::getInstance()->getRequest()->
                getFuseActionName();
            
            if( $loginAction != $currentAction && $authenticationAction != 
                $currentAction ) {
                if( !$manager->isAuthenticated() ) {
                    MyFuses::sendToUrl( MyFuses::getMySelfXfa( 
                        'goToLoginAction' ) );
                }
            }
            
            if( !$manager->isAuthenticated() ) {
                if( MyFuses::getInstance()->getRequest()->getFuseActionName() == 
                    $this->getAuthenticationAction() ) {
                    
                    unset( $_SESSION[ 'MYFUSES_SECURITY' ][ 'AUTH_ERRORS' ] );
                
                    $error = false;
                    
                    foreach( $manager->getAuthenticationListeners() as 
                        $listner ) {
                        $listner->authenticate( $manager );
                    }
                    
                    if( !$manager->isAuthenticated() ) {
                        MyFuses::sendToUrl( MyFuses::getMySelfXfa( 
                            'goToLoginAction' ) );
                    }
                    else {
                        MyFuses::sendToUrl( MyFuses::getMySelfXfa( 
                            'goToIndexAction' ) );
                    }
                }
            }
        }
    }
    
}