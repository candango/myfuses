<?php
/**
 * Plugin  - Plugin.class.php
 * 
 * Extensible, that's one of the most important myFuses main feature. One way
 * to make the myFuses extensible is work with plugins. You can plug a small
 * logic in the myFuses process using the plugin framework. There are four
 * phases in the process to be plug some logic: preProcess, postProcess, 
 * preFuseaction and postFuseactions. There are two phases to plug error
 * handling: fuseactionException and processErrror.
 * In this file is difined the basic plugin infrastructure with Plugin 
 * interface. The PluginCircuit class implements the basic features demanded by
 * Plugin and all plugins that the developer will extend the process plugin 
 * or exception plugin.
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
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2010.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: Plugin.class.php 702 2009-04-20 17:49:15Z flavio.garcia $
 */

require_once MYFUSES_ROOT_PATH . "core/ProcessPlugin.class.php";

/**
 * Plugin  - Plugin.class.php
 * 
 * Extensible, that's one of the most important myFuses main feature. One way
 * to make the myFuses extensible is work with plugins. You can plug a small
 * logic in the myFuses process using the plugin framework. There are four
 * phases in the process to be plug some logic: preProcess, postProcess, 
 * preFuseaction and postFuseactions. There are two phases to plug error
 * handling: fuseactionException and processErrror.
 * In this file is difined the basic plugin infrastructure with Plugin 
 * interface. The PluginCircuit class implements the basic features demanded by
 * Plugin and all plugins that the developer will extend the process plugin 
 * or exception plugin.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 702 $
 * @since      Revision 88
 */
interface Plugin { // extends IParseable, ICacheable {
    
	/**
     * Fuseaction exception fase constant<br>
     * Value "fuseactionException"
     * 
     * @var string
     */
    const FUSEACTION_EXCEPTION_PHASE = "fuseactionException";
	
    /**
     * Pre process fase constant<br>
     * Value "preProcess"
     * 
     * @var string
     */
    const PRE_PROCESS_PHASE = "preProcess";
    
    /**
     * Pre fuseaction fase constant<br>
     * Value "preFuseaction"
     * 
     * @var string
     */
    const PRE_FUSEACTION_PHASE = "preFuseaction";
    
    /**
     * Post fuseaction fase constant<br>
     * Value "postFuseaction"
     * 
     * @var string
     */
    const POST_FUSEACTION_PHASE = "postFuseaction";
    
    /**
     * Post process fase constant<br>
     * Value "postProcess"
     * 
     * @var string
     */
    const POST_PROCESS_PHASE = "postProcess";
    
    /**
     * Process error fase constant<br>
     * Value "processError"
     * 
     * @var string
     */
    const PROCESS_ERROR_PHASE = "processError";
    
    /**
     * Return the plugin name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Set the plugin name
     *
     * @param string $name
     */
    public function setName( $name );
    
    /**
     * Return the plugin file
     *
     * @return string
     */
    public function getFile();
    
    /**
     * Set the plugin file
     *
     * @param string $file
     */
    public function setFile( $file );
    
    /**
     * Return the plugin template
     *
     * @return string
     */
    public function getTemplate();
    
    /**
     * Set the plugin template
     *
     * @param string $file
     */
    public function setTemplate( $file );
    
    /**
     * Return the plugin path
     *
     * @return string
     */
    public function getPath();
    
    /**
     * Set the plugin path
     *
     * @param string $path
     */
    public function setPath( $path );
    
    /**
     * Returns the plugin phase
     *
     * @return string
     */
    public function getPhase();
    
    /**
     * Set the application phase
     *
     * @param string $phase
     */
    public function setPhase( $phase );
    
    /**
     * Returns the plugin index
     *
     * @return integer
     */
    public function getIndex();
    
    /**
     * Set the plugin index
     *
     * @param integer $index
     */
    public function setIndex( $index );
    
    /**
     * Return plugin application
     *
     * @return Application
     */
    public function getApplication();
    
    /**
     * Set plugin application
     *
     * @param Application $application
     */
    public function setApplication( Application $application );
    
    /**
     * Clear application plugin
     */
    public function clearApplication();
    
    /**
     * Add one parameter to plugin
     *
     * @param string $name
     * @param string $value
     */
    public function addParameter( $name, $value );
    
    /**
     * Get plugins parameters
     * 
     * @return array An array of paramters
     */
    public function getParameters();
    
    /**
     * Enter description here...
     *
     * @param array $parameters
     */
    public function setParameters( $parameters );
    
    /**
     * Get one parameter by a given name
     * 
     * @return strin The paramter name
     */
    public function getParameter( $name );
    
    /**
     * This is the method that runs plugin action.
     *
     */
    public function run();
}