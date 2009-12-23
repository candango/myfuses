<?php
/**
 * MyFusesAbstractLoader - MyFusesAbstractLoader.class.php
 * 
 * This is an abstract implementation of MyFusesLoader interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement MyFusesLoader inteface and you will save you a lot of work.
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

/**
 * This is an abstract implementation of MyFusesLoader interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement MyFusesLoader inteface and you will save you a lot of work.
 * 
 * PHP version 5
 *
 * @category   loader
 * @package    myfuses.loader
 * @author     Flavio Goncalves Garcia <flavio dot garcia at candango dot org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 758
 */
abstract class MyFusesAbstractLoader implements MyFusesLoader {
	
    
    /**
     * (non-PHPdoc)
     * @see engine/MyFusesLoader#loadApplication()
     */
    public function loadApplication( Application &$application ) {
        // Getting properties that developers can change in the bootstrap
        $default = $application->isDefault();
        $locale = $application->getLocale();
        
        if( $this->isApplicationParsed( $application ) ) {
            $this->includeApplicationParsedFile( $application );
            
            // Setting properties defined by developers in the bootstrap
            $application->setDefault( $default );
            
            // Fixing application reference in myfuses
            MyFuses::getInstance()->addApplication( $application );
            
            // if not production the application is in development mode
            if( $application->getMode() == Application::PRODUCTION_MODE ) {
                if( $this->isApplicationModified( $application ) ) {
                    $this->fireLoadApplication( $application );    
                }
            }
            else {
                $this->fireLoadApplication( $application );    
            }
        }
        else {
            $this->fireLoadApplication( $application );
        }
        
        // Setting the real locale
        if( $application->getLocale() != $locale ) {
            $application->setLocale( $locale );    
        }
    }
    
    /**
     * Returns if the application was modified
     * 
     * @param $application
     * @return boolean
     */
    abstract public function isApplicationModified( Application $application );
    
    /**
     * Execute the real application load
     * 
     * @param $application The application to be loaded
     */
    protected function fireLoadApplication( Application &$application ) {
        $data = $this->getApplicationData( $application );
        
        $assembler = new MyFusesBasicAssembler();
        
        $assembler->assemblyApplication( $application, $data );
        
        $application->setLastLoadTime( time() );
    }
    
	/**
	 * (non-PHPdoc)
	 * @see engine/MyFusesLoader#addApplicationReference()
	 */
	public function addApplicationReference( Application $application, 
       CircuitReference $reference ) {
       $application->addReference( $reference );
    }
	
    /**
     * Include the appliation cache file to restore the cache
     * 
     * @param $application
     */
    private function includeApplicationParsedFile( Application &$application ) {
        // TODO Check if parsed application file exists
        
        
        $application = include $application->getParsedApplicationFile();   
    }
    
    /**
     * Returns if the application parsed file exists
     * 
     * @param $application
     * @return unknown_type
     */
    private function isApplicationParsed( Application $application ) {
        return is_file( $application->getParsedApplicationFile() );
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */