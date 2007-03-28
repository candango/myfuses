<?php
/**
 * Application  - Application.class.php
 * 
 * This is the MyFuses application class.
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
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:Application.class.php 23 2007-01-04 13:26:33Z piraz $
 */

/**
 * Application  - Application.class.php
 * 
 * This is the MyFuses application class.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 19
 */
class Application implements ICacheable {
    
    /**
     * Default applicatication name
     * 
     * @var string
     * @static 
     * @final
     */
    const DEFAULT_APPLICATION_NAME = "default";
    
    /**
     * Flag that indicates that this application was loaded
     *
     * @var boolean
     * @access privae
     */
    private $loaded = false;
    
    /**
     * Application name
     * 
     * @access private
     */
    private $name;
    
    /**
     * Application path
     * 
     * @access private
     */
    private $path;
    
    /**
     * Application pased path. This is the path where MyFuses will put all
     * parsed files generated.
     *
     * @var string
     * @access private
     */
    private $parsedPath;
    
    /**
     * File that contains all application confs
     *
     * @var string
     */
    private $file;
    
    /**
     * Last time that application was loaded
     *
     * @var integer
     */
    private $lastLoadTime = 0;
    
    /**
     * Application circuits
     *
     * @var array
     */
    private $circuits = array();
    
    /**
     * Default application flag
     *
     * @var boolean
     * @access private
     */
    private $default = false;
    
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
    private $characterEncoding;
    
    /**
     * Application constructor
     * 
     * @param $name Application name
     * @access public
     */
    public function __construct( $name = "default" ) {
        $this->setName( $name );
    }
    
    /**
     * Returns the application name
     *
     * @return string
     * @access public
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Sets the application name
     *
     * @param string $name
     * @access public
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Returns the application path
     *
     * @return string
     * @access public
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Sets the application path
     *
     * @param string $path
     * @access public
     */
    public function setPath( $path ) {
        $this->path = $path;
    }
    
    /**
     * Returns the application parsed path
     *
     * @return string
     * @access public
     */
    public function getParsedPath() {
        return $this->parsedPath;
    }
    
    /**
     * Sets the application parsed path
     *
     * @param string $parsedPath
     * @access public
     */
    public function setParsedPath( $parsedPath ) {
        $this->parsedPath = $parsedPath;
    }
    
    /**
     * Return if the application was loaded or not
     *
     * @return boolean
     * @access public
     */
    public function isLoaded() {
        return $this->loaded;
    }
    
    /**
     * Set if the application was loaded or not
     *
     * @param boolean $loaded
     * @access public
     */
    public function setLoaded( $loaded ) {
        $this->loaded = $loaded;
    }
    
    /**
     * Return the application file name
     * 
     * @return string
     * @access public
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Return the complete application file path
     * 
     * @return string
     * @access public
     */
    public function getCompleteFile() {
        return $this->path . $this->file;
    }
    
	/**
     * Return the application cache file name
     * 
     * @return string
     * @access public
     */
    public function getCacheFile() {
        return $this->name . ".myfuses.php";
    }
    
    /**
     * Return the complete application file path
     * 
     * @return string
     * @access public
     */
    public function getCompleteCacheFile() {
        return $this->parsedPath . $this->getCacheFile();
    }
    
    /**
     * Set the application file name
     * 
     * @param string $file
     * @access public
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
    /**
     * Return the application last load time
     *
     * @return integer
     * @access public
     */
    public function getLastLoadTime() {
        return $this->lastLoadTime;
    }
    
    /**
     * Sets the application last load time
     * 
     * @param integer $lastLoadTime
     * @access public
     */
    public function setLastLoadTime( $lastLoadTime ) {
        $this->lastLoadTime = $lastLoadTime;
    }

    /**
     * Add a circuit to application
     *
     * @param Circuit $circuit
     */
    public function addCircuit( Circuit $circuit ) {
        $this->circuits[ $circuit->getName() ] = $circuit;
        $circuit->setApplication( $this );
        // updating all circuits parents
        $this->updateCircuitsParents();
        
    }
    
    /**
     * Update or link the circuits whith this parents
     * 
     * @access public
     */
    public function updateCircuitsParents() {
        foreach( $this->circuits as $circuit ) {
            
            if( $circuit->getParentName() != "" ) {
                if( !is_null( $this->getCircuit( 
                    $circuit->getParentName() ) ) ) {
                    $circuit->setParent( $this->getCircuit( 
                        $circuit->getParentName() ) );
                }
            }
            
        }
    }
    
    /**
     * Return a circuit by a given name
     *
     * @param string $name
     * @return Circuit
     */
    public function getCircuit( $name ) {
        if( isset( $this->circuits[ $name ] ) ) {
            return $this->circuits[ $name ];    
        }
        return null;
    }
    
    /**
     * Return all application circuits
     * 
     * @return array
     * @access public
     */
    public function getCircits() {
        return $this->circuits;
    }
    
    /**
     * Set the applciation circuits
     * 
     * @param array $circuits
     * @access public
     */
    public function setCircuits( $circuits ) {
        $this->circuits = $circuits;
    }
    
    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     * @access public
     */
    public function isDefault(){
        return $this->default;
    }
    
    /**
     * Set if the application is default or not
     * 
     * @param boolean $value
     * @access public
     */
    public function setDefault( $value ) {
        $this->default = $value;
    }
    
    /**
     * Return the fuseaction variable
     * 
     * @return string
     * @access public 
     */
    public function getFuseactionVariable() {
        return $this->fuseactionVariable;
    }
    
    /**
     * Set the fusaction variable
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setFuseactionVariable( $fuseactionVariable ) {
        $this->fuseactionVariable = $fuseactionVariable;
    }
    
	/**
     * Return the default fuseaction
     * 
     * @return string
     * @access public 
     */
    public function getDefaultFuseaction() {
        return $this->defaultFuseaction;
    }
    
    /**
     * Set the defautl fuseaction
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setDefaultFuseaction( $defaultFuseaction ) {
        $this->defaultFuseaction = $defaultFuseaction;
    }
    
	/**
     * Return precedence form or url
     * 
     * @return string
     * @access public 
     */
    public function getPrecedenceFormOrUrl() {
        return $this->precedenceFormOrUrl;
    }
    
    /**
     * Set precedence form or url
     * 
     * @param string $precedenceFormOrUrl
     * @access public
     */
    public function setPrecedenceFormOrUrl( $precedenceFormOrUrl ) {
        $this->precedenceFormOrUrl = $precedenceFormOrUrl;
    }
    
	/**
     * Return the application mode
     * 
     * @return string
     * @access public 
     */
    public function getMode() {
        return $this->mode;
    }
    
    /**
     * Set the application mode
     * 
     * @param string $mode
     * @access public
     */
    public function setMode( $mode ) {
        $this->mode = $mode;
    }
    
    /**
     * Return application password
     * 
     * @return string
     * @access public
     */
    public function getPassword() {
        return $this->password;
    }
    
    /**
     * Set the application password
     * 
     * @param $password
     * @access public
     */
    public function setPassword( $password ) {
        $this->password = $password;
    }
    
    /**
     * Return if application must be parsed with comments
     * 
     * @return boolean
     */
    public function isParsedWithComments() {
        return $this->parsedWithComments;
    }
    
    /**
     * Set if application must be parsed with comments
     *
     * @param boolean $parsedWithComments
     */
    public function setParsedWithComments( $parsedWithComments ) {
        if( is_bool( $parsedWithComments ) ) {
            $this->parsedWithComments = (boolean) $parsedWithComments;    
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
     * Return if application is using conditional parse
     * 
     * @return boolean
     */
    public function isConditionalParse() {
        return $this->conditionalParse;
    }
    
    /**
     * Set if application is using conditional parse
     * 
     * @param boolean $conditionalParse
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
        $this->characterEncoding = $characterEncoding;
    }
    
    /**
     * Return the application cache code
     * 
     * @return string
     * @access public
     */
    public function getCachedCode() {
        $strOut = "\$application = new Application( \"" . $this->getName() . "\" );\n";
        
        $strOut .= "\$application->setPath( \"" . $this->getPath() . "\" );\n";
        
        $strOut .= "\$application->setParsedPath( \"" . $this->getParsedPath() . "\");\n";
        
        $strOut .= "\$application->setFile( \"" . $this->getFile() . "\" );\n";
        
        $strOut .= "\$application->setLastLoadTime( " . $this->getLastLoadTime() . " );\n";
        
        if( $this->isDefault() ) {
            $strOut .= "\$application->setDefault( true );\n";
        }
        
        // parameters
        $strOut .= "\n\$application->setFuseactionVariable( \"" . $this->getFuseactionVariable() . "\" );\n";
        $strOut .= "\$application->setDefaultFuseaction( \"" . $this->getDefaultFuseaction() . "\" );\n";
        $strOut .= "\$application->setPrecedenceFormOrUrl( \"" . $this->getPrecedenceFormOrUrl() . "\" );\n";
        $strOut .= "\$application->setMode( \"" . $this->getMode() . "\" );\n";
        $strOut .= "\$application->setPassword( \"" . $this->getPassword() . "\" );\n";
        $strOut .= "\$application->setParsedWithComments( " . ( $this->isParsedWithComments() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setConditionalParse( " . ( $this->isConditionalParse() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setLexiconAllowed( " . ( $this->isLexiconAllowed() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setBadGrammarIgnored( " . ( $this->isBadGrammarIgnored() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setAssertionsUsed( " . ( $this->isAssertionsUsed() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setScriptLanguage( \"" . $this->getScriptLanguage() . "\" );\n";
        $strOut .= "\$application->setScriptFileDelimiter( \"" . $this->getScriptFileDelimiter() . "\" );\n";
        $strOut .= "\$application->setMaskedFileDelimiters( \"" . implode( ",", $this->getMaskedFileDelimiters() ) . "\" );\n";
        $strOut .= "\$application->setCharacterEncoding( \"" . $this->getCharacterEncoding() . "\" );\n";
        // end paramenters
        
        $strOut .= $this->getCircuitsCachedCode();
        
        $strOut .= "MyFuses::getInstance()->addApplication( \$application );\n";
        
        return $strOut;
    }
    
    /**
     * Returns all application circuits cache code
     * 
     * @return string
     * @access public
     */
    private function getCircuitsCachedCode() {
        $strOut = "";        
        foreach( $this->circuits as $circuit ) {
            $strOut .= $circuit->getCachedCode() . "\n";
        }
        
        return $strOut;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */