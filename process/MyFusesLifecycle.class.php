<?php
/**
 * MyFusesLifecycle - MyFusesLifecycle.class.php
 * 
 * The MyFuses Lifecycle controls all phases of application and request process.
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
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   process
 * @package    process
 * @author     Flávio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: MyFuses.class.php 405 2008-04-20 02:53:35Z piraz $
 */

/**
 * MyFusesLifecycle - MyFusesLifecycle.class.php
 * 
 * The MyFuses Lifecycle controls all phases of application and request process.
 * 
 * PHP version 5
 *
 * @category   process
 * @package    process
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 405 $
 * @since      Revision 17
 */
abstract class MyFusesLifecycle {
    
    const LOAD_PHASE = "load";
    
    const BUILD_PHASE = "build";
    
    const STORE_PHASE = "store";
    
    /**
     * Process phase constant<br>
     * value "process"
     *
     * @var string
     */
    const PROCESS_PHASE = "process";
    
    /**
     * Pre process fase constant<br>
     * Value "preProcess"
     * 
     * @var string
     */
    const PRE_PROCESS_PHASE = "preProcess";
    
    /**
     * Pre fuseaction fase constant<br>
     * Value "preFuseaction"
     * 
     * @var string
     */
    const PRE_FUSEACTION_PHASE = "preFuseaction";
    
    /**
     * Post fuseaction fase constant<br>
     * Value "postFuseaction"
     * 
     * @var string
     */
    const POST_FUSEACTION_PHASE = "postFuseaction";
    
    /**
     * Post process fase constant<br>
     * Value "postProcess"
     * 
     * @var string
     */
    const POST_PROCESS_PHASE = "postProcess";
    
    /**
     * Process error fase constant<br>
     * Value "processError"
     * 
     * @var string
     */
    const PROCESS_ERROR_PHASE = "processError";
    
    /**
     * Lifecycle Phase
     *
     * @var string
     */
    private static $phase;
    
    /**
     * Lifecycle circuit
     *
     * @var Circuit
     */
    private static $circuit;
    
    /**
     * Lifecycle action
     *
     * @var CircuitAction
     */
    private static $action;
    
    public static function configureLocale() {
        
        $handler = MyFusesI18nHandler::getInstance();
        
        $handler->configure();
        /*
        MyFusesI18nHandler::markTimeStamp();
        
        MyFusesI18nHandler::setLocale();
        
        MyFusesI18nHandler::loadFiles();
        
        $locale = MyFuses::getApplication()->getLocale();
        
        bindtextdomain( "myfuses", 
            MyFuses::getApplication()->getParsedPath() . "i18n" );
        
        textdomain( "myfuses" );*/
        
    }
    
    /*public static function configureApplications() {
        foreach( MyFuses::getInstance()->getApplications() as 
            $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                self::configureApplication( $application );
            }
        }
    }
    
    public static function configureApplication( Application $application ) {}*/
    
    
    
    /**
     * Return the current lifecycle phase
     *
     * @return string
     */
    public static function getPhase(){
        return self::$phase;
    }
    
    /**
     * Set the current lifecycle phase
     *
     * @param string $phase
     */
    public static function setPhase( $phase ){
        self::$phase = $phase;
    }
    
    /**
     * Return the current lifecycle action
     *
     * @return CircuitAction
     */
    public static function getAction() {
        return self::$action;
    }
    
    /**
     * Set the current lifecycle action
     *
     * @param CircuitAction $circuit
     */
    public static function setAction( CircuitAction $action ) {
        self::$action = $action;
    }
    
    /**
     * Load all registered applications 
     */
    public static function loadApplications() {
        foreach( MyFuses::getInstance()->getApplications() as 
            $key => $application ) {
             if( $key != Application::DEFAULT_APPLICATION_NAME ) {
                 self::loadApplication( $application );
             }     
         } 
    }
    
    /**
     * Load one application
     *
     * @param Application $application
     */
    public static function loadApplication( Application $application ) {
        $application->getLoader()->loadApplication();
    }
    
    /**
     * Builds all applications registered
     */
    public static function buildApplications() {
        foreach( MyFuses::getInstance()->getApplications() as 
            $key => $application ) {
            if( $key != Application::DEFAULT_APPLICATION_NAME ) {
                BasicMyFusesBuilder::buildApplication( $application );
             }
         }
    }
    
    public static function enableTools() {
        
        if( MyFuses::getApplication()->isToolsAllowed() ) {
            $appReference[ 'path' ] = MyFuses::MYFUSES_ROOT_PATH . 
            "myfuses_tools/";
            
            MyFuses::getInstance()->createApplication( "myfuses", 
                $appReference );

            self::loadApplication( MyFuses::getApplication( 'myfuses' ) );
            
            BasicMyFusesBuilder::buildApplication( 
                MyFuses::getApplication( 'myfuses' ) );
        }
        
    }
    
}