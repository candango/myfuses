<?php
/**
 * MyFuses - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
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
 * @category   controller
 * @package    myfuses
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: MyFuses.class.php 747 2009-11-28 19:08:29Z flavio.garcia $
 */

/**
 * MyFuses - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 747 $
 * @since      Revision 17
 */
abstract class MyFusesLifecycle {
    
    /**
     * Load myfuses.xml and all circuit.xml's of the given application
     * 
     * @param $application
     */
    public static function loadApplication( Application $application ) {
        $loader = new MyFusesXmlLoader();
        
        $loader->loadApplication( $application );
    }
    
    /**
     * Load all aplication registered in the controller
     * 
     * @param $controller
     */
    public static function loadApplications( MyFuses $controller ) {
        foreach( $controller->getApplications() as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                self::loadApplication( $application );	
            }
        }
    }
    
	public static function createRequest( MyFuses $controller ) {
		/*$request = new MyFusesRequest();
		
	    $request->setApplication( $controller->getApplication() );
	    
	    $request->setDefaultFuseaction( $controller->getApplication()->getDefaultFuseaction() );
	    
	    $request->setFuseactionVariable( $controller->getApplication()->getFuseactionVariable() );
	    
		$router = new MyFusesBasicRequestRouter();

		$router->grab( $request );
		
		$router->resolve( $request );
		
		$router->release( $request );
		
		$controller->setRequest( $request );*/
	}
	
	public static function executeProcess( MyFuses $controller ) {
		/*$application = $controller->getApplication();
		
		if( !$application->isStarted() ) {
			$application->fireApplicationStart();
			$application->setStarted( true );
		}
		
		$application->firePreProcess();
		
		$application->firePostProcess();*/
	}
	
	/**
	 * Stores one application in his parsed file
	 * 
	 * @param $application
	 */
    public static function storeApplication( Application $application ) {
        /*$serializedApp = "<?php\nreturn unserialize( '" . 
           serialize( $application ) . "' );\n\n";
        
        MyFusesFileHandler::createPath( $application->getParsedPath() );
        
        MyFusesFileHandler::writeFile( 
            $application->getParsedApplicationFile(), $serializedApp );*/
    }
	
    /**
     * Stores all applications registered in the controller
     * 
     * @param $controller
     */
    public static function storeApplications( MyFuses $controller ) {
        foreach( $controller->getApplications() as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                self::storeApplication( $application );
            }
        }
    }
    
    /**
     * Restores application from stored file if exists
     * 
     * @param $applicationName
     * @return Application The stored application
     */
    public static function restoreApplication( $applicationName ) {
    	$applicationFile = MyFusesFileHandler::sanitizePath( 
           MyFuses::getInstance()->getParsedRootPath() . 
           $applicationName ) . $applicationName . 
           MyFuses::getInstance()->getStoredApplicationFileExtension();
    	
    	if( file_exists( $applicationFile ) ) {
    		
    		return include $applicationFile;
    		
    	}
    	
    	return null;
    }
    
}