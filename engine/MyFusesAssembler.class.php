<?php
/**
 * MyFusesAssembler - MyFusesAssembler.class.php
 * 
 * All data processed by the loader need to be assembled in the myFuses object
 * tree as circuits, fuseactions, verbs, plugins and so on. The assembler do 
 * that task assembling the application and circuit structure. This interface 
 * defines all methods needed in the myFuses assembly process phase. 
 * In this file is difined the basic myFuses assembler infrastructure with 
 * MyFusesAssembler interface. The AbstactMyFusesAssembler class implements 
 * the basic features demanded by MyFusesAssembler and the BasicAssembler is the
 * instanciable class.
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
 * The Original Code is myFuses "a Candango implementation of Fusebox 
 * Corporation Fusebox" part .
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
 * @version    SVN: $Id: MyFusesLoader.class.php 793 2009-12-21 02:20:24Z flavio.garcia $
 */

require_once MYFUSES_ROOT_PATH . "engine/MyFusesAbstractAssembler.class.php";
require_once MYFUSES_ROOT_PATH . "engine/MyFusesBasicAssembler.class.php";

/**
 * All data processed by the loader need to be assembled in the myFuses object
 * tree as circuits, fuseactions, verbs, plugins and so on. The assembler do 
 * that task assembling the application and circuit structure. This interface 
 * defines all methods needed in the myFuses assembly process phase. 
 * In this file is difined the basic myFuses assembler infrastructure with 
 * MyFusesAssembler interface. The AbstactMyFusesAssembler class implements 
 * the basic features demanded by MyFusesAssembler and the BasicAssembler is the
 * instanciable class.
 * 
 * PHP version 5
 *
 * @category   loader
 * @package    myfuses.engine
 * @author     Flavio Goncalves Garcia <flavio dot garcia at candango dot org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 793 $
 * @since      Revision 20
 */
interface MyFusesAssembler{
    
    /**
     * Assembly the given application with the data provided by the loader
     * 
     * @param $application
     * @param $data
     */
    public function assemblyApplication( Application $application, $data );
    
    /**
     * Set one parameter in one application.
     * 
     * @param $application The application where the parameter will be seted
     * @param $name The parameter name
     * @param $value The parameter value
     */
    public function setApplicationParameter( Application $application, 
        $name, $value );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */