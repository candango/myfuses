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
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: MyFuses.class.php 8 2006-08-10 20:44:32Z piraz $
 * @since      Revision 3
 */

define( "MYFUSES_ROOT_PATH", dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

require_once MYFUSES_ROOT_PATH . "exception/MyFusesException.class.php";

// ifbox autoload function
spl_autoload_register( "myfusesAutoLoad" );

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
 * @version    SVN: $Revision: 8 $
 * @since      Revision 3
 * @abstract
 */
class MyFuses {
    
    /**
     * The MyFuses root path constant
     * 
     * @access public
     * @static
     * @final
     */
    const ROOT_PATH = MYFUSES_ROOT_PATH;
    
    /**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     * @access private
     */
    private static $instance;
    
    /**
     * Array of registered applications
     * 
     * @var array
     * @access private
     */
    private $applications = array();
    
    private $appHandler;
    
    /**
     * MyFuses constructor
     *
     * @param string $applicationName
     * @access protected
     */
    protected function __construct( $appName = ""  ) {
        
        $this->appHandler = new ApplicationHandler();
        
        if( $appName == "" ) {
            $appName = Application::DEFAULT_APPLICATION_NAME;
        }
        
        $this->applications[ $appName ] = 
            $this->appHandler->getApplicationInstance( $appName );
        
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
    public function getApplication( $name = "" ) {
        if( $name == "" ) {
            return $this->applications[ Application::DEFAULT_APPLICATION_NAME ];
        }
        
        return $this->applications[ $name ];
    }
    
    /**
     * Returns an array of registered applications
     *
     * @return array
     * @access public
     */
    public function getApplications() {
        return $this->applications;
    }
    
    /**
     * Returns the application name
     * 
     * @return string The application name
     * @access public
     */
    public function getApplicationName( $name = "default" ) {
        return $this->applicationName;
    }
    
    /**
     * Sets the application name
     * 
     * @param string name The application name
     * @access public
     */
    public function setApplicationName( $value, $name = "default" ) {
        return $this->applications[ $name ]->setName( $value );
    }
    
    /**
     * Returns the application path
     * 
     * @return string The application path
     * @access public
     */
    public function getApplicationPath( $name = "default" ) {
        $this->applications[ $name ]->getPath();
    }
    
    /**
     * Sets the application path
     * 
     * @param string name The application path
     * @access public
     */
    public function setApplicationPath( $value, $name = "default" ) {
        $this->applications[ $name ]->setPath( $value );
        $this->applications[ $name ]->setParsedPath( 
            $value . "fusebox/parsed/" );
    }
    
    
    /**
     * Loads all applications registered
     * 
     * @access private
     */
    private function loadApplications() {
         foreach( $this->applications as $application ) {
             if( !$application->isLoaded() ) {
                 $this->loadApplication( $application );
             }
         }
    }
    
    private function loadApplication( Application $application ) {
        $this->appHandler->loadApplication( $application );
        $application->setLoaded( true );
    }
    
    /**
     * Process the user request
     * 
     * @access public
     */
    public function doProcess() {
        try{
            // initilizing application if necessary
            $this->loadApplications();
        }
        catch( MyFusesFileOperationException $mffoe ) {
            $mffoe->breakProcess();
        }
        
    }
    
    
    /**
     * Returns one instance of MyFuses. Only one instance is creted per process.
     * MyFuses is implemmented using the singleton pattern.
     *
     * @return MyFuses
     * @access public
     * @static 
     */
    public static function getInstance( $name = "default" ) {
        if( is_null( self::$instance ) ) {
            self::$instance = new MyFuses( $name );
        }
        return self::$instance;
    }
    
    /**
     * Auto loads class files when they aren't included 
     * 
     * @param string className The class name
     * @access public
     */
    public static function autoLoad( $className ) {
        $classIncludeMap = array(
            'Application' => 'application/',
            'ApplicationHandler' => 'application/',
            'IContextRegisterable' => 'context/'
            );

            try {
                self::includeCoreFile( MyFuses::ROOT_PATH .
                $classIncludeMap[ $className ] . $className . ".class.php" );
            }
            catch( MyFusesMissingCoreFileException $mfmcfe ) {
                $mfmcfe->breakProcess();
            }
    }
    
    /**
     * Includes core files.<br>
     * Throws IFBExeption when <code>file doesn't exists</code>.
     * In truth this method use require_once insted include_once.
     * Process must break if some core file doesn't exists.
     * 
     * @param file File path
     * @access public
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
    
}

/**
 * Fires MyFuses::autoLoad()
 * 
 * @param string className
 * @see MyFuses::autoLoad()
 */
function myfusesAutoLoad( $className ){
    MyFuses::autoLoad( $className );
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */