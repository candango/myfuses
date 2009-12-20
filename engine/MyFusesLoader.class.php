<?php
/**
 * MyFusesLoader - MyFusesLoader.class.php
 * 
 * This interface defines one myFuses loader.
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
 * @category   loader
 * @package    myfuses.loader
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */
require_once MYFUSES_ROOT_PATH . "engine/MyFusesAbstractLoader.class.php";
require_once MYFUSES_ROOT_PATH . "engine/loaders/MyFusesXmlLoader.class.php";

/**
 * MyFusesLoader - MyFusesLoader.class.php
 * 
 * This interface defines one myFuses loader.
 * 
 * PHP version 5
 *
 * @category   loader
 * @package    myfuses.loader
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
interface MyFusesLoader {
	
	/**
	 * Set one parameter in one application.
	 * 
	 * @param $application The application where the parameter will be seted
	 * @param $name The parameter name
	 * @param $value The parameter value
	 */
    public function setApplicationParameter( Application $application, 
        $name, $value );
        
    /**
     * Add one circuit reference in one appliaction.
     * 
     * @param $application The application where the reference will be added
     * @param $reference The circuit reference
     */
    public function addApplicationReference( Application $application, 
       CircuitReference $reference );
       
       
    /**
     * Load all application elements like circuit, action, verbs, plugins and
     * so on.
     * 
     * @param $application The application to be loaded
     */
    public function loadApplication( Application $application );
}