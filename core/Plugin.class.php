<?php
/**
 * Plugin  - Plugin.class.php
 * 
 * This is MyFuses plugin interface. Defines how one interfece must to be.
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
 * @category   controller
 * @package    myfuses.core
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

/**
 * Plugin  - Plugin.class.php
 * 
 * This is MyFuses plugin interface. Defines how one interfece must to be.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 88
 */
interface Plugin extends IParseable {
    
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
     * This is the method that runs plugin action.
     *
     */
    public function run();
}