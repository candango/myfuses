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
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

define( "MYFUSES_ROOT_PATH", dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

require_once MYFUSES_ROOT_PATH . "exception/MyFusesException.class.php";

try {
    MyFuses::includeCoreFile( MyFuses::ROOT_PATH . 
        "core/Application.class.php" );
    MyFuses::includeCoreFile( MyFuses::ROOT_PATH . 
        "core/ICacheable.class.php" );
    MyFuses::includeCoreFile( MyFuses::ROOT_PATH . 
        "core/IParseable.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::ROOT_PATH . 
        "engine/AbstractMyFusesLoader.class.php" );
    MyFuses::includeCoreFile( MyFuses::ROOT_PATH . 
        "engine/loaders/XMLMyFusesLoader.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::ROOT_PATH . 
        "process/FuseRequest.class.php" );
   MyFuses::includeCoreFile( MyFuses::ROOT_PATH . 
        "process/MyFusesLifecycle.class.php" );
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
    const ROOT_PATH = MYFUSES_ROOT_PATH;
    
    /**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
    private static $instance;
    
    /**
     * Array of registered applications
     * 
     * @var array
     */
    private $applications = array();
    
    /**
     * MyFuses loader instance
     * 
     * @var MyFusesLoader
     */
    private $loader;
    
    /**
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
     * MyFuses constructor
     *
     * @param MyFusesLoader $loader
     * @param string $applicationName
     */
    protected function __construct( 
        $appName = Application::DEFAULT_APPLICATION_NAME, 
        MyFusesLoader $loader = null ) {
        
        // FIXME each application must have its own loader
        if( is_null( $loader ) ) {
            $loader = 
                AbstractMyFusesLoader::getLoader( MyFusesLoader::XML_LOADER );
        }
        
        $this->loader = $loader;
        
        $this->applications[ $appName ] = new Application( $appName );
        
        $this->applications[ $appName ]->setDefault( true );
        
        $this->applications[ $appName ]->setPath( 
            dirname( $_SERVER[ 'SCRIPT_FILENAME' ] ) );
        
        // FIXME this part isn't working fine
        if( Application::DEFAULT_APPLICATION_NAME != $appName ) {
            $this->applications[ Application::DEFAULT_APPLICATION_NAME ] =
                &$this->applications[ $appName ];
        }
        
    }
    
    /**
     * Returns an existing application
     *
     * @param string $name
     * @return Application
     */
    public function &getApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        return $this->applications[ $name ];
    }
    
    /**
     * Returns an array of registered applications
     *
     * @return array
     */
    public function &getApplications() {
        return $this->applications;
    }
    
    public function addApplication( Application $application ) {
        $this->applications[ $application->getName() ] = $application;
        
        if( $application->isDefault() ) {
            $this->applications[ 
                Application::DEFAULT_APPLICATION_NAME ] = $application; 
        }
    }
    
    /**
     * Sets the application $name
     * 
     * @param string
     */
    public function setApplicationName( $value, 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        return $this->applications[ $name ]->setName( $value );
    }
    
    /**
     * Returns the application $path
     * 
     * @return string
     */
    public function getApplicationPath( 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        $this->applications[ $name ]->getPath();
    }
    
    /**
     * Sets the application path
     * 
     * @param string $name
     */
    public function setApplicationPath( $value, 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        $this->applications[ $name ]->setPath( $value );
    }
    
    
    /**
     * Loads all applications registered
     */
    private function loadApplications() {
         foreach( $this->applications as $application ) {
             if( !$application->isLoaded() ) {
                 $this->loader->loadApplication( $application );
             }
         }
    }
    
    protected function createRequest() {
        $this->request = new FuseRequest();
    }
    
    public function getCurrentPhase() {
        return $this->lifecycle->getPhase();
    }
    
    public function setCurrentPhase( $phase ) {
        $this->lifecycle->setPhase( $phase );
    }
    
    public function &getCurrentCircuit() {
        return $this->lifecycle->getAction()->getCircuit();
    }
    
    public function &getCurrentAction() {
        return $this->lifecycle->getAction();
    }
    
    public function setCurrentAction( CircuitAction $action ) {
        $this->lifecycle->setAction( $action );
    }
    
    /**
     * Returns the current request
     * 
     * @return FuseRequest
     */
    public function &getRequest() {
        return $this->request;
    }
    
    /**
     * Sotore all myfuses applications
     */
    protected function storeApplications() {
        
        foreach( $this->applications as $index => $application ) {
            $strStore = "";
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                if( !file_exists( $application->getParsedPath() ) ) {
                    mkdir( $application->getParsedPath() );
                    chmod( $application->getParsedPath(), 0777 );
                }
                
                $strStore = $application->getCachedCode();
                $fileName = $application->getCompleteCacheFile();
                              
	            $fp = fopen( $fileName,"w" );
	        
		        if ( !flock($fp,LOCK_EX) ) {
		            die("Could not get exclusive lock to Parsed File file");
		        }
		        
		        if ( !fwrite($fp, "<?php\n" . $strStore) ) {
		           var_dump( "deu pau 2!!!" );
		        }
		        flock($fp,LOCK_UN);
		        fclose($fp);
		        chmod( $fileName, 0777 );
            }
        }
    }
    
    public function parseRequest() {
        $this->lifecycle = new MyFusesLifecycle();
        
        $circuit = $this->request->getAction()->getCircuit();
        
        $path = $this->request->getApplication()->getParsedPath() .
	        $this->request->getCircuitName() . DIRECTORY_SEPARATOR;
        
        $fileName = $path . $this->request->getActionName() . ".action.php" ;
        
	    // FIXME when the application is modified reparse circuit request
        // TODO handle file parse
        
        if( !is_file( $fileName ) || $circuit->isModified() ) {
        
            $fuseQueue = $this->request->getFuseQueue();
            
            $myFusesString = "MyFuses::getInstance( \"" . 
	            $this->request->getApplication()->getName() . "\" )";
        
	        $actionString = $myFusesString . "->getApplication( \"" . 
	            $this->request->getApplication()->getName() . 
	            "\" )->getCircuit( \"" . 
	            $this->request->getCircuitName() . "\" )->getAction( \"" . 
	            $this->request->getActionName() . "\" )";
            
            
            $strParse = "";
	        
            $strParse .= $myFusesString . "->setCurrentPhase( \"" . 
		        MyFusesLifecycle::PRE_PROCESS_PHASE . "\" );\n\n";
	        
            $strParse .= $myFusesString . "->setCurrentAction( "  . 
                $actionString . " );\n\n";
            
            foreach( $fuseQueue->getPreProcessQueue() as $parseable ) {
	            $strParse .= $parseable->getParsedCode(
	                $this->request->getApplication()->isParsedWithComments(), 0 );
	        }
            
	
	        foreach( $fuseQueue->getProcessQueue() as $parseable ) {
	            $strParse .= $parseable->getParsedCode( 
	                $this->request->getApplication()->isParsedWithComments(), 0 );    
	        }
	        
	        $strParse .= $myFusesString . "->setCurrentPhase( \"" . 
		        MyFusesLifecycle::POST_PROCESS_PHASE . "\" );\n\n";
	        
            $strParse .= $myFusesString . "->setCurrentAction( "  . 
                $actionString . " );\n\n";
	        
            $selector = true;
                
            foreach( $fuseQueue->getPostProcessQueue() as $parseable ) {
	            if( !( $parseable instanceof CircuitAction ) && $selector ){

	                $strParse .= $myFusesString . "->setCurrentPhase( \"" . 
				        MyFusesLifecycle::POST_PROCESS_PHASE . "\" );\n\n";
			        
		            $strParse .= $myFusesString . "->setCurrentAction( "  . 
		                $actionString . " );\n\n";
		            
		            $selector = false;
	                
	            }
                
                $strParse .= $parseable->getParsedCode(
	                $this->request->getApplication()->isParsedWithComments(), 0 );
	        }
	        
            if( !file_exists( $path ) ) {
	            mkdir( $path );
	            chmod( $path, 0777 );
	        }
	        
	        $fp = fopen( $fileName,"w" );
	         
	        if ( !flock($fp,LOCK_EX) ) {
	            die("Could not get exclusive lock to Parsed File file");
	        }
	
	        if ( !fwrite($fp, "<?php\n" . $strParse) ) {
	            var_dump( "deu pau 2!!!" );
	        }
	        flock($fp,LOCK_UN);
	        fclose($fp);
	        chmod( $fileName, 0777 );
        
        }
        include $fileName;
    }
    
    /**
     * Process the user request
     */
    public function doProcess() {
        try {
            // initilizing application if necessary
            $this->loadApplications();
            
            $this->createRequest();
            
            // storing all applications if necessary
            $this->storeApplications();
            
            $this->parseRequest();
        }
        catch( MyFusesFileOperationException $mffoe ) {
            $mffoe->breakProcess();
        }
        
    }
    
    
    /**
     * Returns one instance of MyFuses. Only one instance is creted per process.
     * MyFuses is implemmented using the singleton pattern.
     * // FIXME Tem que repensar esse método dica: tem tudo a ve com aquele problema de um loader por aplicação
     * 
     * @return MyFuses
     * @static 
     */
    public static function &getInstance( 
        $name = Application::DEFAULT_APPLICATION_NAME, 
        MyFusesLoader $loader = null ) {
        
        if( is_null( self::$instance ) ) {
            self::$instance = new MyFuses( $name, $loader );
        }
        
        return self::$instance;
    }
    
    public static function getXfa( $name ) {
        return self::getInstance()->getRequest()->getAction()->getXfa( $name );
    }
    
    public static function getSelf() {
        $self = "http://" . $_SERVER[ 'HTTP_HOST' ];
        
        if( substr( $self, -1 ) != "/" ) {
            $self .= "/";
        }
        
        $self .= str_replace( $_SERVER[ 'DOCUMENT_ROOT' ], 
            "", $_SERVER[ 'SCRIPT_FILENAME' ] );
        
        return $self;
    }
    
    public static function getMySelf() {
        $mySelf = self::getSelf() . "?";
        
        $mySelf .= self::getInstance()->getRequest()->
            getApplication()->getFuseactionVariable();
        $mySelf .= "=" ;
        return $mySelf;
    }
    
    public static function getMySelfXfa( $xfaName ) {
        $link = self::getMySelf() . self::getXfa( $xfaName );
        return $link;
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
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */