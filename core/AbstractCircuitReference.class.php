<?php
/**
 * BasicCircuitReference - BasicCircuitReference.class.php
 * 
 * This is an abstract implementation of CircuitReference interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement CircuitReference inteface and you will save you a lot 
 * of work.
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

/**
 * This is an abstract implementation of CircuitReference interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement CircuitReference inteface and you will save you a lot 
 * of work.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 746
 */
abstract class AbstractCircuitReference implements CircuitReference {
    
    /**
     * Circuit refence name
     * 
     * @var string
     */
    private $name;
    
    /**
     * Circuit reference path
     * 
     * @var string
     */
    private $path;
    
    /**
     * Circuit reference parent name
     * 
     * @var string
     */
    private $parent;
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitReference#getName()
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitReference#setName()
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitReference#getPath()
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitReference#setPath()
     */
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitReference#getParent()
     */
    public function getParent() {
    	return $this->parent;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/CircuitReference#setParent()
     */
    public function setParent( $parent ) {
    	$this->parent = $parent;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */