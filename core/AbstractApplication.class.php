<?php
/**
 * AbstractApplication - Application.class.php
 * 
 * This is an abstract implementation of Application interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement Application inteface will save you a lot of work.
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
 * The Original Code is MyFuses "a Candango implementation of Fusebox 
 * Corporation Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2010.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:Application.class.php 23 2007-01-04 13:26:33Z piraz $
 */

/**
 * This is an abstract implementation of Application interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement Application inteface will save you a lot of work.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 664
 */
abstract class AbstractApplication implements Application {
    
    /**
     * Default application flag
     *
     * @var boolean
     */
    private $default = false;
    
    /**
     * Application name
     * 
     * @var string
     */
    private $name;
    
    /**
     * Application descritor file
     * 
     * @var string
     */
    private $file;
    
    /**
     * Application path
     * 
     * @var string
     */
    private $path;
    
    ########################
    // COLLECTION PROPERTIES
    ########################
    /**
     * Application circuit references loaded or created in the application 
     * 
     * @var array An array of CircuitReferences
     */
    private $references = array();
    
    /**
     * Class definitions loaded or created in the application
     * 
     * @var array An array of ClassDefinitions
     */
    private $classes = array();
    ############################
    // END COLLECTION PROPERTIES
    ############################
    
    #####################
    // PROCESS PROPERTIES
    #####################
    /**
     * Application started state
     * 
     * @var boolean
     */
    private $started = false;
    
    /**
     * Application start time. The time this application was initialized at
     * the first time.
     * 
     * @var int
     */
    private $startTime;
    #########################
    // END PROCESS PROPERTIES
    #########################
    
    ####################################
    // PROPERTIES DIFINED IN myfuses.xml
    ####################################
    /**
     * Application locale. English locale is seted by default.
     *
     * @var string
     */
    private $locale = "en";
    
    /**
     * Application debug flag
     *
     * @var boolean
     */
    private $debug = false;
    
    /**
     * Fuseaction variable
     * 
     * @var string
     */
    private $fuseactionVariable = "fuseaction";

    /**
     * Default fuseaction
     * 
     * @var string
     */
    private $defaultFuseaction;
    
    /**
     * Precedence form or url
     * 
     * @var string
     * @deprecated 
     */
    private $precedenceFormOrUrl;
    
    /**
     * Execution mode
     * 
     * @var string
     */
    private $mode;
    
    /**
     * Fusebox strictMode
     * 
     * @var boolean
     */
    private $strictMode = false;
    
    /**
     * Appliaction password
     * 
     * @var string
     */
    private $password;
    
    /**
     * Flag that indicates that the application 
     * must be parsed with comments
     * 
     * @var boolean
     */
    private $parsedWithComments;
    
    /**
     * Flag that indicates that the application 
     * must be parsed using conditional method
     * 
     * @var boolean
     * @deprecated
     */
    private $conditionalParse;
    
    /**
     * Flag that indicates that the application 
     * has lexicon allowed
     * 
     * @var boolean
     * @deprecated
     */
    private $lexiconAllowed;
    
    /**
     * Flag that indicates that the application 
     * has lexicon allowed
     * 
     * @var boolean
     * @deprecated
     */
    private $badGrammarIgnored;
    
    /**
     * Flag that indicates that the application 
     * use assertions
     * 
     * @var boolean
     */
    private $assertionsUsed;
    
    /**
     * Application script language
     * 
     * @var string
     */
    private $scriptLanguage = "php5";
    
    /**
     * Application script file delimiter
     * 
     * @var string
     */
    private $scriptFileDelimiter = "php";
    
    /**
     * Application masked file delimiters
     * 
     * @var array
     */
    private $maskedFileDelimiters;
    
    /**
     * Application character encoding
     * 
     * @var string
     */
    private $characterEncoding = "UTF-8";
    
    /**
     * Application tools flag
     *
     * @var boolean
     */
    private $tools = false;
    ########################################
    // END PROPERTIES DIFINED IN myfuses.xml
    ########################################
    
    /**
     * Default constructor
     */
    public function __construct() {
        $this->startTime = time();
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#isDefault()
     */
    public function isDefault(){
        return $this->default;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setDefault()
     */
    public function setDefault( $default ) {
        $this->default = $default;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getName()
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setName()
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getFile()
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setFile()
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getCompleteFile()
     */
    public function getCompleteFile() {
        return $this->path . $this->file;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getPath()
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setPath()
     */
    public function setPath( $path ) {
        $this->path = MyFusesFileHandler::sanitizePath( $path );
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getParsedPath()
     */
    public function getParsedPath() {
        return MyFusesFileHandler::sanitizePath( 
           MyFuses::getInstance()->getParsedRootPath() . 
           $this->getName() );
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getParsedApplicationFile()
     */
    public function getParsedApplicationFile() { 
        return $this->getParsedPath() . $this->getName() . 
           MyFuses::getInstance()->getStoredApplicationFileExtension();
    }
    
    #####################
    // COLLECTION METHODS
    #####################
    /**
     * (non-PHPdoc)
     * @see core/Application#addReference()
     */
    public function addReference( CircuitReference $reference ) {
        // TODO Reference without name and path must throw a exception
        $this->references[ $reference->getName() ] = $reference;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getReferences()
     */
    public function getReferences() {
        return $this->references;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getReference()
     */
    public function getReference( $name ) {
        return $this->references[ $name ];
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#addClass()
     */
    public function addClass( ClassDefinition  $definition ) {
        $this->classes[ $definition->getName() ] = $definition;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getClasses()
     */
    public function getClasses() {
        return $this->classes;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getClass()
     */
    public function getClass( $name ){
        return $this->classes[ $name ];
    }
    #########################
    // END COLLECTION METHODS
    #########################
    
    ##################
    // PROCESS METHODS
    ##################
    /**
     * (non-PHPdoc)
     * @see noxml/myfuses/core/Application#isStarted()
     */
    public function isStarted() {
        if( is_null( $this->startTime ) || $this->startTime < 1 ) {
            return false;   
        }
        return $this->started;
    }
    
    /**
     * (non-PHPdoc)
     * @see noxml/myfuses/core/Application#setStarted()
     */
    public function setStarted( $started ) {
        $this->started = $started;
    }
    
    /**
     * (non-PHPdoc)
     * @see noxml/myfuses/core/Application#getStartTime()
     */
    public function getStartTime() {
        return $this->startTime;    
    }
    
    /**
     * (non-PHPdoc)
     * @see noxml/myfuses/core/Application#fireApplicationStart()
     */
    public function fireApplicationStart() {
        // fire some action
    }
    
    /**
     * (non-PHPdoc)
     * @see noxml/myfuses/core/Application#firePreProcess()
     */
    public function firePreProcess() {
        // fire some action
    }
    
    /**
     * (non-PHPdoc)
     * @see noxml/myfuses/core/Application#firePostProcess()
     */
    public function firePostProcess() {
        // fire some action
    }
    ######################
    // END PROCESS METHODS
    ######################
    
    #################################
    // METHODS DIFINED IN myfuses.xml
    #################################
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getLocale()
     */
    public function getLocale() {
        return $this->locale;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setLocale()
     */
    public function setLocale( $locale ) {
        $this->locale = $locale;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#isDebugAllowed()
     */
    public function isDebugAllowed() {
        return $this->debug;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setDebug()
     */
    public function setDebug( $debug ) {
        if( is_bool( $debug ) ) {
            $this->debug = $debug;    
        }
        else {
            if( $debug == "true" ) {
                $this->debug = true;
            }
            else {
                $this->debug = false;
            }
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getFuseactionVariable()
     */
    public function getFuseactionVariable() {
        return $this->fuseactionVariable;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setFuseactionVariable()
     */
    public function setFuseactionVariable( $fuseactionVariable ) {
        $this->fuseactionVariable = $fuseactionVariable;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getDefaultFuseaction()
     */
    public function getDefaultFuseaction() {
        return $this->defaultFuseaction;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setDefaultFuseaction()
     */
    public function setDefaultFuseaction( $defaultFuseaction ) {
        $this->defaultFuseaction = $defaultFuseaction;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getPrecedenceFormOrUrl()
     */
    public function getPrecedenceFormOrUrl() {
        return $this->precedenceFormOrUrl;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setPrecedenceFormOrUrl()
     */
    public function setPrecedenceFormOrUrl( $precedenceFormOrUrl ) {
        $this->precedenceFormOrUrl = $precedenceFormOrUrl;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getMode()
     */
    public function getMode() {
        return $this->mode;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setMode()
     */
    public function setMode( $mode ) {
        $this->mode = $mode;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#isStrictMode()
     */
    public function isStrictMode() {
        return $this->strictMode;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setStrictMode()
     */
    public function setStrictMode( $strictMode ) {
        if( is_bool( $strictMode ) ) {
            $this->strictMode = $strictMode;    
        }
        else {
            if( $strictMode == "true" ) {
                $this->strictMode = true;
            }
            else {
                $this->strictMode = false;
            }
        }       
        
    }    
    
    /**
     * (non-PHPdoc)
     * @see core/Application#getPassword()
     */
    public function getPassword() {
        return $this->password;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setPassword()
     */
    public function setPassword( $password ) {
        $this->password = $password;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#isParsedWithComments()
     */
    public function isParsedWithComments() {
        return $this->parsedWithComments;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setParsedWithComments()
     */
    public function setParsedWithComments( $parsedWithComments ) {
        if( is_bool( $parsedWithComments ) ) {
            $this->parsedWithComments = $parsedWithComments;    
        }
        else {
            if( $parsedWithComments == "true" ) {
                $this->parsedWithComments = true;
            }
            else {
                $this->parsedWithComments = false;
            }
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#isConditionalParse()
     */
    public function isConditionalParse() {
        return $this->conditionalParse;
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setConditionalParse()
     */
    public function setConditionalParse( $conditionalParse ) {
        if( is_bool( $conditionalParse ) ) {
            $this->conditionalParse = (boolean) $conditionalParse;    
        }
        else {
            if( $conditionalParse == "true" ) {
                $this->conditionalParse = true;
            }
            else {
                $this->conditionalParse = false;
            }
        }
    }
    
    /**
     * 
     * @return unknown_type
     */
    public function isLexiconAllowed() {
        return $this->lexiconAllowed;
    }
    
    public function setLexiconAllowed( $lexiconAllowed ) {
        if( is_bool( $lexiconAllowed ) ) {
            $this->lexiconAllowed = (boolean) $lexiconAllowed;    
        }
        else {
            if( $lexiconAllowed == "true" ) {
                $this->lexiconAllowed = true;
            }
            else {
                $this->lexiconAllowed = false;
            }
        }
    }
    
    public function isBadGrammarIgnored() {
        return $this->badGrammarIgnored;
    }
    
    public function setBadGrammarIgnored( $badGrammarIgnored ) {
        if( is_bool( $badGrammarIgnored ) ) {
            $this->badGrammarIgnored = (boolean) $badGrammarIgnored;    
        }
        else {
            if( $badGrammarIgnored == "true" ) {
                $this->badGrammarIgnored = true;
            }
            else {
                $this->badGrammarIgnored = false;
            }
        }
    }

    public function isAssertionsUsed() {
        return $this->assertionsUsed;
    }
    
    public function setAssertionsUsed( $assertionsUsed ) {
        if( is_bool( $assertionsUsed ) ) {
            $this->assertionsUsed = (boolean) $assertionsUsed;    
        }
        else {
            if( $assertionsUsed == "true" ) {
                $this->assertionsUsed = true;
            }
            else {
                $this->assertionsUsed = false;
            }
        }
    }
    
    public function getScriptLanguage() {
        return $this->scriptLanguage;
    }

    public function setScriptLanguage( $scriptLanguage ) {
        $this->scriptLanguage = $scriptLanguage;
    }
    
    
    public function getScriptFileDelimiter() {
        return $this->scriptFileDelimiter;
    }
    
    public function setScriptFileDelimiter( $scriptFileDelimiter ) {
        $this->scriptFileDelimiter = $scriptFileDelimiter;
    }
    
    public function getMaskedFileDelimiters() {
        return $this->maskedFileDelimiters;
    }
    
    public function setMaskedFileDelimiters( $maskedFileDelimiters ) {
        return $this->maskedFileDelimiters = explode( ",", $maskedFileDelimiters );
    }
    
    public function getCharacterEncoding() {
        return $this->characterEncoding;
    }
    
    public function setCharacterEncoding( $characterEncoding ) {
        $this->characterEncoding = strtoupper( $characterEncoding );
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#isToolsAllowed()
     */
    public function isToolsAllowed(){
        return $this->tools; 
    }
    
    /**
     * (non-PHPdoc)
     * @see core/Application#setTools()
     */
    public function setTools( $tools ) {
        if( is_bool( $tools ) ) {
            $this->tools = $tools;    
        }
        else {
            if( $tools == "true" ) {
                $this->tools = true;
            }
            else {
                $this->tools = false;
            }
        }
    }
    #####################################
    // END METHODS DIFINED IN myfuses.xml
    #####################################
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */