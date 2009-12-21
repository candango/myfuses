<?php
/**
 * AbstractClassDefinition  - AbstractClassDefinition.class.php
 * 
 * This is an abstract implementation of ClassDefinition interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement ClassDefinition inteface and you will save you a lot 
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
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: ClassDefinition.class.php 379 2008-04-14 03:04:45Z flavio.garcia $
 */

/**
 * This is an abstract implementation of ClassDefinition interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement ClassDefinition inteface and you will save you a lot 
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
 * @since      Revision 749
 */
abstract class AbstractClassDefinition implements ClassDefinition {
    
    /**
     * Class name
     *
     * @var string
     */
    private $name;
    
    /**
     * Class path
     *
     * @var string
     */
    private $path;
    
    /**
     * (non-PHPdoc)
     * @see core/ClassDefinition#getName()
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/ClassDefinition#setName()
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/ClassDefinition#getPath()
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/ClassDefinition#setPath()
     */
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/ClassDefinition#getCompletePath()
     */
    public function getCompletePath() {
        //return $this->getApplication()->getPath() . $this->getPath();
        return $this->getPath();
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */