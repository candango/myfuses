<?php
/**
 * BasicApplication - BasicApplication.class.php
 * 
 * This is the basic MyFuses application class.
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
 * Contributor(s): Flavio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:Application.class.php 23 2007-01-04 13:26:33Z piraz $
 */

require_once "myfuses/core/Application.class.php";

/**
 * Application  - Application.class.php
 * 
 * This is the basic MyFuses application class.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 23
 */
class BasicApplication implements Application {
    
    /**
     * Application loader
     * 
     * @var MyFusesLoader
     */
    private $loader;
    
    /**
     * Application locale. English locale is seted by default.
     *
     * @var string
     */
    private $locale = "en_US";
    
    /**
     * Flag that indicates that the application must be loaded
     *
     * @var boolean
     */
    private $load = false;
    
    /**
     * Flag that indicates if the application must be parsed
     *
     * @var boolean
     */
    private $parse = false;
    
    /**
     * Flag that indicates if the application must be stored
     *
     * @var boolean
     */
    private $store = false;
    
    /**
     * Flag that alows automatic rewrite for action resolution
     *
     * @var boolean
     */
    private $rewrite = true;
    
    /**
     * Application debug flag
     *
     * @var boolean
     */
    private $debug = false;
    
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
     * Application controller
     * 
     * @var MyFuses
     */
    private $controller;
    
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
     * All applications class definitions founded in application file
     * 
     * @var array
     */
    private $classes = array();
    
    /**
     * FuseAction to be executed before process
     * 
     * @var CircuitAction
     */
    private $preProcessFuseAction;
    
    /**
     * FuseAction to be executed after process
     * 
     * @var CircuitAction
     */
    private $postProcessFuseAction;

    /**
     * Application tools flag
     *
     * @var boolean
     */
    private $tools = false;
    
    /**
     * Plugin map
     *
     * @var array
     */
    private $plugins;
    
    /**
     * Memcalhe enabled flag
     */
    private $memcacheEnabled = false;
    
    /**
     * Array of loader listeners
     *
     * @var array
     */
    private $loaderListeners = array();
    
    /**
     * Array of builder listeners
     *
     * @var array
     */
    private $builderListeners = array();
    
    /**
     * Application data
     *
     * @var string
     */
    private $data = array();
    
    /**
     * Application constructor
     * 
     * @param $name Application name
     * @access public
     */
    public function __construct( $name = Application::DEFAULT_APPLICATION_NAME,
        $loader = null ) {
        
        $this->setName( $name );
        
        // FIXME each application must have its own loader
        if( is_null( $loader ) ) {
            $loader = 
                AbstractMyFusesLoader::getLoader( MyFusesLoader::XML_LOADER );
        }
        
        $this->setLoader( $loader );
        
        $this->plugins[ MyFusesLifecycle::PRE_PROCESS_PHASE ] = array();
        $this->plugins[ MyFusesLifecycle::PRE_FUSEACTION_PHASE ] = array();
        $this->plugins[ MyFusesLifecycle::POST_FUSEACTION_PHASE ] = array();
        $this->plugins[ MyFusesLifecycle::POST_PROCESS_PHASE ] = array();
        $this->plugins[ MyFusesLifecycle::PROCESS_ERROR_PHASE ] = array();
    }
    
    /**
     * Return if the degug is alowed
     *
     * @return boolean
     */
    public function isDebugAllowed() {
        return $this->debug;
    }
    
    /**
     * Set application debug flag
     *
     * @param boolean $debug
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
        if( substr( $path, -1 ) != DIRECTORY_SEPARATOR ) {
            $path .= DIRECTORY_SEPARATOR;
        }
        if( MyFusesFileHandler::isAbsolutePath( $path ) ) {
            $this->path = $path;    
        }
        else {
            $this->path = MyFusesFileHandler::sanitizePath( getcwd() ) . $path;
        }
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
     * Return application loader
     *
     * @return MyFusesLoader
     * @access public
     */
    public function getLoader() {
        return $this->loader;
    }
    
    /**
     * Set the application loader
     *
     * @param MyFusesLoader $loader
     * @access public
     */
    public function setLoader( MyFusesLoader $loader ) {
        $this->loader = $loader;
        $loader->setApplication( $this );
    }
    
    /**
     * Return application locale
     *
     * @return string
     */
    public function getLocale() {
        return $this->locale;
    }
    
    /**
     * Set application locale
     *
     * @param string $locale
     */
    public function setLocale( $locale ) {
        $this->locale = $locale;
    }
    
    /**
     * Return application builder
     *
     * @return MyFusesBuilder
     */
    public function getBuilder() {
        return $this->builder;
    }
    
    /**
     * Set application builder
     *
     * @param MyFusesBuilder $builder
     */
    public function setBuilder( MyFusesBuilder $builder ) {
        $this->builder = $builder;
        $builder->setApplication( $this );
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
     * Return the complete application file path
     * 
     * @return string
     * @access public
     */
    public function getCompleteCacheFileData() {
        return $this->parsedPath . "data." . $this->getCacheFile();
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
                try {
                    if( !is_null( $this->getCircuit( 
	                    $circuit->getParentName() ) ) ) {
	                     
	                    $circuit->setParent( $this->getCircuit( 
	                        $circuit->getParentName() ) );
	                }
                }
	            catch ( MyFusesCircuitException $mfe ) {
		            // TODO think about that
	                //$mfe->breakProcess();
		            return;
		        }
            }
        }
    }
    
    /**
     * Verifies if application has a circuit
     * 
     * @param string $name
     * @return boolean
     */
    public function hasCircuit( $name ) {
        if( isset( $this->circuits[ $name ] ) ) {
           return true;
        }
        return false;
    }
    
    /**
     * Return a circuit by a given name
     *
     * @param string $name
     * @return Circuit
     */
    public function getCircuit( $name ) {
        
        $circuit = null;
        
    	if( isset( $this->circuits[ $name ] ) ) {
    		$circuit = $this->circuits[ $name ];
    	}
    	
    	if( is_null( $circuit ) ) {
    	    $params = array( "circuitName" => $name, "application" => &$this );
                throw new MyFusesCircuitException( $params, 
                    MyFusesCircuitException::NON_EXISTENT_CIRCUIT );
    	}
    	
    	if( $circuit->getName() ==  'MYFUSES_GLOBAL_CIRCUIT' ) {
    	    $circuit->setLoaded( true );
    	}
    	
        if( !is_null( $this->getController()->getCurrentPhase() ) ) {
            if( !$circuit->isLoaded() ) {
                $circuit->setData( $this->getLoader()->loadCircuit( $circuit ) );
                BasicMyFusesBuilder::buildCircuit( $circuit );
                $circuit->setLoaded( true );    
            }
        }
    	
        return $circuit;
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
    
    public function getControllerClass() {
        return get_class( $this->controller );
    }
    
    /**
     * Return the application controller
     * 
     * @return MyFuses
     */
    public function getController() {
        return $this->controller;
    }
    
    /**
     * Set the application Controller
     * 
     * @param MyFuses $myfuses
     */
    public function setController( MyFuses &$myFuses ) {
        $this->controller = &$myFuses;
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
     * Return if the application must be loaded of not
     *
     * @return boolean
     */
    public function mustLoad() {
        return $this->load;
    }
    
    /**
     * Set if the application must be loaded or not
     *
     * @param boolean $load
     */
    public function setLoad( $load ) {
        $this->load = $load;
    }
    
    /**
     * Returns if the application must be parsed or not
     * 
     * @return boolean
     * @access public
     */
    public function mustParse(){
        return $this->parse;
    }
    
    /**
     * Set if the application must be parsed or not
     * 
     * @param boolean $value
     * @access public
     */
    public function setParse( $parse ) {
        $this->parse = $parse;
    }
    
    /**
     * Returns if application must be stored
     *
     * @return boolean
     */
    public function mustStore() {
        return $this->store;
    }
    
    /**
     * Set if application must be stored
     *
     * @param boolean $store
     */
    public function setStore( $store ) {
        $this->store = $store;
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
     * Return the fusebox sctricMode
     * 
     * @return boolean
     * @access public 
     */
    public function isStrictMode() {
        return $this->strictMode;
    }
    
    /**
     * Set the fusebox strictMode
     * 
     * @param boolean $strictMode
     * @access public
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
        $this->characterEncoding = strtoupper( $characterEncoding );
    }
    
    public function addClass( ClassDefinition $class ) {
        $class->setApplication( $this );
        $this->classes[ $class->getName() ] = $class;
    }
    
    // TODO handle non existent class exception
    public function getClass( $name ) {
        return $this->classes[ $name ];
    }
    
    // TODO handle non existent class exception
    public function deleteClass( $name ) {
        $this->classes[ $name ]->setApplication( null );
        unset( $this->classes[ $name ] );
    }
    
    public function getClasses() {
        return $this->classes;
    }
    
    /**
     * Return the pre process fuse action
     * 
     * @return CircuitAction
     */
    public function getPreProcessFuseAction() {
        return $this->preProcessFuseAction;
    }
    
    /**
     * Set the pre process fuse action
     * 
     * @param CirctuitAction $action
     */
    public function setPreProcessFuseAction( CirctuitAction $action ) {
        $this->preProcessFuseAction = $action;
    }
    
    /**
     * Return the post process fuse action
     * 
     * @return CircuitAction
     */
    public function getPostProcessFuseAction() {
        return $this->postProcessFuseAction;
    }
    
    /**
     * Set the post process fuse action
     * 
     * @param CirctuitAction $action
     */
    public function postPreProcessFuseAction( CirctuitAction $action ) {
        $this->postProcessFuseAction = $action;
    }
    
    /**
     * TODO add index parameter
     * Add one plugin in a ginven fase
     * 
     * @param Plugin $plugin
     * @param string $fase
     */
    public function addPlugin( Plugin $plugin ) {
        $index = count( $this->plugins[ $plugin->getPhase() ] );
        $this->plugins[ $plugin->getPhase() ][ $index ] = $plugin;
        $plugin->setApplication( $this );
        $plugin->setIndex( $index );
    }
    
    /**
     * Return all plugins of a given fase
     * 
     * @param string $fase
     * @return array
     */
    public function &getPlugins( $phase ) {
        return $this->plugins[ $phase ];
    }
    
    /**
     * Set all plugins of a given fase
     * 
     * @param string $fase
     * @param array $plugins
     */
    public function setPlugins( $phase, $plugins ) {
        $this->plugins[ $phase ] = $plugins;
    }
    
    public function setRewrite( $rewrite ) {
        $this->rewrite = $rewrite;
    }
    
    public function allowRewrite(){
        return $this->rewrite;
    }
    
    /**
     * Return one plugin of a given fase and index
     * FIXME Handle non existent plugin error
     * 
     * @param string $phase
     * @param integer $index
     * @return Plugin
     */
    public function getPlugin( $phase, $index ) {
        return $this->plugins[ $phase ][ $index ];
    }
    
    /**
     * Clear the fase plugins array
     * 
     * @param string $fase
     */
    public function clearPlugins( $phase = null ) {
        if( is_null( $phase ) ) {

            foreach( $this->plugins as $phaseName => $phase ) {
                foreach( $phase as $plugin ) {
                    $plugin->clearApplication();
                }
                $this->plugins[ $phaseName ] = array();
            }
            
        }
        else {
            foreach( $this->plugins[ $fase ] as $plugin ) {
	            $plugin->clearApplication();
	        }
	        $this->plugins[ $fase ] = array();    
        }
    }
    
    /**
     * Return if the tools application is allowed
     *
     * @return boolean
     */
    public function isToolsAllowed(){
        return $this->tools; 
    }
    
    /**
     * Return the application tag
     *
     * @return string
     */
    public function getTag() {
        return get_class( $this->getController() ) . "_" . 
            get_class( $this ) . "_" . $this->getName();
    }
    
    /**
     * Set application tools flag
     *
     * @param boolean $tools
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
    
    /**
     * Return the application cache code
     * 
     * @return string
     * @access public
     */
    public function getCachedCode() {
        $strOut = "\$application = new " . get_class( $this ) . "( \"" . $this->getName() . "\" );\n";
        
        $strOut .= "\$application->setPath( \"" . addslashes( $this->getPath() ) . "\" );\n";
        
        $strOut .= "\$application->setRewrite( " . ( $this->allowRewrite() ? "true" : "false" ) . " );\n";
        
        $strOut .= "\$application->setParsedPath( \"" . addslashes( $this->getParsedPath() ) . "\");\n";
        
        $strOut .= "\$application->setFile( \"" . addslashes( $this->getFile() ) . "\" );\n";
        
        $strOut .= "\$application->setLastLoadTime( " . $this->getLastLoadTime() . " );\n";
        $strOut .= "\$application->setLoader( new " . get_class( $this->getLoader() ) . "() );\n";
        
        /*if( $this->isDefault() ) {
            $strOut .= "\$application->setDefault( true );\n";
        }*/
        
        // parameters
        $strOut .= "\n\$application->setFuseactionVariable( \"" . 
            $this->getFuseactionVariable() . "\" );\n";
        $strOut .= "\$application->setDefaultFuseaction( \"" . 
            $this->getDefaultFuseaction() . "\" );\n";
        $strOut .= "\$application->setPrecedenceFormOrUrl( \"" . 
            $this->getPrecedenceFormOrUrl() . "\" );\n";
        $strOut .= "\$application->setMode( \"" . 
            $this->getMode() . "\" );\n";
        $strOut .= "\$application->setPassword( \"" . 
            $this->getPassword() . "\" );\n";
        $strOut .= "\$application->setParsedWithComments( " . ( 
            $this->isParsedWithComments() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setConditionalParse( " . ( 
            $this->isConditionalParse() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setLexiconAllowed( " . ( 
            $this->isLexiconAllowed() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setBadGrammarIgnored( " . ( 
            $this->isBadGrammarIgnored() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setAssertionsUsed( " . ( 
            $this->isAssertionsUsed() ? "true" : "false" ) . " );\n";
        $strOut .= "\$application->setScriptLanguage( \"" . 
            $this->getScriptLanguage() . "\" );\n";
        $strOut .= "\$application->setScriptFileDelimiter( \"" . 
            $this->getScriptFileDelimiter() . "\" );\n";
            
        $strOut .= "\$application->setDebug( " . ( 
            $this->isDebugAllowed() ? "true" : "false" ) . " );\n";
        
        $strOut .= "\$application->setTools( " . ( 
            $this->isToolsAllowed() ? "true" : "false" ) . " );\n";    
            
        if( !is_null( $this->getMaskedFileDelimiters() ) ) {
            $strOut .= "\$application->setMaskedFileDelimiters( \"" . 
                implode( ",", $this->getMaskedFileDelimiters() ) . "\" );\n";    
        }
        $strOut .= "\$application->setCharacterEncoding( \"" . 
            $this->getCharacterEncoding() . "\" );\n";
        // end paramenters
        
        $controllerClass = $this->getControllerClass();
        
        $strOut .= $controllerClass . 
            "::getInstance()->addApplication( \$application );\n";
            
        $strOut .= $this->getCircuitsCachedCode();
        
        $strOut .= $this->getClassesCacheCode();
        
        $strOut .= $this->getPluginsCacheCode();
        
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
    
    private function getClassesCacheCode(){
        $strOut = "";        
        foreach( $this->classes as $class ) {
            $strOut .= $class->getCachedCode() . "\n";
        }
        return $strOut;
    }
    
    private function getPluginsCacheCode(){
        $strOut = "";
        
        foreach( $this->plugins as $phase ) {
            foreach( $phase as $plugin ) {
                $strOut .= $plugin->getCachedCode() . "\n";    
            }
        }
        return $strOut;
    }
    
    /**
     * Add one application load listener
     *
     * @param MyFusesApplicationLoaderListener $listener
     */
    public function addLoadListener( 
        MyFusesApplicationLoaderListener $listener ){
        $this->loaderListeners[] = $listener;
    }
    
    /**
     * Return all application load listerners
     *
     * @return array
     */
    public function getLoadListeners() {
        return $this->loaderListeners;
    }
    
    /**
     * Add one application builder listener
     *
     * @param MyFusesApplicationBuilderListener $listener
     */
    public function addBuilderListener( 
        MyFusesApplicationBuilderListener $listener ){
        $this->builderListeners[] = $listener;
    }
    
    /**
     * Return all application builder listerners
     *
     * @return array
     */
    public function getBuilderListeners() {
        return $this->builderListeners;
    }
    
    /**
     * Return application data
     *
     * @return array
     */
    public function &getData() {
        return $data;
    }
    
    /**
     * Set application data
     *
     * @param array $data
     */
    public function setData( $data ) {
        $this->data = $data;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */