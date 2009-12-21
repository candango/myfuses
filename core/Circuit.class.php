<?php
/**
 * Circuit - Circuit.class.php
 * 
 * MyFuses framework organize one application in circuits. All functionality and
 * logic is stored in circuits as Fuseactions. Normaly one circuit will 
 * represent one business entity of the application. Circuits can be called as
 * one application module, why not?
 * In this file is difined the basic circuits infrastructure with Circuit 
 * interface. The AbstactCircuit class implements the basic features demanded by
 * Circuit and the BasicCircuit is the implementable class.
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
require_once MYFUSES_ROOT_PATH . "core/AbstractCircuit.class.php";
require_once MYFUSES_ROOT_PATH . "core/BasicCircuit.class.php";

/**
 * MyFuses framework organize one application in circuits. All functionality and
 * logic is stored in circuits as Fuseactions. Normaly one circuit will 
 * represent one business entity of the application. Circuits can be called as
 * one application module. Why not?
 * In this file is difined the basic circuits infrastructure with Circuit 
 * interface. The AbstactCircuit class implements the basic features demanded by
 * Circuit and the BasicCircuit is the implementable class.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 48
 */
interface Circuit {
    
    /**
     * Returns the circuit name
     * 
     * @return string
     */
	public function getName();
	
	/**
	 * Sets the circuit name
	 * 
	 * @param $name The circuit name to be seted
	 */
	public function setName( $name ); 
	
	/**
	 * Returns the circuit path 
	 * 
	 * @return string
	 */
	public function getPath();
	
	/**
	 * Sets the circuit path
	 * 
	 * @param $path The circuit path to be seted
	 */
	public function setPath( $path );
	
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */