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
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:Circuit.class.php 23 2007-01-04 13:26:33Z piraz $
 */

require_once "myfuses/core/Application.class.php";
require_once "myfuses/core/CircuitAction.class.php";
require_once "myfuses/core/ICacheable.class.php";


/**
 * Circuit - Circuit.class.php
 * 
 * MyFuses Circuit class
 * 
 * PHP version 5
 * 
 * @category   controller
 * @package    myfuses.core
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 19
 */
class Circuit implements ICacheable {
    
    /**
     * Enter description here...
     *
     */
    const PUBLIC_ACCESS = 1;
    
    /**
     * Enter description here...
     *
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
    public function setAccess( $access ) {
        $this->access;
    }
    
	/**
     * Set the circuit access using a string
     *
     * @param string $access
     */
    public function setAccessByString( $accessString ) {
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
    
    public function getAction( $name ) {
        if( isset( $this->actions[ $name ] ) ) {
    		return $this->actions[ $name ];
    	}
    	
    	$params = array( "actionName" => $name, "circuit" => &$this , 
    	    "application" => $this->getApplication() );
    	throw new MyFusesFuseActionException( $params, 
    	    MyFusesFuseActionException::NON_EXISTENT_FUSEACTION );
        
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
     * @return Application
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
    
    // TODO put prefuseaction and postfuseaction verbs in cache
    public function getCachedCode() {
        $strOut = "\$circuit = new Circuit();\n";
        $strOut .= "\$circuit->setName( \"" . $this->getName() . "\" );\n";
        $strOut .= "\$circuit->setPath( \"" . addslashes( $this->getPath() ) . "\" );\n";
        $strOut .= "\$circuit->setFile( \"" . addslashes( $this->getFile() ) . "\" );\n";
        $strOut .= "\$circuit->setLastLoadTime( " . 
            $this->getLastLoadTime() . " );\n";
        $strOut .= "\$circuit->setParentName( \"" . 
            $this->getParentName() . "\" );\n";
        $strOut .= $this->getActionsCachedCode();
        
        $strOut .= $this->getPreFuseActionCachedCode();
        
        $strOut .= $this->getPostFuseActionCachedCode();
        
        $strOut .= "\$application->addCircuit( \$circuit );\n";
        
        return $strOut;
    }
    
	/**
     * Returns all circuit actions cache code
     * 
     * @return string
     */
    private function getActionsCachedCode() {
        
        $strOut = "\n";
        
        foreach( $this->actions as $action ) {
            $strOut .= $action->getCachedCode();
            $strOut .= "\$circuit->addAction( \$action );\n";
        }
        
        return $strOut;
    }
    
    /**
     * Returns pre fuse action cache code
     * 
     * @return string
     */
    private function getPreFuseActionCachedCode() {
        $strOut = "";
        if( !( is_null( $this->preFuseAction ) ) ) {
            $strOut = "\n" . $this->preFuseAction->getCachedCode();
            $strOut .= "\$circuit->setPreFuseAction( \$action );\n";    
        }
         
        return $strOut;
    }
    
    /**
     * Returns post fuse action cache code
     * 
     * @return string
     */
    private function getPostFuseActionCachedCode() {
        $strOut = "";
        
        if( !( is_null( $this->postFuseAction ) ) ) {
            $strOut = "\n" . $this->postFuseAction->getCachedCode();
            $strOut .= "\$circuit->setPostFuseAction( \$action );\n";
        }
         
        return $strOut;
    }
    
    public function isModified() {
        return $this->modified;
    }
    
    public function setModified( $modified ) {
        return $this->modified = $modified;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */