<?php
/**
 * Action  - Action.class.php
 * 
 * This interface defines how one action must be in MyFuses.
 * One Action is some part of MyFuses process.
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
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

require_once "myfuses/core/IParseable.class.php";

/**
 * AbstractAction  - AbstractAction.class.php
 * 
 * This is a functional abstract MyFuses Action implementation. One concrete
 * Action must extends this class.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 25
 */
interface Action extends IParseable {
   
    /**
     * Return the action name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Set the action name
     *
     * @param string $name
     */
    public function setName( $name );
    
    /**
     * Do some action. Concrete action will implement this method.
     */
    public function doAction();

    /**
     * Set custom attribute
     *
     * @param string $namespace
     * @param string $name
     * @param mixed $value
     */
    public function setCustomAttribute( $namespace, $name, $value );
    
    /**
     * Get some especific custom attribute in this action
     *
     * @param string $namespace
     * @param string $name
     * @return mixed $value
     */
    public function getCustomAttribute( $namespace, $name );
    
    /**
     * Return all custom attribute by a given namespace
     *
     * @param string $namespace
     * @return array
     */
    public function getCustomAttributes( $namespace );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */