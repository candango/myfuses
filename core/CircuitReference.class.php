<?php
/**
 * CircuitReference - CircuitReference.class.php
 * 
 * One circuit reference is a simple structure that store basic circuit data.
 * This structure is used to make cache and simplify some process parts like
 * loading, building and parsing circuits.
 * In this file are difined the basic cricuit reference infrastructure with 
 * CircuitReference interface. CircuitReference implements the basic features
 * demanded by the interface and the BasicApplication is the implementable 
 * class. 
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

require_once MYFUSES_ROOT_PATH . "core/AbstractCircuitReference.class.php";
require_once MYFUSES_ROOT_PATH . "core/BasicCircuitReference.class.php";

/**
 * One circuit reference is a simple structure that store basic circuit data.
 * This structure is used to make cache and simplify some process parts like
 * loading, building and parsing circuits.
 * In this file are difined the basic cricuit reference infrastructure with 
 * CircuitReference interface. CircuitReference implements the basic features
 * demanded by the interface and the BasicApplication is the implementable 
 * class. 
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
interface CircuitReference {
	
    /**
     * Return the circuit reference name
     * 
     * @return string
     */
	public function getName();
    
	/**
	 * Set the circuit reference name
	 * 
	 * @param $name
	 */
    public function setName( $name ); 
    
    /**
     * Return the circuit reference path
     * 
     * @return unknown_type
     */
    public function getPath();
    
    /**
     * Set the circuit reference path
     * 
     * @param $path
     */
    public function setPath( $path );
    
    /**
     * Return the name of his circuit parent
     * 
     * @return string
     */
    public function getParent();
    
    /**
     * Set the naem of his circuit parent
     * 
     * @param $parent
     */
    public function setParent( $parent );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */