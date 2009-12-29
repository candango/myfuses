<?php
/**
 * AbstractCircuit  - AbstractCircuit.class.php
 * 
 * This is an abstract implementation of Circuit interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement Circuit inteface and you will save you a lot of work.
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
 * @version    SVN: $Id: ClassDefinition.class.php 379 2008-04-14 03:04:45Z flavio.garcia $
 */

/**
 * This is an abstract implementation of Circuit interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement Circuit inteface and you will save you a lot of work.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 750
 */
abstract class AbstractCircuit implements Circuit {
	
    /**
     * Circuit access type
     *
     * @var integer
     */
    private $access;
    
    /**
     * Circuit application reference
     *
     * @var Application
     */
    private $application;
    
	/**
     * Circuit name
     * 
     * @var String
     */
	private $name;
	
	/**
     * Circuit path
     * 
     * @var String
     */
	private $path;
	
	/**
	 * The circuit file
	 * 
	 * @var String
	 */
	private $file;
	
	/**
	 * The paths that is possible find one verb
	 * 
	 * @var array An array of strings
	 */
	private $verbPaths = array();
	
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getAccess()
     */
    public function getAccess(){
        return $this->access;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getAccessName()
     */
    public function getAccessName(){
        switch ( $this->getAccess() ) {
            case self::PUBLIC_ACCESS :
                return "public";
            case self::INTERNAL_ACCESS :
                return "internal";
            case self::PRIVATE_ACCESS :
                return "private";
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#setAccess()
     */
    public function setAccess( $access = Circuit::PUBLIC_ACCESS ) {
        $this->access = $access; 
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#setAccessByString()
     */
    public function setAccessByName( $accessName = "public" ) {
        if( $accessName == "" ){
            $accessName = "public";
        }
            
        $accessList = array(
            "public" => self::PUBLIC_ACCESS,
            "internal" => self::INTERNAL_ACCESS
        );
        $this->setAccess( $accessList[ $accessName ] );
    }
	
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getApplication()
     */
    public function getApplication() {
        return $this->application;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#setApplication()
     */
    public function setApplication( Application &$application ) {
        $this->application = &$application;
    }
	
	/**
	 * (non-PHPdoc)
	 * @see core/Circuit#getName()
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see core/Circuit#setName()
	 */
	public function setName( $name ) {
		$this->name = $name;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see core/Circuit#getPath()
	 */
	public function getPath() {
        return $this->path;
	}
    
	/**
	 * (non-PHPdoc)
	 * @see core/Circuit#setPath()
	 */
    public function setPath( $path ) {
    	$this->path = $path;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getCompletePath()
     */
    public function getCompletePath() {
        return $this->getApplication()->getPath() . $this->getPath();
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getCompleteCacheFile()
     */
    public function getCompleteCacheFile() {
        return $this->getApplication()->getParsedPath() . $this->getName() . 
            ".circuit.myfuses.php";
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getCompleteFile()
     */
    public function getCompleteFile() {
        return $this->getCompletePath() . $this->getFile();
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getFile()
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#setFile()
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getVerbPaths()
     */
    public function getVerbPaths() {
        return $this->verbPaths;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Circuit#getVerbPath()
     */
    public function getVerbPath( $name ) {
        return $this->verbPaths[ $name ];
    }
    
    /**
     * TODO I think this method is for the old store/restore way. With the ... 
     * serialization don't need to do that.
     * (non-PHPdoc)
     * @see core/Circuit#setVerbPaths()
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
     * (non-PHPdoc)
     * @see core/Circuit#verbPathExists()
     */
    public function verbPathExists( $verbPath ) {
        return isset( $this->verbPaths[ $verbPath ] );
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */