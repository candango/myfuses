<?php
/**
 * MyFuses  - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
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
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses
 * @author     Flávio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */
define( "MYFUSES_ROOT_PATH", dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

require_once MYFUSES_ROOT_PATH . "exception/MyFusesException.class.php";

try {
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "core/ICacheable.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "core/IParseable.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "core/BasicApplication.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "engine/MyFusesApplicationLoaderListener.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "engine/AbstractMyFusesLoader.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "engine/loaders/XmlMyFusesLoader.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "engine/MyFusesApplicationBuilderListener.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "engine/BasicMyFusesBuilder.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "process/FuseRequest.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "process/MyFusesLifecycle.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "process/MyFusesDebugger.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "util/context/MyFusesContext.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "util/file/MyFusesFileHandler.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "util/data/MyFusesDataUtil.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "util/data/MyFusesJsonUtil.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "util/data/MyFusesXmlUtil.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "util/i18n/MyFusesI18nHandler.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "util/i18n/MyFusesNativeI18nHandler.class.php" );
}
catch( MyFusesMissingCoreFileException $mfmcfe ) {
    $mfmcfe->breakProcess();
}

/**
 * MyFuses  - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class MyFuses {
    
    /**
     * The MyFuses root path constant
     * 
     * @static
     * @final
     */
    const MYFUSES_ROOT_PATH = MYFUSES_ROOT_PATH;
    
    const MODE_DEVELOPMENT = "development";
    const MODE_PRODUCTION = "production";
    
    /**
     * Memcache enabled flag
     *
     * @var boolean
     */
    private $memcacheEnabled = false;
    
    /**
     * All myfuses memcache servers
     *
     * @var array
     */
    private $memcaheServers;
    
    /**
     * MyFuses memcache instance
     *
     * @var Memcache
     */
    private $memcache;
    
    /**
     * Path used by myfuses to search some plugin
     *
     * @var array
     */
    private $pluginPaths = array();
    
    /**
     * Path used by myfuses to search i18n files
     *
     * @var array
     */
    private $i18nPaths = array();
    
    /**
     * Path used by myfuses to search verbs
     *
     * @var array
     */
    private $verbPaths = array();
    
    /**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
    protected static $instance;
    
    /**
     * Array of registered applications
     * 
     * @var array
     */
    protected $applications = array();
    
    /**
     * The MyFuses request instance
     * 
     * @var FuseRequest
     */
    private $request;
    
    /**
     * Instance Lifecycle
     * 
     * @var MyFusesLifecycle
     */
    private $lifecycle;
    
    /**
     * Default debugger
     * 
     * @var MyFusesDebugger
     */
    private $debugger;
    
    private $parsedPath;
    
    private $applicationClass = "BasicApplication";

    /**
     * I18n type flag. Default native.
     *
     * @var string
     */
    private static $i18nType = MyFusesI18nHandler::NATIVE_TYPE;
    
    /**
     * MyFuses constructor
     *
     * @param MyFusesLoader $loader
     * @param string $applicationName
     */
    protected function __construct() {
        $this->builder = new BasicMyFusesBuilder();
        $this->debugger = new MyFusesDebugger();
        
        $pathStr = str_replace( array( DIRECTORY_SEPARATOR, ':', '/' ), '_', 
            dirname( $_SERVER[ 'SCRIPT_FILENAME' ] ) );
        
        $pathStr = str_replace( '__' , '_', $pathStr );
            
        if( substr( $pathStr, 0, 1 ) == "_" ) {
            $pathStr = substr( $pathStr, 1, strlen( $pathStr ) );      
        }
        
        $this->setParsedPath( MyFuses::MYFUSES_ROOT_PATH . "parsed" . 
            DIRECTORY_SEPARATOR . $pathStr . DIRECTORY_SEPARATOR );        
            
        // adding plugin paths
        $this->addPluginPath( "plugins" . DIRECTORY_SEPARATOR );
        $this->addPluginPath( self::MYFUSES_ROOT_PATH . "plugins" . DIRECTORY_SEPARATOR );
        
        // adding i18n paths
        $this->addI18nPath( self::MYFUSES_ROOT_PATH . "i18n" . DIRECTORY_SEPARATOR );
        $this->addI18nPath( "i18n" . DIRECTORY_SEPARATOR );
        
        
        // adding verb paths
        $this->addVerbPath( self::MYFUSES_ROOT_PATH );
        
    }
    
    /**
     * Return i18n type
     *
     * @return string
     */
    public static function getI18nType() {
        return self::$i18nType;
    }
    
    /**
     * Set i18n type
     *
     * @param string $i18nType
     */
    public static function setI18nType( $i18nType ) {
        self::$i18nType = $i18nType;
    }
    
    /**
     * Add one plugin path. MyFuses will be search plugins in this paths if
     * no path was informed. 
     *
     * @param string $path
     */
    protected function addPluginPath( $path ) {
        $this->pluginPaths[] = $path;
    }
    
    /**
     * Return all plugin paths
     *
     * @return array
     */
    public function getPluginPaths() {
        return $this->pluginPaths;
    }
    
    /**
     * Add one i18n path to myfuses
     *
     * @param string $path
     */
    protected function addI18nPath( $path ) {
        $this->i18nPaths[] = $path;
    }
    
    /**
     * Return all i18n paths
     *
     * @return array
     */
    public function getI18nPaths() {
        return $this->i18nPaths;
    }
    
    /**
     * Add one verb path. MyFuses will be search verbs in this paths if
     * no path was informed. 
     *
     * @param string $path
     */
    protected function addVerbPath( $path ) {
        $this->verbPaths[] = $path;
    }
    
    /**
     * Return all verb paths
     *
     * @return array
     */
    public function getVerbPaths() {
        return $this->verbPaths;
    }
    
    /**
     * Enable/disable the memcache feature
     *
     * @param boolean $enable
     */
    public function enableMemcache( $enable ) {
        $this->memcacheEnabled = $enable;
    }
    
    /**
     * Add a memcache server to controller
     *
     * @param MyFusesMemcacheServer $server
     */
    public function addMemcacheServer( MyFusesMemcacheServer $server ) {
        $this->memcaheServers[] = $server;
    }
    
    /**
     * Return all memcache servers
     *
     * @return array
     */
    private function getMemcacheServers() {
        return $this->memcaheServers;
    }
    
    /**
     * Return the memcache object
     *
     * @return Memcache
     */
    public function getMemcache() {
        return $this->memcache;
    }
    
    /**
     * Set the memcache object
     *
     * @param Memcache $memcache
     */
    private function setMemcache( Memcache $memcache ) {
        $this->memcache = $memcache;
    }
    
    /**
     *  Add servers to mencache object
     */
    private function configureMemcache() {
        if( is_null( $this->getMemcache() ) ) {
            $this->setMemcache( new Memcache() );
        }
        
        foreach( $this->getMemcacheServers() as $server ) {
            $server->configureMemcache( $this->getMemcache() );
        }
    }
    
    /**
     * Return if the memcache is enabled
     *
     * @return boolean
     */
    public function isMemcacheEnabled() {
        return $this->memcacheEnabled;
    }
    
    public function getParsedPath() {
        return $this->parsedPath;
    }
    
    protected function setParsedPath( $parsedPath ) {
        $this->parsedPath = $parsedPath;
    }
    
    protected function getApplicationClass() {
        return $this->applicationClass;
    }
    
    protected function setApplicationClass( $appClass ) {
        $this->applicationClass = $appClass;
    }
    
    public function createApplication( 
        $appName = Application::DEFAULT_APPLICATION_NAME, 
        $appReference = null ) {
            
        $appClass = $this->getApplicationClass();
            
        $application = new $appClass( $appName );
        
        if( !is_null( $appReference ) ) {
            if( isset( $appReference[ 'path' ] ) ) {
                $application->setPath( $appReference[ 'path' ] );
            }
            if( isset( $appReference[ 'file' ] ) ) {
                $application->setFile( $appReference[ 'file' ] );
            }
            
        }
        else {
			$application->setPath( dirname( str_replace( "/", DIRECTORY_SEPARATOR, $_SERVER[ 'SCRIPT_FILENAME' ] ) ) );    
        }
        
        // setting parsed path
        $application->setParsedPath( $this->getParsedPath() . 
            $application->getName() . DIRECTORY_SEPARATOR ) ;
        
        $this->addApplication( $application );
        
        return $application;
    }
    
    /**
     * Returns an existing application
     *
     * @param string $name
     * @return Application
     */
    public static function getApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        
        if( isset( self::getInstance()->applications[ $name ] ) ) {
            return self::getInstance()->applications[ $name ];   
        }
        
        $params = array( "applicationName" => $name );
        throw new MyFusesApplicationException( $params, 
            MyFusesApplicationException::NON_EXISTENT_APPLICATION );
    }
    
    /**
     * Returns if the application exisits
     *
     * @param string $name
     * @return boolean
     */
    public static function hasApplication( $name ) {
        try {
            self::getApplication( $name );
            return true;
        }
        catch( MyFusesApplicationException $mfae ) {
            return false;
        }
    }
    
    /**
     * Returns an array of registered applications
     *
     * @return array
     */
    public function &getApplications() {
        return $this->applications;
    }
    
    /**
     * Add one application to controller
     *
     * @param Application $application
     */
    public function addApplication( Application $application ) {
        if( count( $this->applications ) == 0 ) {
            $application->setDefault( true );
        }
        
        $application->setController( $this );
        
        $this->applications[ $application->getName() ] = $application;
        
        $application->setController( $this );
        
        if( Application::DEFAULT_APPLICATION_NAME != $application->getName() ) {
            if( $application->isDefault() ) {
                if( isset( $this->applications[ 
                    Application::DEFAULT_APPLICATION_NAME ] ) ) {
                    $this->applications[ 
                    Application::DEFAULT_APPLICATION_NAME ]->setDefault( 
                        false );
                }
                $this->applications[ Application::DEFAULT_APPLICATION_NAME ] =
                    &$this->applications[ $application->getName() ];
            }
                
        }
    }
    
    
    protected function createRequest() {
        $this->request = new FuseRequest();
    }
    
    public function getCurrentPhase() {
        return MyFusesLifecycle::getPhase();
    }
    
    public function setCurrentPhase( $phase ) {
        MyFusesLifecycle::setPhase( $phase );
    }
    
    public function getCurrentCircuit() {
        return MyFusesLifecycle::getAction()->getCircuit();
    }
    
    public function getCurrentAction() {
        return MyFusesLifecycle::getAction();
    }
    
    public function setCurrentAction( $fuseaction ) {
        list( $appName, $cName, $aName ) = explode( ".", $fuseaction );
        MyFusesLifecycle::setAction( $this->getApplication( $appName )->
            getCircuit( $cName )->getAction( $aName ) );
    }
    
    public function setCurrentProperties( $phase, $fuseaction ) {
        $this->setCurrentPhase( $phase );
        $this->setCurrentAction( $fuseaction );
    }
    
    /**
     * Return controller debugger
     *
     * @return MyFusesDebugger
     */
    public function getDebugger() {
        return $this->debugger;
    }
    
    /**
     * Returns the current request
     * 
     * @return FuseRequest
     */
    public function getRequest() {
        return $this->request;
    }
    
    public function createApplicationPath( Application $application ) {
        if( !file_exists( $application->getParsedPath() ) ) {
            mkdir( $application->getParsedPath(), 0777, true );
         
            $path = explode( DIRECTORY_SEPARATOR, 
                substr( $application->getParsedPath(), 0, 
                strlen( $application->getParsedPath() ) - 1 ) );
         
            while( $this->getParsedPath() != ( 
                implode( DIRECTORY_SEPARATOR, $path ) . 
                DIRECTORY_SEPARATOR ) ) {
                chmod( implode( DIRECTORY_SEPARATOR, $path ), 
                    0777 );
                $path = array_slice( $path, 0, count( $path ) - 1 );
            }
        }
    }
    
    protected function storeApplication( Application $application ) {
        $strStore = "";
        
        if( $application->mustStore() ) {
            if( !$this->isMemcacheEnabled() ) {
                
                $this->createApplicationPath( $application );
                
                $strStore .= $application->getCachedCode();
                
                $fileName = $application->getCompleteCacheFile();
                
                MyFuses::getInstance()->getDebugger()->registerEvent( 
                    new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                        "Application " . 
                        $application->getName() . " Stored" ) );
                
                MyFusesFileHandler::writeFile( $fileName, "<?php\n" . 
    	            $strStore );
            }
            else {
                $this->getMemcache()->set( $application->getTag(), 
                    serialize( $application->getLoader()->
                    getCachedApplicationData() ) );
            }
        }
        
        foreach( $application->getCircits() as $circuit ) {
            if( $circuit->getName() !== "MYFUSES_GLOBAL_CIRCUIT" ) {
                if( $circuit->isLoaded() ) {
                      
                    $strCircuitStoreData = "";
                    
                    $strCircuitStoreData = $circuit->getCachedCode();
                    
                    $fileName = $circuit->getCompleteCacheFile();
                    
                    MyFusesFileHandler::writeFile( $fileName, "<?php\n" . 
                        $strCircuitStoreData );
                    
                }
            }
        }
        
    }
    
    /**
     * Sotore all myfuses applications
     */
    protected function storeApplications() {
        foreach( $this->applications as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                $this->storeApplication( $application );
            }
        }
    }
    
    /**
     * This method parse the request and write the genereted 
     * string in one file
     */
    public function parseRequest() {
        
        $circuit = $this->request->getAction()->getCircuit();
        
        $controllerName = $circuit->getApplication()->getControllerClass();
        
        $application = $circuit->getApplication();
        
        $path = $this->request->getApplication()->getParsedPath() .
	        $this->request->getCircuitName() . DIRECTORY_SEPARATOR;
        
        $fileName = $path . $this->request->getActionName() . ".action.php" ;
        
        // TODO handle file parse
        if( !is_file( $fileName ) || $circuit->isModified() ) {
            $fuseQueue = $this->request->getFuseQueue();
            
            $myFusesString = $controllerName . "::getInstance()";
        
	        $actionString = "\"" . $this->request->getApplication()->getName() . 
	            "." . $this->request->getCircuitName() . 
	            "." . $this->request->getActionName() . "\"";
            
            $strParse = "";
	        
            $strParse .= $myFusesString . "->setCurrentProperties( \"" . 
		        MyFusesLifecycle::PRE_PROCESS_PHASE . "\", "  . 
                $actionString . " );\n\n";
	        
            // parsing pre process plugins
            if( count( $application->getPlugins( 
                Plugin::PRE_PROCESS_PHASE ) ) ) {
                $pluginsStr = $controllerName . 
                    "::getInstance()->getApplication( \"" . 
                    $application->getName() . "\" )->getPlugins(" .
                    " \"" . Plugin::PRE_PROCESS_PHASE . "\" )";
                $strParse .= "foreach( " . $pluginsStr . " as \$plugin ) {\n";
                $strParse .= "\t\$plugin->run();\n}\n";
                $strParse .= "foreach( MyFusesContext::getContext() as " . 
                    " \$key => \$value ) {global \$\$value;}\n\n";
            }
            //end parsing pre process plugins
            
            
            
    		$strParse .= "\$strContent = \"text/html; charset=\" . " . $controllerName . 
    			"::getInstance()->getRequest()->getApplication()->getCharacterEncoding();\n";
    
	    	$strParse .= "\$strContent = \"<meta http-equiv=\\\"Content-Type\\\" content=\\\" . \$strContent . \\\">\";\n";
			
	   		$strParse .= "header( \"Content-Type: \" . \$strContent );\n\n";
            
            foreach( $fuseQueue->getPreProcessQueue() as $parseable ) {
                $strParse .= $parseable->getParsedCode( 
                    $this->request->getApplication()->isParsedWithComments(), 
	                0 );
	        }
	
	        foreach( $fuseQueue->getProcessQueue() as $parseable ) {
	            $strParse .= $parseable->getParsedCode( 
                    $this->request->getApplication()->isParsedWithComments(),
                    0 );
	        }
	        
	        $strParse .= $myFusesString . "->setCurrentProperties( \"" . 
		        MyFusesLifecycle::POST_PROCESS_PHASE . "\", "  . 
                $actionString . " );\n\n";
	        
            $selector = true;
                
            foreach( $fuseQueue->getPostProcessQueue() as $parseable ) {
	            
                
	            $strParse .= $parseable->getParsedCode( 
                    $this->request->getApplication()->isParsedWithComments(), 
                    0 );
	        }
	        
	        // parsing post process plugins
            if( count( $application->getPlugins( 
                Plugin::POST_PROCESS_PHASE ) ) ) {
                $strParse .= $myFusesString . "->setCurrentProperties( \"" . 
                        MyFusesLifecycle::POST_PROCESS_PHASE . "\", "  . 
                        $actionString . " );\n\n";
                $pluginsStr = $controllerName . 
                    "::getInstance()->getApplication( \"" . 
                    $application->getName() . "\" )->getPlugins(" .
                    " \"" . Plugin::POST_PROCESS_PHASE . "\" )";
                $strParse .= "foreach( " . $pluginsStr . " as \$plugin ) {\n";
                $strParse .= "\t\$plugin->run();\n}\n\n";
            }
            //end parsing post process plugins
            
            $this->createApplicationPath( $application );
            
            if( !file_exists( $path ) ) {
	            mkdir( $path );
	            chmod( $path, 0777 );
	        }
	        
	        MyFusesFileHandler::writeFile( $fileName, "<?php\n" . 
	           MyFusesContext::sanitizeHashedString( $strParse ) );
	        
	        MyFuses::getInstance()->getDebugger()->registerEvent( 
                new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                    "Fuseaction " . 
                    $this->getRequest()->getFuseActionName() . " Compiled" ) );
	        
        }
        
        MyFusesContext::includeFile( $fileName );
    }
    
    public static function includeFile( $file ) {
        include $file;
    }
    
    private function configureApplications() {
        foreach( $this->getApplications() as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                $this->configureApplication( $application );
            }
        }
    }
    
    protected function configureApplication( Application $application ) {}
    
    /**
     * Process the user request
     */
    public function doProcess() {
        try {
        	
            MyFusesLifecycle::configureLocale();
            
            if( $this->isMemcacheEnabled() ) {
                $this->configureMemcache();
            }
            
            // initilizing application if necessary
            MyFusesLifecycle::loadApplications();
            
            MyFusesLifecycle::buildApplications();            
            
            MyFusesLifecycle::enableTools();
            
            $this->createRequest();
            
            $this->configureApplications();
            
            $this->parseRequest();
            
            MyFuses::getInstance()->getDebugger()->registerEvent( 
                new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                    "Request completed" ) );
            
            // storing all applications if necessary
            $this->storeApplications();
                
            MyFusesLifecycle::storeLocale();
            
            if( $this->getRequest()->getApplication()->isDebugAllowed() ) {
                print $this->getDebugger();    
            }
        }
        catch( MyFusesException $mfe ) {
            $mfe->breakProcess();
        }
    }
    
    
    /**
     * Returns one instance of MyFuses. Only one instance is creted per process.
     * MyFuses is implemmented using the singleton pattern.
     * 
     * @return MyFuses
     * @static 
     */
     public static function getInstance() {
        
        if( is_null( self::$instance ) ) {
            self::$instance = new MyFuses();
        }
        
        return self::$instance;
    }
    
    public static function getXfa( $name ) {
        return self::getInstance()->getRequest()->getAction()->getXfa( $name );
    }
    
    public static function getSelfPath() {
        
        $self = "http://" . $_SERVER[ 'HTTP_HOST' ];
        
        $self .= "/";
        
        if( substr( $self, -1 ) == "/" ) {
            $self = substr( $self, 0, strlen( $self ) - 1 );
        }
        
        $self .= dirname( $_SERVER[ 'PHP_SELF' ] );
        
        if( substr( $self, -1 ) != "/" ) {
            $self .= "/";
        }
        
        return $self;
    }
    
    public static function getSelf() {
        $self = "http://" . $_SERVER[ 'HTTP_HOST' ];
        
        if( substr( $self, -1 ) != "/" ) {
            $self .= "/";
        }
        
        if( isset( $_SERVER[ 'REDIRECT_STATUS' ] ) ) {
            $self1 = dirname( $_SERVER[ 'SCRIPT_NAME' ] );
            if( substr( $self1, -1 ) != "/" ) {
                $self1 .= "/";
            }
        }
        else {
            $self1 = $_SERVER[ 'SCRIPT_NAME' ];
        }
        
        if( substr( $self1, 0, 1 ) == "/" ) {
            $self1 = substr( $self1, 1, strlen( $self1 ) );
        }
        
        $self .= $self1;
        
        return $self;
    }
    
    public static function getMySelf( $showFuseactionVariable=true ) {
        if( isset( $_SERVER[ 'REDIRECT_STATUS' ] ) ) {
            $mySelf = self::getSelf();
            if( $showFuseactionVariable ) {
                $mySelf .= self::getInstance()->getRequest()->
                    getApplication()->getFuseactionVariable() . "/";    
            }
        }
        else {
            $mySelf = self::getSelf() . "?";
        
            $mySelf .= self::getInstance()->getRequest()->
                getApplication()->getFuseactionVariable();
            $mySelf .= "=" ;    
        }
        
        return $mySelf;
    }
    
    public static function getMySelfXfa( $xfaName, $initQuery = false, 
        $showFuseactionVariable=true ) {
        if( isset( $_SERVER[ 'REDIRECT_STATUS' ] ) ) {
            $link = self::getMySelf( $showFuseactionVariable ) . 
                implode( "/", explode( ".", self::getXfa( $xfaName ) ) );
            if( $initQuery ) {
                $link .= "?";
            }
        }
        else {
            $link = self::getMySelf() . self::getXfa( $xfaName );
            if( $initQuery ) {
                $link .= "&";
            }    
        }
        return $link;
    }
    
    public static function doAction( $actionName ) {
        $actionNameX = explode( ".", $actionName );
        if( count( $actionNameX ) < 3 ) {
            array_unshift( $actionNameX, 
                MyFuses::getInstance()->getApplication()->getName() );
        }
        
        $application = MyFuses::getInstance()->getApplication( 
            $actionNameX[ 0 ] );
        
        $circuit = $application->getCircuit( $actionNameX[ 1 ] );
            
        $action =  $circuit->getAction( $actionNameX[ 2 ] );
        require_once "myfuses/core/verbs/DoVerb.class.php";        
        DoVerb::doAction( $action );
    }
    
    /**
     * Includes core files.<br>
     * Throws IFBExeption when <code>file doesn't exists</code>.
     * In truth this method use require_once insted include_once.
     * Process must break if some core file doesn't exists.
     * 
     * @param $file
     * @return void
     */
    public static function includeCoreFile( $file ) {
        if ( file_exists( $file ) ) {
            require_once $file;
        }
        else {
            throw new MyFusesMissingCoreFileException( $file );
        }
    }
    
    public static function sendToUrl( $url ) {
        if( !headers_sent() ) {
            header( "Location: " . $url );
        }
	    else {
	        echo '<script type="text/javascript">';
	        echo 'window.location.href="'.$url.'";';
	        echo '</script>';
	        echo '<noscript>';
	        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
	        echo '</noscript>';
	        die();
	    }
    }
    
}

/**
 * This is an alias function to MyFuses::getMySelfXfa method.
 *
 * @param string $xfaName
 * @param boolean $initQuery
 * @param boolean $showFuseactionVariable
 * @return string
 */
function xfa( $xfaName, $initQuery = false, $showFuseactionVariable=true ) {
    return MyFuses::getMySelfXfa( $xfaName, $initQuery, 
        $showFuseactionVariable );
}

class MyFusesMemcacheServer {
    
    private $host;
    
    private $port;
    
    private $persistent;
    
    /**
     * Server constructor
     *
     * @param string $host
     * @param string $port
     * @param boolean $persistent
     */
    public function __construct( $host = null, $port = "11211", 
        $persistent = false ) {
        $this->setHost( $host );
        $this->setPort( $port );
        $this->setPersistent( $persistent );
    }
    
    public function getHost() {
        return $this->host;
    }
    
    public function setHost( $host ) {
        $this->host = $host;
    }
    
    public function getPort() {
        return $this->port;
    }
    
    public function setPort( $port ) {
        $this->port = $port;
    }
    
    public function isPersistent() {
        return $this->persistent;
    }
    
    public function setPersistent( $persistent ) {
        $this->persistent = $persistent;
    }
    
    public function configureMemcache( Memcache $memcache ) {
        $memcache->addServer( $this->getHost(), $this->getPort(), 
            $this->isPersistent() );
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */