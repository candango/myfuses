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
 * @copyright  Copyright (c) 2006 - 2009 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: MyFuses.class.php 662 2009-03-11 04:30:31Z flavio.garcia $
 */
define( "MYFUSES_ROOT_PATH", dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

require_once "myfuses/exception/MyFusesException.class.php";

require_once "myfuses/core/Application.class.php";
require_once "myfuses/core/Circuit.class.php";
require_once "myfuses/core/CircuitReference.class.php";
require_once "myfuses/core/ClassDefinition.class.php";

require_once "myfuses/engine/MyFusesLoader.class.php";

require_once "myfuses/process/MyFusesLifecycle.class.php";
require_once "myfuses/process/MyFusesRequest.class.php";

require_once "myfuses/util/Common.classes.php";

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
 * @version    SVN: $Revision: 662 $
 * @since      Revision 17
 */
class MyFuses {
    
	const MYFUSES_ROOT_PATH = MYFUSES_ROOT_PATH;
	
    /**
     * Array of registered applications
     * 
     * @var array
     */
    protected $applications = array();
    
    protected $request;
    
    protected $storedApplicationExtension = ".application.myfuses.php";
    
    /**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
    private static $instance;
    
    public function createApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) {

        $application = MyFusesLifecycle::restoreApplication( $name );
        
        if( $application === null ) {
            $application = new BasicApplication();
	        
	        $application->setName( $name );
	        
	        $application->setPath( 
	           dirname( str_replace( "/", DIRECTORY_SEPARATOR, 
	           $_SERVER[ 'SCRIPT_FILENAME' ] ) ) );
	        
        }
        
        $this->addApplication( $application );
        
        return $application;
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
        
        $this->applications[ $application->getName() ] = $application;
        
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
    
    /**
     * Returns an existing application
     *
     * @param string $name
     * @return Application
     */
    public function getApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) { 
        	
        if( isset( $this->applications[ $name ] ) ) {
            return $this->applications[ $name ];
        }
    }
    
    public function getRequest() {
    	return $this->request;
    }
    
    public function setRequest( MyFusesRequest $request ) {
    	$this->request = $request;
    }
    
    /**
     * Returns an array of registered applications
     *
     * @return array
     */
    public function &getApplications() { 
        return $this->applications;
    }
    
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

    public function getRootParsedPath() {
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
    
    public function getStoredApplicationExtension() {
    	return $this->storedApplicationExtension;
    }
}