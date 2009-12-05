<?php
/**
 * AbstractApplication - Application.class.php
 * 
 * This is an abstract implementation of Application interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement Application inteface will save you a lot of work.
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
 * This is an abstract implementation of Application interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement Application inteface will save you a lot of work.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 664
 */
abstract class AbstractApplication implements Application {
    
    /**
     * Default application flag
     *
     * @var boolean
     */
    private $default = false;
    
    /**
     * Application name
     * 
     * @var string
     */
    private $name;
    
    /**
     * Application path
     * 
     * @var string
     */
    private $path;
    
    /**
     * Application circuit references loaded or created in the application 
     * 
     * @var array An array of CircuitReferences
     */
    private $references = array();
    
    /**
     * Class definitions loaded or created in the application
     * 
     * @var array An array of ClassDefinitions
     */
    private $classes = array();
    
    /**
     * (non-PHPdoc)
     * @see core/Application#isDefault()
     */
    public function isDefault(){
        return $this->default;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setDefault()
     */
    public function setDefault( $default ) {
        $this->default = $default;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getName()
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setName()
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getPath()
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setPath()
     */
    public function setPath( $path ) {
        $this->path = MyFusesFileHandler::sanitizePath( $path );
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#addReference()
     */
    public function addReference( CircuitReference $reference ) {
        // TODO Reference without name and path must throw a exception
        $this->references[ $reference->getName() ] = $reference;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getReferences()
     */
    public function getReferences() {
        return $this->references;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getReference()
     */
    public function getReference( $name ) {
        return $this->references[ $name ];
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#addClass()
     */
    public function addClass( ClassDefinition  $definition ) {
        $this->classes[ $definition->getName() ] = $definition;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getClasses()
     */
    public function getClasses() {
        return $this->classes;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getClass()
     */
    public function getClass( $name ){
        return $this->classes[ $name ];
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */