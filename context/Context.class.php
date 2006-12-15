<?php
/**
 * Context  - Context.class.php
 * 
 * This is the MyFuses context class that offers better control of all 
 * applications.
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
 * @package    myfuses.context
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: Context.class.php 7 2006-08-10 14:03:05Z piraz $
 * @since      Revision 3
 */

/**
 * Context  - Context.class.php
 * 
 * This is the MyFuses context class that offers better control of all 
 * applications.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.context
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 7 $
 * @since      Revision 3
 * @abstract
 */
class Context {
    
    /**
     * Context name
     * 
     * @access private
     */
    private $name = "default";
    
    /**
     * Unique Context instance that must exist
     * 
     * @access private
     */
    private static $instance;
    
    /**
     * The name of current appliaction that context is deal with
     * 
     * @access private
     */
    private $currentApplicationName;
    
    /**
     * Array of apliactions that the context knows
     */
    private $applications = array();
    
    /**
     * The constructor
     * 
     * @access private
     */
    private function __construct() {
        $_SESSION[ 'CONTEXT' ] = $this;
        
        $this->name = MyFuses::getContextName();
        $this->currentApplicationName = MyFuses::getApplicationName();
    }
    
    /**
     *  Singleton method that retrieves a unique Context instance
     * 
     * @return Context A Context instance
     * @access public 
     */
    public static function getInstance() {
    	if ( is_null( self::$instance ) ) {
            self::$instance = new Context();
        }
        return self::$instance;
    }
    
    /**
     * Get one Application by his given name
     * 
     * @param string The Appliaction name
     * @return Application The founded Application or null
     * @access public
     */
    public function &getApplication( $name ) {
    	return $this->applications[ $name ];
    }
    
    /**
     * Adds one application in context
     * 
     * @param Application One Application
     * @access public
     */
    public function addApplication( Application $application ) {
    	$this->applications[ $application->getName() ] = $application;
    }
    
    /**
     * Removes one application by his given name
     * 
     * @param string The Application name
     * @access public
     */
    public function removeApplication( $name ) {
    	
        //TODO throw invalid operation exception
        if( $this->currentApplication->getName() !== $name ) {
    	   $applicationsTemp = $this->applications;
    	}
    }
    
    /**
     * Returns an array of all Application contained in context
     * 
     * @return array An arrray of Application
     * @access public
     */
    public function getApplications() {
    	return $this->applications;
    }
    
    /**
     * Verifies if certian application exists
     * 
     * @param string name The Application name
     * @return boolean A boolean value if the application exists in context
     * @access public
     */
    public function applicationExists( $name ) {
    	if ( is_null( $this->getApplication( $name ) ) ) {
    		return false;
    	}
        return true;
    }
    
    
    /**
     * Returns the application that the context handles in the moment
     * 
     * @return Application An application
     * @access public
     */
    public function &getCurrentApplication() {
    	return $this->getApplication( MyFuses::getApplicationName() );
    }
    
    /**
     * Returns the context name
     * 
     * @return string Context name
     * @access public
     */
    public function getName() {
    	return $this->name;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */