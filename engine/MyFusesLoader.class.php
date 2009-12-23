<?php
/**
 * MyFusesLoader - MyFusesLoader.class.php
 * 
 * MyFuses need to load the application data to process the user request sent 
 * to the controller. There is two ways to do that task: loading xml descriptor
 * files(myfuses.xml and circuit.xml) or reading the application directory
 * structure. This interface defines all methods needed in the myFuses load 
 * process phase. 
 * In this file is difined the basic myFuses loader infrastructure with 
 * MyFusesLoader interface. The AbstactMyFusesLoader class implements the basic
 * features demanded by MyFusesLoader and other concrete interfaces like 
 * MyFusesXmlLoader and MyFusesDirectoryLoader will be provided to do more 
 * specific tasks.
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
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2010.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   loader
 * @package    myfuses.loader
 * @author     Flavio Goncalves Garcia <flavio dot garcia at candango dot org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

require_once MYFUSES_ROOT_PATH . "engine/MyFusesAbstractLoader.class.php";
require_once MYFUSES_ROOT_PATH . "engine/loaders/MyFusesXmlLoader.class.php";

require_once MYFUSES_ROOT_PATH . "engine/MyFusesAssembler.class.php";


/**
 * MyFuses need to load the application data to process the user request sent 
 * to the controller. There is two ways to do that task: loading xml descriptor
 * files(myfuses.xml and circuit.xml) or reading the application directory
 * structure. This interface defines all methods needed in the myFuses load 
 * process phase. 
 * In this file is difined the basic myFuses loader infrastructure with 
 * MyFusesLoader interface. The AbstactMyFusesLoader class implements the basic
 * features demanded by MyFusesLoader and other concrete interfaces like 
 * MyFusesXmlLoader and MyFusesDirectoryLoader will be provided to do more 
 * specific tasks.
 * 
 * PHP version 5
 *
 * @category   loader
 * @package    myfuses.engine
 * @author     Flavio Goncalves Garcia <flavio dot garcia at candango dot org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 20
 */
interface MyFusesLoader {
	    
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
    public function loadApplication( Application &$application );
    
    /**
     * Return one array of mapped data from one application
     * 
     * @param $application The application to be mapped 
     * @return array One array of mapped data from the application
     */
    public function getApplicationData( Application $application );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */