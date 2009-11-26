<?php
/**
 * MyFuses - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
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
 * The Original Code is myFuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2009.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   controller
 * @package    myfuses
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */
 
/**
 * The MYFUSES_ROOT_PATH constant defines de framework root path and helps map
 * other important directories like parsed path and application path.
 * 
 * @var string The myFuses root path
 */
 define( "MYFUSES_ROOT_PATH", dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
 
 /**
 * MyFuses - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2009 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class MyFuses {
    
    /**
     * The MYFUSES_ROOT_PATH constant defines de framework root path and 
     * helps map other important directories like parsed path and 
     * application path.
     * 
     * @var string The myFuses root path
     */
    const MYFUSES_ROOT_PATH = MYFUSES_ROOT_PATH;
    
    /**
     * Registered applications in the controller
     * 
     * @var array Array of registered applications
     */
    protected $applications = array();
    
    /**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
    private static $instance;
    
    /**
     * Default constructor. It is to implement singleton pattern.
     */
    private function __construct() {}
    
    /**
     * Returns one instance of MyFuses. Only one instance is creted per requrest.
     * MyFuses is implemmented using the singleton pattern.
     * 
     * @return MyFuses
     * @static 
     */
    public static function getInstance() {
        
        if( is_null( self::$instance ) ) {
            self::$instance = new MyFuses();
        }
        
        return self::$instance; 
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */