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
 * @version    SVN: $Id: 7 $
 * @since      Revision 3
 */

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
 * @version    SVN: $Revision: 7 $
 * @since      Revision 3
 * 
 */
class Circuit {
    
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
    private $actions;
    
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
     * Add one action to circuit
     * 
     * @param Action $action
     */
    public function addAcction( Action $action ) {
         $this->actions[] = $action;
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
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */