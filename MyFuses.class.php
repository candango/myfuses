<?php   
/**
 * MyFuses - MyFuses.class.php
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
 * The Original Code is myFuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2009.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   controller
 * @package    myfuses
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */
 
/**
 * The MYFUSES_ROOT_PATH constant defines de framework root path and helps map
 * other important directories like parsed path and application path.
 * 
 * @var string The myFuses root path
 */
 define( "MYFUSES_ROOT_PATH", dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
 
// Including core parts
require_once MYFUSES_ROOT_PATH . "core/Application.class.php";
require_once MYFUSES_ROOT_PATH . "core/Circuit.class.php";
require_once MYFUSES_ROOT_PATH . "core/CircuitReference.class.php";
require_once MYFUSES_ROOT_PATH . "core/ClassDefinition.class.php";
require_once MYFUSES_ROOT_PATH . "core/FuseAction.class.php";
require_once MYFUSES_ROOT_PATH . "core/Plugin.class.php";

// Including engine parts
require_once MYFUSES_ROOT_PATH . "engine/MyFusesLoader.class.php";

// Including process parts
require_once MYFUSES_ROOT_PATH . "process/MyFusesRequestRouter.class.php";
require_once MYFUSES_ROOT_PATH . "process/MyFusesRequest.class.php";
require_once MYFUSES_ROOT_PATH . "process/MyFusesLifecycle.class.php";

// Including uitlities parts
require_once MYFUSES_ROOT_PATH . "util/file/MyFusesFileHandler.class.php";
 
/**
 * MyFuses - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2009 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class MyFuses {
    
    /**
     * The MYFUSES_ROOT_PATH constant defines de framework root path and 
     * helps map other important directories like parsed path and 
     * application path.
     * 
     * @var string The myFuses root path
     */
    const MYFUSES_ROOT_PATH = MYFUSES_ROOT_PATH;
    
    /**
     * Registered applications in the controller
     * 
     * @var array Array of registered applications
     */
    protected $applications = array();
    
    /**
     * The request resolved by the process
     * 
     * @var MyFusesRequest
     */
    protected $request;
    
    /**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
    private static $instance;
    
    /**
     * Path used to search the plugin to be included
     *
     * @var array
     */
    private $pluginPaths = array();
    
    /**
     * The stored application file extension
     * 
     * @var String
     */
    protected $storedApplicationFileExtension = ".application.myfuses.php";
    
    /**
     * Default constructor. It is to implement singleton pattern.
     */
    private function __construct() {
        // adding plugin paths
        $this->addPluginPath( "plugins" . DIRECTORY_SEPARATOR );
        $this->addPluginPath( self::MYFUSES_ROOT_PATH . "plugins" . 
            DIRECTORY_SEPARATOR );
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
        else {
            if( $application->getName() == 
                $this->getApplication()->getName() ) {
                $application->setDefault( true );
            }
        }
        
        $this->applications[ $application->getName() ] = $application;
        
        if( Application::DEFAULT_APPLICATION_NAME != $application->getName() ) {
            if( $application->isDefault() ) {
                if( isset( $this->applications[ 
                    Application::DEFAULT_APPLICATION_NAME ] ) && 
                    ( $application->getName() != 
                    $this->getApplication()->getName() ) ) {
                    $this->applications[ 
                    Application::DEFAULT_APPLICATION_NAME ]->setDefault( 
                        false );
                }
                $this->applications[ Application::DEFAULT_APPLICATION_NAME ] =
                    &$this->applications[ $application->getName() ];
            }  
        }
    }
    
    /**
     * Create one application by a given name
     * 
     * @return Application
     */
    public function createApplication( $name = 
        Application::DEFAULT_APPLICATION_NAME ) {

        $application = MyFusesLifecycle::restoreApplication( $name );
        
        if( $application === null ) {
            $application = new BasicApplication();
            
            $application->setName( $name );
            
            $application->setPath( 
               dirname( str_replace( "/", DIRECTORY_SEPARATOR, 
               $_SERVER[ 'SCRIPT_FILENAME' ] ) ) );
        }
        
        $this->addApplication( $application );
        
        return $this->getApplication( $name );
    }
    
    /**
     * Returns an existing application by a given name if exists
     *
     * @param string $name
     * @return Application The application founded
     */
    public function getApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) { 
        if( isset( $this->applications[ $name ] ) ) {
            return $this->applications[ $name ];
        }
    }
    
    /**
     * Returns an array of all applications registered in the controller
     *
     * @return array An array of applications
     */
    public function &getApplications() { 
        return $this->applications;
    }
    
    /**
     * Return the request registed in controller
     * 
     * @return MyFusesRequest
     */
    public function getRequest() {
        return $this->request;
    }
    
    /**
     * Set on request in the controller
     * 
     * @param MyFusesRequest $request
     */
    public function setRequest( MyFusesRequest $request ) {
        $this->request = $request;
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
     * Return all plugin paths registed
     *
     * @return array
     */
    public function getPluginPaths() {
        return $this->pluginPaths;
    }
    
    /**
     * Execute the myFuses process
     */
    public function doProcess() {
        try {
            MyFusesLifecycle::loadApplications( $this );
            
            MyFusesLifecycle::createRequest( $this );
            
            MyFusesLifecycle::executeProcess( $this );
            
            MyFusesLifecycle::storeApplications( $this );
        }
        catch( MyFusesException $mfe ) {
            $mfe->breakProcess();
        }
        
    }
    
    /**
     * Returns one instance of MyFuses. Only one instance is creted per requrest.
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
    
    /**
     * Return the root path where the parsed files stored
     * 
     * @return String
     */
    public function getParsedRootPath() {
        $rootParsedPath = "";
        
        if( file_exists( MYFUSES_ROOT_PATH . "parsed" ) && 
            is_readable( MYFUSES_ROOT_PATH . "parsed" ) ) {
            return MyFusesFileHandler::sanitizePath( 
                MYFUSES_ROOT_PATH . "parsed" );
        }
        else {
            return DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR;
        }
    }
    
    /**
     * Returns the file extension of the stored application file
     * 
     * @return String
     */
    public function getStoredApplicationFileExtension() {
        return $this->storedApplicationFileExtension;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */