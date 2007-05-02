<?php
/**
 * AbstractPlugin  - AbstractPlugin.class.php
 * 
 * This is a functional abstract MyFuses plugin implementation. One concrete
 * Plugin must extends this class.
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
 * @version    SVN: $Id: MyFuses.class.php 79 2007-04-26 14:32:40Z piraz $
 */

MyFuses::includeCoreFile( MyFuses::ROOT_PATH . "core/Plugin.class.php" );  

/**
 * Plugin  - Plugin.class.php
 * 
 * This is a functional abstract MyFuses plugin implementation. One concrete
 * Plugin must extends this class.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 79 $
 * @since      Revision 17
 */
abstract class AbstractPlugin implements Plugin{
    
    /**
     * Plugin name
     *
     * @var string
     */
    private $name;
    
    /**
     * Plugin file
     *
     * @var string
     */
    private $file;
    
    /**
     * Plugin path
     *
     * @var string
     */
    private $path;
    
    /**
     * Plugin fase
     *
     * @var string
     * @TODO Maybe this attribute will be a class like MyFusesFase
     */
    private $fase;
    
    /**
     * Return the plugin name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set the plugin name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Return the plugin file
     *
     * @return string
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Set the plugin file
     *
     * @param string $file
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
     /**
     * Return the plugin template
     *
     * @return string
     */
    public function getTemplate() {
        return $this->getFile();
    }
    
    /**
     * Set the plugin template
     *
     * @param string $file
     */
    public function setTemplate( $file ) {
        $this->setFile( $file );
    }
    
    /**
     * Return the plugin path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Set the plugin path
     *
     * @param string $path
     */
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * Returns the plugin fase
     *
     * @return string
     */
    public function getFase() {
        return $this->fase;
    }
    
    /**
     * Set the application fase
     *
     * @param string $fase
     */
    public function setFase( $fase ) {
        $this->fase = $fase;
    }
    
}