<?php
/**
 * MyFuses i18n Handler class - MyFusesI18nHandler.class.php
 *
 * Utility to handle usual i18n operations.
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
 * The Original Code is Candango Fusebox Implementation part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2008.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:MyFusesI18nContext.class.php 521 2008-06-25 12:43:32Z piraz $
 */

require_once "myfuses/util/i18n/MyFusesI18nContext.class.php";

/**
 * MyFuses i18n Handler class - MyFusesI18nHandler.class.php
 *
 * Utility to handle usual i18n operations.
 *
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:521 $
 * @since      Revision 125
 */
abstract class MyFusesI18nHandler {
    
    /**
     * Native type constant
     *
     * @var string
     */
    const NATIVE_TYPE = 'native';
    
    /**
     * Gettext type constant
     *
     * @var string
     */
    const GETTEXT_TYPE = 'gettext';
    
    /**
     * Time stamp mark
     *
     * @var long
     */
    private $timeStamp;
    
    /**
     * Unique instance
     *
     * @var MyFusesI18nHandler
     */
    private static $instance;
    
    /**
     * Method that execute all steps to configure i18n
     */
    public function configure(){
        $this->markTimeStamp();
        $this->setLocale();
        
        if( $this->mustLoadFiles() ) {
            
            $this->loadFiles();
            
        }
        
    }
    
    /**
     * Set handler locale
     */
    abstract public function setLocale();
    
    /**
     * Load i18n files
     */
    public function loadFiles() {
        $application = MyFuses::getApplication();
        
        MyFuses::getInstance()->createApplicationPath( $application );
        
        $i18nPath = MyFusesFileHandler::sanitizePath( 
            MyFuses::getApplication()->getParsedPath() . "i18n" ); 
        
        $i18nFile = $i18nPath . "locale.data.php";
            
        
        if( file_exists( $i18nFile ) ) {
            $i18nData = require $i18nFile;
        }
        
        //var_dump( MyFusesFileHandler::readFile( $loca ) );die();
        
        
        MyFuses::getApplication()->getParsedPath();
        
        foreach( MyFuses::getInstance()->getI18nPaths() as $path ) {
            
            if( MyFusesFileHandler::isAbsolutePath( $path ) ) {
                $this->digPath( $path );
            }
            else {
                foreach( MyFuses::getInstance()->getApplications() as $key => $application ) {
                    if( $key != Application::DEFAULT_APPLICATION_NAME ) {
                        $this->digPath( $application->getPath() . $path );
                    }
                }
            }
            
        }
        
    }
    
    /**
     * Dig the given path to find i18n files
     *
     * @param string $paht
     */
    private function digPath( $path ) {
        if( file_exists( $path ) ) {
            $it = new RecursiveDirectoryIterator( $path );
            
            foreach ( new RecursiveIteratorIterator($it, 1) as $child ) {
                if( $child->isDir() ) {
                    $locale = $child->getBaseName();
                    
                    $localePath = MyFusesFileHandler::sanitizePath( 
                        $child->getPath() . DIRECTORY_SEPARATOR . $locale );
                    
                    if( $localePath != $path ) {
                        if( file_exists( $localePath . "expression.xml" ) ) {
                            $doc = $this->loadFile( $localePath . "expression.xml" );
                            foreach( $doc->expression as $expression ) {
                                $name = "";
                                foreach( $expression->attributes() as $key => $attr ) {
                                    if( $key == 'name' ) {
                                        $name = "" . $attr;
                                    }    
                                }
                                
                                if( $name != "" ) {
                                    MyFusesI18nContext::setExpression( $locale, $name, "" . $expression );
                                }
                            }
                        }
                    }
                }
            }
            //var_dump( MyFusesI18nContext::getContext() );die();
        }
    }
    
    /**
     * Mark timestamp
     */
    public function markTimeStamp() {
        $this->timeStamp = time();
    }
    
    
    /**
     * Return marked timestamp
     *
     * @return long
     */
    public function getTimeStamp(){
        return $this->timeStamp;
    }
    
    
    private function mustLoadFiles() {
        return true;
    }
    
    private static function loadFile( $file ) {
        try {
            // FIXME put no warning modifier in SimpleXMLElement call
            return @new SimpleXMLElement( file_get_contents( $file ) ); 
        }
        catch ( Exception $e ) {
            // FIXME handle error
            echo "<b>" . $this->getApplication()->
                getCompleteFile() . "<b><br>";
            die( $e->getMessage() );    
        }
    }
    
    public abstract function storeFiles();
    
    /*private static function storeFiles( $exps ) {
        $path = MyFusesFileHandler::sanitizePath( 
            MyFuses::getApplication()->getParsedPath() . 'i18n' );
        foreach( $exps as $locale => $expressions ) {
            $strOut = self::getFileComments( $locale );
            $strOut .= self::getFileHeaders( $locale );
            $strOut .= self::getExpressions( $locale, $expressions );
            
            $pathI18n = MyFusesFileHandler::sanitizePath( $path . $locale );
            
            if( !file_exists( $pathI18n ) ) {
                mkdir( $pathI18n, 0777, true );
                chmod( $pathI18n, 0777 );
            }
            
            $pathI18n = MyFusesFileHandler::sanitizePath( $pathI18n . 
                "LC_MESSAGES" );
            
            if( !file_exists( $pathI18n ) ) {
                mkdir( $pathI18n, 0777, true );
                chmod( $pathI18n, 0777 );
            }
            
            $fileI18n = $pathI18n . "myfuses.po";
            
            MyFusesFileHandler::writeFile( $fileI18n, $strOut );
            
            exec( 'msgfmt ' . $fileI18n . ' -o ' . $pathI18n . 'myfuses.mo' );
            
        }
        
    }*/
    
    /**
     * Return one MyFusesI18nHandler implementation 
     *
     * @return MyFusesI18nHandler
     */
    public static function getInstance() {
        
        if( is_null( self::$instance ) ) {
            switch( MyFuses::getI18nType() ) {
                case self::NATIVE_TYPE:
                    self::$instance = new MyFusesNativeI18nHandler();
                    break;
            }    
        }
        
        return self::$instance;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */