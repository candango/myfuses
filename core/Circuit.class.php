<?php
/**
 * Circuit - Circuit.class.php
 * 
 * MyFuses Circuit class
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
 * MyFuses Circuit class
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
class Circuit {
    
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
     * Circuit application reference
     *
     * @var Application
     */
    private $application;
    
    /**
     * Circuit name
     *
     * @var string
     */
    private $name;
    
    /**
     * Circuit path
     * 
     * @var string
     */
    private $path;
    
    private $verbPaths = array();
    
    /**
     * Circuit access type
     *
     * @var integer
     */
    private $access;
    
    /**
     * Cicuit actions
     *
     * @var array
     */
    private $actions = array();
    
    private $file;
    
    /**
     * Application parent name
     * 
     * @var string
     */
    private $parentName = "";
    
    /**
     * Application parent
     * 
     * @var Application
     */
    private $parent;
    
    /**
     * Last time that circuit was loaded
     *
     * @var integer
     */
    private $lastLoadTime = 0;
    
    private $preFuseAction;
    
    private $postFuseAction;
    
    private $modified = false;
    
    /**
     * Custom attributes defined by develloper
     * 
     * @var array 
     */
    private $customAttributes = array();
    
    /**
     * Return circuit application
     *
     * @return Application
     */
    public function &getApplication() {
        return $this->application;
    }
    
    /**
     * Set circuit application
     * 
     * @param Application $application
     */
    public function setApplication( Application &$application ) {
        $this->application = &$application;
    }
    
    /**
     * Return the circuit name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set the circuit name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Return the circuit path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Return the circuit complete path
     *
     * @return string
     */
    public function getCompletePath() {
        return $this->getApplication()->getPath() . $this->getPath();
    }
    
    
    /**
     * Set the circuit path
     *
     * @param string $path
     */
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * Return circuit verb paths
     *
     * @return array
     */
    public function getVerbPaths() {
        return $this->verbPaths;
    }
    
    /**
     * Return one verb path
     *
     * @param string $name
     * @return string
     */
    public function getVerbPath( $name ) {
        if( MyFusesFileHandler::isAbsolutePath( $this->verbPaths[ $name ] ) ) {
            return $this->verbPaths[ $name ];
        }
        return $this->getApplication()->getPath() . $this->verbPaths[ $name ];
    }
    
    /**
     * Set circui verb paths
     *
     * @param array $verbPaths
     */
    public function setVerbPaths( $verbPaths ) {
        $verbPaths = unserialize( $verbPaths );
        foreach( $verbPaths as $key => $path ) {
            $verbPaths[ $key ] = preg_replace_callback( 
                '@{([\$?\w+][\:\:\w+\(\)]*[->\w+\(\)]*)}@', 
                array( $this, 'evalExpression' ), $path );
        }
        $this->verbPaths = $verbPaths;
    }
    
    /**
     * Return if a given verbPath exists
     * 
     * @param string $verbPath
     * @return boolean
     */
    public function verbPathExists( $verbPath ) {
        return isset( $this->verbPaths[ $verbPath ] );
    }
    
    /**
     * Return the circuit access
     *
     * @return integer
     */
    public function getAccess(){
        return $this->access;
    }
    
    /**
     * Set the circuit access
     *
     * @param integer $access
     */
    public function setAccess( $access = Circuit::PUBLIC_ACCESS ) {
        $this->access = $access; 
    }
    
	/**
     * Set the circuit access using a string
     *
     * @param string $access
     */
    public function setAccessByString( $accessString = "public" ) {
        if( $accessString == "" ){
            $accessString = "public";
        }
            
        $accessList = array(
            "public" => self::PUBLIC_ACCESS,
            "internal" => self::INTERNAL_ACCESS
        );
        $this->setAccess( $accessList[ $accessString ] );
    }
    
    /**
     * Add one action to circuit
     * 
     * @param Action $action
     */
    public function addAction( Action $action ) {
         $this->actions[ $action->getName() ] = $action;
    }
    
    /**
     * Return one Circuit by name
     *
     * @param string $name
     * @return FuseAction
     * @throws MyFusesFuseActionException
     */
    public function getAction( $name ) {
        if( isset( $this->actions[ $name ] ) ) {
    		return $this->actions[ $name ];
    	}
    	
    	$params = array( "actionName" => $name, "circuit" => &$this , 
    	    "application" => $this->getApplication() );
    	throw new MyFusesFuseActionException( $params, 
    	    MyFusesFuseActionException::NON_EXISTENT_FUSEACTION );
        
    }
    
    /**
     * 
     */
    public function hasAction( $name ) {
       return isset( $this->actions[ $name ] ); 
    }
    
    public function getActions() {
        return $this->actions;
    }
    
    /**
     * Enter description here...
     *
     * @return CircuitAction
     */
    public function getPreFuseAction() {
        return $this->preFuseAction;
    }
    
	/**
	 * Enter description here...
	 *
	 * @param CircuitAction $action
	 */
	public function setPreFuseAction( CircuitAction $action ) {
	    return $this->preFuseAction = $action;
	}
    
	public function unsetPreFuseAction() {
	    $this->preFuseAction = null;
	}
	
	/**
	 * Enter description here...
	 *
	 * @return CircuitAction
	 */
	public function getPostFuseAction() {
	    return $this->postFuseAction;
	}
    
	/**
	 * Enter description here...
	 *
	 * @param CircuitAction $action
	 */
	public function setPostFuseAction( CircuitAction $action ) {
	    return $this->postFuseAction = $action;
	}
    
    public function unsetPostFuseAction() {
	    $this->postFuseAction = null;
	}
	
    /**
     * Return the circuit complete file
     * 
     * complete path + file
     *
     * @return string
     */
    public function getCompleteFile() {
        return $this->getCompletePath() . $this->getFile();
    }
	
    /**
     * Return the circuit file
     *
     * @return string
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Set the circuit file
     *
     * @param string $file
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
	/**
     * Return the application parent name
     *
     * @return string
     * @access public
     */
    public function getParentName() {
        if( !is_null( $this->parent ) ) {
            return $this->parent->getName();
        }
        
        return $this->parentName;
    }
    
    /**
     * Set the applciation parent name.<br>
     * When parent name is seted the parent reference is seted to null.
     * 
     * @param string $parentName
     * @access public
     */
    public function setParentName( $parentName ) {
        if( $parentName == null ) {
            $parentName = "";
        }
        $this->parent = null;
        $this->parentName = $parentName;
    }
    
    /**
     * Return the application parent
     * 
     * @return Circuit
     * @access public
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * Set the application parent
     * 
     * @param Circuit $parent
     * @access public
     */
    public function setParent( Circuit $parent ) {
        $this->parentName = $parent->getName();
        $this->parent = $parent;
    }
    
    /**
     * Return the circuit last load time
     *
     * @return integer
     * @access public
     */
    public function getLastLoadTime() {
        return $this->lastLoadTime;
    }
    
    /**
     * Sets the circuit last load time
     * 
     * @param integer $lastLoadTime
     * @access public
     */
    public function setLastLoadTime( $lastLoadTime ) {
        $this->lastLoadTime = $lastLoadTime;
    }
    
    public function isModified() {
        return $this->modified;
    }
    
    public function setModified( $modified ) {
        return $this->modified = $modified;
    }
    
    private function evalExpression( $matches ){
	    return eval( "return " . $matches[ 1 ] . ";" );
    }
    
    
    public function setCustomAttribute( $namespace, $name, $value ) {
        $this->customAttributes[ $namespace ][ $name ] = $value;
    }
    
    public function getCustomAttribute( $namespace, $name ) {
        return $this->customAttributes[ $namespace ][ $name ];
    }
    
    public function getCustomAttributes( $namespace ) {
        return $this->customAttributes[ $namespace ];
    }
    
    public function getErrorParams() {
        $params = array(
            "appName" => $this->getApplication()->getName(),
            "circuitName" => $this->getName(),
            "circuitFile" => $this->getCompleteFile()
        );
        return $params;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */