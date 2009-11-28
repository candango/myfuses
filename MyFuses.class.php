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
require_once MYFUSES_ROOT_PATH . "core/CircuitReference.class.php";

// Including myfuses parts
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
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
    private static $instance;
    
    /**
     * The stored application file extension
     * 
     * @var String
     */
    protected $storedApplicationFileExtension = ".application.myfuses.php";
    
    /**
     * Default constructor. It is to implement singleton pattern.
     */
    private function __construct() {}
    
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
        
        return $application;
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