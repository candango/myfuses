<?php
/**
 * Application  - Application.class.php
 * 
 * In this file are difined the basic applications infrastructure with 
 * Application interface AbstactApplication that implements the basic features
 * demanded Application and the BasicApplication an AbstractApplication child. 
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
 * The Original Code is MyFuses "a Candango implementation of Fusebox 
 * Corporation Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2009 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:Application.class.php 23 2007-01-04 13:26:33Z piraz $
 */

require_once MYFUSES_ROOT_PATH . "core/AbstractApplication.class.php";
require_once MYFUSES_ROOT_PATH . "core/BasicApplication.class.php";

/**
 * This is the MyFuses application interface. Defines how an application must
 * be implemented.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2009 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 23
 */
interface Application {

	/**
     * Default applicatication name
     * 
     * @var string
     * @static 
     * @final
     */
    const DEFAULT_APPLICATION_NAME = "default";
	
    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     */
    public function isDefault();
    
    /**
     * Set if the application is default or not
     * 
     * @param boolean $default
     */
    public function setDefault( $default );
    
	/**
     * Returns the application name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Sets the application name
     *
     * @param string $name
     */
    public function setName( $name );
	
	/**
     * Returns the application path
     *
     * @return string
     */
    public function getPath();
    
    /**
     * Sets the application path
     *
     * @param string $path
     */
    public function setPath( $path );
	
	/**
	 * Return the parsed path.
	 * 
	 * @return string
	 */
	public function getParsedPath();
	
	public function getParsedApplicationFile();
	
	#####################
    // COLLECTION METHODS
    #####################
    
	public function getReferences();
	
	public function getReference( $name );
	
	public function addReference( CircuitReference $reference );
	
	#########################
    // END COLLECTION METHODS
    #########################
	
	##################
    // PROCESS METHODS
    ##################
	
	/**
     * Returns if the application is started or not
     * 
     * @return boolean
     */
    public function isStarted();
    
    /**
     * Set if the application is started or not
     * 
     * @param boolean $value
     */
    public function setStarted( $started );
	
    public function getStartTime();
    
	/**
	 * Will fire the onApplicationStart event
	 */
	public function fireApplicationStart();
	
	/**
	 * Will fire the onPreProcess event
	 */
	public function firePreProcess();
	
	/**
	 * Will fire the onPostProcess event
	 */
	public function firePostProcess();
	
	######################
    // END PROCESS METHODS
    ######################
	
	#################################
	// METHODS DIFINED IN myfuses.xml
	#################################
	
	/**
     * Return application locale
     *
     * @return string
     */
    public function getLocale();
    
    /**
     * Set application locale
     *
     * @param string $locale
     */
    public function setLocale( $locale );
    
    /**
     * Return if the degug is alowed
     *
     * @return boolean
     */
    public function isDebugAllowed();
    
    /**
     * Set application debug flag
     *
     * @param boolean $debug
     */
    public function setDebug( $debug );
	
	/**
     * Return the fuseaction variable
     * 
     * @return string
     * @access public 
     */
    public function getFuseactionVariable();
    
    /**
     * Set the fusaction variable
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setFuseactionVariable( $fuseactionVariable );
    
    /**
     * Return the default fuseaction
     * 
     * @return string
     * @access public 
     */
    public function getDefaultFuseaction();
    
    /**
     * Set the defautl fuseaction
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setDefaultFuseaction( $defaultFuseaction );
    
    /**
     * Return precedence form or url
     * 
     * @return string
     * @access public 
     */
    public function getPrecedenceFormOrUrl();
    
    /**
     * Set precedence form or url
     * 
     * @param string $precedenceFormOrUrl
     * @access public
     */
    public function setPrecedenceFormOrUrl( $precedenceFormOrUrl );
    
    /**
     * Return the application mode
     * 
     * @return string
     * @access public 
     */
    public function getMode();
    
    /**
     * Set the application mode
     * 
     * @param string $mode
     * @access public
     */
    public function setMode( $mode );
    
    /**
     * Return the fusebox sctricMode
     * 
     * @return boolean
     * @access public 
     */
    public function isStrictMode();
    
    /**
     * Set the fusebox strictMode
     * 
     * @param boolean $strictMode
     * @access public
     */
    public function setStrictMode( $strictMode );
    
    /**
     * Return application password
     * 
     * @return string
     * @access public
     */
    public function getPassword();
    
    /**
     * Set the application password
     * 
     * @param $password
     * @access public
     */
    public function setPassword( $password );
    
    /**
     * Return if application must be parsed with comments
     * 
     * @return boolean
     */
    public function isParsedWithComments();
    
    /**
     * Set if application must be parsed with comments
     *
     * @param boolean $parsedWithComments
     */
    public function setParsedWithComments( $parsedWithComments );
    
    /**
     * Return if application is using conditional parse
     * 
     * @return boolean
     */
    public function isConditionalParse();
    
    /**
     * Set if application is using conditional parse
     * 
     * @param boolean $conditionalParse
     */
    public function setConditionalParse( $conditionalParse );
	
    /**
     * Return if the tools application is allowed
     *
     * @return boolean
     */
    public function isToolsAllowed();
    
    /**
     * Set application tools flag
     *
     * @param boolean $tools
     */
    public function setTools( $tools );
    
    #####################################
	// END METHODS DIFINED IN myfuses.xml
	#####################################
	
}