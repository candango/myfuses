<?php
/**
 * Application  - Application.class.php
 * 
 * This is the MyFuses application class.
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
 * @version    SVN: $Id: Context.class.php 7 2006-08-10 14:03:05Z piraz $
 * @since      Revision 3
 */

/**
 * Application  - Application.class.php
 * 
 * This is the MyFuses application class.
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
class Application {
    
    /**
     * Default applicatication name
     * 
     * @access public
     * @var string
     * @static 
     * @final
     */
    const DEFAULT_APPLICATION_NAME = "default";
    
    /**
     * Flag that indicates that this application was loaded
     *
     * @var boolean
     * @access privae
     */
    private $loaded = false;
    
    /**
     * Application name
     * 
     * @access private
     */
    private $name;
    
    /**
     * Application path
     * 
     * @access private
     */
    private $path;
    
    /**
     * Application pased path. This is the path where MyFuses will put all
     * parsed files generated.
     *
     * @var string
     * @access private
     */
    private $parsedPath;
    
    /**
     * File that contains all application confs
     *
     * @var string
     */
    private $file;

    /**
     * Last time that application was loaded
     *
     * @var integer
     */
    private $lastLoadTime = 0;
    
    /**
     * Application circuits
     *
     * @var array
     */
    private $circuits = array();
    
    /**
     * Application constructor
     * 
     * @param $name Application name
     * @access public
     */
    public function __construct( $name = "default" ) {
        $this->setName( $name );
    }
    
    /**
     * Returns the application name
     *
     * @return string
     * @access public
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Sets the application name
     *
     * @param string $name
     * @access public
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Returns the application path
     *
     * @return string
     * @access public
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Sets the application path
     *
     * @param string $path
     * @access public
     */
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * Returns the application parsed path
     *
     * @return string
     * @access public
     */
    public function getParsedPath() {
        return $this->parsedPath;
    }
    
    /**
     * Sets the application parsed path
     *
     * @param string $parsedPath
     * @access public
     */
    public function setParsedPath( $parsedPath ) {
        $this->parsedPath = $parsedPath;
    }
    
    /**
     * Return if the application was loaded or not
     *
     * @return boolean
     * @access public
     */
    public function isLoaded() {
        return $this->loaded;
    }
    
    /**
     * Set if the application was loaded or not
     *
     * @param boolean $loaded
     * @access public
     */
    public function setLoaded( $loaded ) {
        $this->loaded = $loaded;
    }
    
    /**
     * Return the application file name
     * 
     * @return string
     * @access public
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Return the complete application file path
     * 
     * @return string
     * @access public
     */
    public function getCompleteFile() {
        return $this->path . $this->file;
    }
    
    /**
     * Set the application file name
     * 
     * @param string $file
     * @access public
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
    /**
     * Return the application last load time
     *
     * @return integer
     * @access public
     */
    public function getLastLoadTime() {
        return $this->lastLoadTime;
    }
    
    /**
     * Sets the application last load time
     * 
     * @param integer $lastLoadTime
     * @access public
     */
    public function setLastLoadTime( $lastLoadTime ) {
        $this->lastLoadTime = $lastLoadTime;
    }

    /**
     * Add a circuit to application
     *
     * @param Circuit $circuit
     */
    public function addCircuit( Circuit $circuit ) {
        $this->circuits[ $circuit->getName() ] = $circuit;
        
        // updating all circuits parents
        $this->updateCircuitsParents();
        
    }
    
    /**
     * Update or link the circuits whith this parents
     * 
     * @access public
     */
    public function updateCircuitsParents() {
        foreach( $this->circuits as $circuit ) {
            
            if( $circuit->getParentName() != "" ) {
                if( !is_null( $this->getCircuit( 
                    $circuit->getParentName() ) ) ) {
                    $circuit->setParent( $this->getCircuit( 
                        $circuit->getParentName() ) );
                }
            }
            
        }
    }
    
    /**
     * Return a circuit by a given name
     *
     * @param string $name
     * @return Circuit
     */
    public function getCircuit( $name ) {
        return $this->circuits[ $name ];    
    }
    
    /**
     * Return all application circuits
     * 
     * @return array
     * @access public
     */
    public function getCircits() {
        return $this->circuits;
    }
    
    /**
     * Set the applciation circuits
     * 
     * @param array $circuits
     * @access public
     */
    public function setCircuits( $circuits ) {
        $this->circuits = $circuits;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */