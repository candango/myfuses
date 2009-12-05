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
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 23
 */
interface Application {

    /**
     * Default applicatication name
     * 
     * @var string The application default name
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
     * Add one reference to application
     * 
     * @param $reference The circuit reference
     */
    public function addReference( CircuitReference $reference );
    
    /**
     * Return all circuit references registered in the application
     * 
     * @return array An array of CircuitReferences
     */
    public function getReferences();
    
    /**
     * Return one circuit reference if registered
     * 
     * @param $name The name of circuit
     * @return CircuitReference
     */
    public function getReference( $name );
    
    /**
     * Add one class definition to application
     * 
     * @param $definition One class definition
     */
    public function addClass( ClassDefinition  $definition );
    
    /**
     * Return all class defintions registered in applications
     * 
     * @return array An array of ClassDefinitions
     */
    public function getClasses();
    
    /**
     * Return one class definition by a given name
     * 
     * @param $name
     * @return ClassDefinition
     */
    public function getClass( $name );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */