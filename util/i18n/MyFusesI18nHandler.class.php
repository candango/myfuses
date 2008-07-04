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
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2005 - 2006.
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
        $this->checkFiles();
        $this->loadFiles();
    }
    
    /**
     * Set handler locale
     */
    abstract public function setLocale();
    
    /**
     * Check files 
     *
     */
    abstract public function checkFiles();
    
    abstract public function loadFiles();
    
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
    
    private static function storeFiles( $exps ) {
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
        
    }
    
    private static function getFileComments( $locale ) {
        $strOut = "# " . MyFuses::getApplication()->getName() . " " . $locale . " i18n expressions file.\n";
        $strOut .= "# Copyright (C) YEAR THE PACKAGE'S COPYRIGHT HOLDER\n";
        $strOut .= "# This file is distributed under the same license as the PACKAGE package.\n";
        $strOut .= "# FIRST AUTHOR <EMAIL@ADDRESS>, YEAR.\n";
        $strOut .= "#\n";
        $strOut .= "#, fuzzy\n";
        $strOut .= "msgid \"\"\n";
        $strOut .= "msgstr \"\"\n";
        return $strOut;
    }
    
    private static function getFileHeaders( $locale ) {
        $strOut = "\"Project-Id-Version: PACKAGE VERSION\\n\"\n";
        $strOut .= "\"Report-Msgid-Bugs-To: \\n\"\n";
        $strOut .= "\"POT-Creation-Date: 2008-06-16 09:54-0300\\n\"\n";
        $strOut .= "\"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n\"\n";
        $strOut .= "\"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n\"\n";
        $strOut .= "\"Language-Team: LANGUAGE <LL@li.org>\\n\"\n";
        $strOut .= "\"MIME-Version: 1.0\\n\"\n";
        $strOut .= "\"Content-Type: text/plain; charset=UTF-8\\n\"\n";
        $strOut .= "\"Content-Transfer-Encoding: 8bit\\n\"\n\n";
        
        return $strOut;
    }
    
    private static function getExpressions( $locale, $expressions ) {
        $strOut = "";
        
        foreach( $expressions as $key => $expression ) {
            $strOut .= "#: expression " . $key . "\n";
            $strOut .= "msgid \"" . $key . "\"\n";
            $strOut .= "msgstr \"" . $expression . "\"\n\n";    
        }
        
        return $strOut;
    }
    
    /**
     * Return one 
     *
     * @return unknown
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