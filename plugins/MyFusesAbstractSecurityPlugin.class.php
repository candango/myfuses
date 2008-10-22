<?php
require_once 'myfuses/util/security/MyFusesAbstractSecurityManager.class.php';

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