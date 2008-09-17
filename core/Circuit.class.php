<?php
/**
 * Circuit - Circuit.class.php
 * 
 * MyFuses Circuit interface
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
 * Portions created by Flavio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:Circuit.class.php 23 2007-01-04 13:26:33Z piraz $
 */

require_once "myfuses/core/Application.class.php";
require_once "myfuses/core/CircuitAction.class.php";

/**
 * Circuit - Circuit.class.php
 * 
 * MyFuses Circuit interface
 * 
 * PHP version 5
 * 
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 48
 */
interface Circuit extends ICacheable {
    
    /**
     * Public Access Constant.<br>
     * Value 1
     * 
     * @var int
     */
    const PUBLIC_ACCESS = 1;
    
    /**
     * Internal Access Constant.<br>
     * Value 2
     * 
     * @var int
     */
    const INTERNAL_ACCESS = 2;
    
    /**
     * Private Access Constant.<br>
     * Value 2
     * 
     * @var int
     */
    const PRIVATE_ACCESS = 3;
    
    /**
     * Return circuit application
     *
     * @return Application
     */
    public function &getApplication();
    
    /**
     * Set circuit application
     * 
     * @param Application $application
     */
    public function setApplication( Application &$application );
    
    /**
     * Return the circuit name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Set the circuit name
     *
     * @param string $name
     */
    public function setName( $name );
    
    /**
     * Return the circuit path
     *
     * @return string
     */
    public function getPath();
    
    /**
     * Return the circuit complete path
     *
     * @return string
     */
    public function getCompletePath();
    
    
    /**
     * Set the circuit path
     *
     * @param string $path
     */
    public function setPath( $path );
    
    /**
     * Return circuit verb paths
     *
     * @return array
     */
    public function getVerbPaths();
    
    /**
     * Return one verb path
     *
     * @param string $name
     * @return string
     */
    public function getVerbPath( $name );
    
    /**
     * Set circui verb paths
     *
     * @param array $verbPaths
     */
    public function setVerbPaths( $verbPaths );
    
    /**
     * Return if a given verbPath exists
     * 
     * @param string $verbPath
     * @return boolean
     */
    public function verbPathExists( $verbPath );
    
    /**
     * Return the circuit access
     *
     * @return integer
     */
    public function getAccess();
    
    /**
     * Return circuit access name
     *
     * @return string
     */
    public function getAccessName();
    
    /**
     * Set the circuit access
     *
     * @param integer $access
     */
    public function setAccess( $access = Circuit::PUBLIC_ACCESS );
    
	/**
     * Set the circuit access using a string
     *
     * @param string $access
     */
    public function setAccessByString( $accessString = "public" );
    
    /**
     * Add one action to circuit
     * 
     * @param Action $action
     */
    public function addAction( Action $action );
    
    /**
     * Return one Circuit by name
     *
     * @param string $name
     * @return FuseAction
     * @throws MyFusesFuseActionException
     */
    public function getAction( $name );
    
    /**
     * 
     */
    public function hasAction( $name );
    
    public function getActions();
    
    /**
     * Enter description here...
     *
     * @return CircuitAction
     */
    public function getPreFuseAction();
    
	/**
	 * Enter description here...
	 *
	 * @param CircuitAction $action
	 */
	public function setPreFuseAction( CircuitAction $action );
    
	public function unsetPreFuseAction();
	
	/**
	 * Enter description here...
	 *
	 * @return CircuitAction
	 */
	public function getPostFuseAction();
    
	/**
	 * Enter description here...
	 *
	 * @param CircuitAction $action
	 */
	public function setPostFuseAction( CircuitAction $action );
    
    public function unsetPostFuseAction();
	
    /**
     * Return the circuit complete file
     * 
     * complete path + file
     *
     * @return string
     */
    public function getCompleteFile();
	
    /**
     * Return the circuit file
     *
     * @return string
     */
    public function getFile();
    
    /**
     * Set the circuit file
     *
     * @param string $file
     */
    public function setFile( $file );
    
	/**
     * Return the application parent name
     *
     * @return string
     * @access public
     */
    public function getParentName();
    
    /**
     * Set the applciation parent name.<br>
     * When parent name is seted the parent reference is seted to null.
     * 
     * @param string $parentName
     * @access public
     */
    public function setParentName( $parentName );
    
    /**
     * Return the application parent
     * 
     * @return Circuit
     * @access public
     */
    public function getParent();
    
    /**
     * Set the application parent
     * 
     * @param Circuit $parent
     * @access public
     */
    public function setParent( Circuit $parent );
    
    /**
     * Return the circuit last load time
     *
     * @return integer
     * @access public
     */
    public function getLastLoadTime();
    
    /**
     * Sets the circuit last load time
     * 
     * @param integer $lastLoadTime
     * @access public
     */
    public function setLastLoadTime( $lastLoadTime );
    
    public function isModified();
    
    public function isLoaded();
    
    public function setLoaded( $loaded );
    
    public function setModified( $modified );
    
    /**
     * Return if circuit was built
     *
     * @return boolean
     */
    public function wasBuilt();
    
    /**
     * Return the circuit cache data
     *
     * @return array
     */
    public function getData();
    
    /**
     * Set circuit cache data
     *
     * @param array $data
     */
    public function setData( $data );
    
    /**
     * Set circuit built status
     *
     * @param boolean $built
     */
    public function setBuilt( $built );
    
    public function setCustomAttribute( $namespace, $name, $value );
    
    public function getCustomAttribute( $namespace, $name );
    
    public function getCustomAttributes( $namespace );
    
    public function getErrorParams();
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */