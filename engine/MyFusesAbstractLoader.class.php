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
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

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
 * @category   loader
 * @package    myfuses.loader
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
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
        
        if( $this->isApplicationParsed( $application ) ) {
            // Getting properties that developers can change in the bootstrap
            $default = $application->isDefault();
            $locale = $application->getLocale();
            
            $this->includeApplicationParsedFile( $application );
            
            // Setting properties defined by developers in the bootstrap
            $application->setDefault( $default );
            $application->setLocale( $locale );
            
            // Fixing application reference in myfuses
            MyFuses::getInstance()->addApplication( $application );
        }
        
        
        
        
        
        $data = $this->getApplicationData( $application );
        
        
        
        //var_dump( $data );
        
        /*$appMethods = array( 
            "circuits" => "loadCircuits", 
            "classes" => "loadClasses",
            "parameters" => "loadParameters"
        );
        
        $path = $application->getPath();
        
        $file = $path . "myfuses.xml";
        
        if( file_exists( $file ) ) {
            
            $data = MyFusesFileHandler::readFile( $file );
            
            try {
                // FIXME put no warning modifier in SimpleXMLElement call
                $rootNode = @new SimpleXMLElement( $data );

                foreach ( $rootNode as $key => $node ) {
                    if( isset( $appMethods[ strtolower( $key ) ] ) ) {
                        $this->$appMethods[ 
                           strtolower( $key ) ]( $application, $node );
                    }
                }
            }
            catch ( Exception $e ) {
                // FIXME handle error
                echo "<b>" . $application->getPath() . "<b><br>";
                die( $e->getMessage() );    
            }
                
        }
        else {
            $exception = new MyFusesException( "Could not find the " . 
               "application \"" . $application->getName() . "\" file." );
            
            $exception->setType( 
               MyFusesException::MYFUSES_APPLICATION_FILE_DOENST_EXISTS_TYPE );
            
            $exception->setDescription( "MyFuses can't find the application " . 
                "descriptor file. Check the directory \"" . 
                $application->getPath() . "\" and see if even myfuses.xml" . 
                " or fusebox.xml files exists." );
            
            throw $exception;
        }
        
        $this->application = null;*/
    }
    
	/**
	 * (non-PHPdoc)
	 * @see myfuses/engine/MyFusesLoader#setApplicationParameter()
	 */
	public function setApplicationParameter( Application $application, 
	   $name, $value ) {
	   
	   $applicationParameters = array(
            "fuseactionVariable" => "setFuseactionVariable",
            "defaultFuseaction" => "setDefaultFuseaction",
            "precedenceFormOrUrl" => "setPrecedenceFormOrUrl",
            "debug" => "setDebug",
            "tools" => "setTools",
            "mode" => "setMode",
            "strictMode" => "setStrictMode",
            "password" => "setPassword",
            "parseWithComments" => "setParsedWithComments",
            "conditionalParse" => "setConditionalParse",
            "allowLexicon" => "setLexiconAllowed",
            "ignoreBadGrammar" => "setBadGrammarIgnored",
            "useAssertions" => "setAssertionsUsed",
            "scriptLanguage" => "setScriptLanguage",
            "scriptFileDelimiter" => "setScriptFileDelimiter",
            "maskedFileDelimiters" => "setMaskedFileDelimiters",
            "characterEncoding" => "setCharacterEncoding"
        );
        
        // putting into $application
        if( isset( $applicationParameters[ $name ] ) ) {
            $application->$applicationParameters[ $name ]( $value );
        }
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