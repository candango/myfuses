<?php
/**
 * Application  - Application.class.php
 * 
 * This is the MyFuses application interface. Defines how an application must
 * be implemented.
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

/**
 * Application  - Application.class.php
 * 
 * This is the MyFuses application interface. Defines how an application must
 * be implemented.
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
interface Application extends ICacheable {
    
    /**
     * Default applicatication name
     * 
     * @var string
     * @static 
     * @final
     */
    const DEFAULT_APPLICATION_NAME = "default";
    
    /**
     * Return application locale
     *
     * @return string
     */
    public function getLocale();
    
    /**
     * Set application locale
     *
     * @param string $locale
     */
    public function setLocale( $locale );
    
    /**
     * Return if the degug is alowed
     *
     * @return boolean
     */
    public function isDebugAllowed();
    
    /**
     * Set application debug flag
     *
     * @param boolean $debug
     */
    public function setDebug( $debug );
    
    /**
     * Returns the application name
     *
     * @return string
     * @access public
     */
    public function getName();
    
    /**
     * Sets the application name
     *
     * @param string $name
     * @access public
     */
    public function setName( $name );
    
    /**
     * Returns the application path
     *
     * @return string
     * @access public
     */
    public function getPath();
    
    /**
     * Sets the application path
     *
     * @param string $path
     * @access public
     */
    public function setPath( $path );
    
    /**
     * Returns the application parsed path
     *
     * @return string
     * @access public
     */
    public function getParsedPath();
    
    /**
     * Sets the application parsed path
     *
     * @param string $parsedPath
     * @access public
     */
    public function setParsedPath( $parsedPath );
    
    /**
     * Return application loader
     *
     * @return MyFusesLoader
     * @access public
     */
    public function getLoader();
    
    /**
     * Set the application loader
     *
     * @param MyFusesLoader $loader
     * @access public
     */
    public function setLoader( MyFusesLoader $loader );
    
    /**
     * Return application builder
     *
     * @return MyFusesBuilder
     */
    public function getBuilder();
    
    /**
     * Set application builder
     *
     * @param MyFusesBuilder $builder
     */
    public function setBuilder( MyFusesBuilder $builder );
    
    /**
     * Return the application file name
     * 
     * @return string
     * @access public
     */
    public function getFile();
    
    /**
     * Return the complete application file path
     * 
     * @return string
     * @access public
     */
    public function getCompleteFile();
    
	/**
     * Return the application cache file name
     * 
     * @return string
     * @access public
     */
    public function getCacheFile();
    
    /**
     * Return the complete application file path
     * 
     * @return string
     * @access public
     */
    public function getCompleteCacheFile();
    
    /**
     * Set the application file name
     * 
     * @param string $file
     * @access public
     */
    public function setFile( $file );
    
    /**
     * Return the application last load time
     *
     * @return integer
     * @access public
     */
    public function getLastLoadTime();
    
    /**
     * Sets the application last load time
     * 
     * @param integer $lastLoadTime
     * @access public
     */
    public function setLastLoadTime( $lastLoadTime );

    /**
     * Add a circuit to application
     *
     * @param Circuit $circuit
     */
    public function addCircuit( Circuit $circuit );
    
    /**
     * Update or link the circuits whith this parents
     * 
     * @access public
     */
    public function updateCircuitsParents();
    
    /**
     * Verifies if application has a circuit
     * 
     * @param string $name
     * @return boolean
     */
    public function hasCircuit( $name );
    
    /**
     * Return a circuit by a given name
     *
     * @param string $name
     * @return Circuit
     */
    public function getCircuit( $name );

    /**
     * Return all application circuits
     *
     * @return array
     * @access public
     */
    public function getCircuits();

    /**
     * Set the applciation circuits
     *
     * @param array $circuits
     * @access public
     */
    public function setCircuits( $circuits );
    
    public function getControllerClass();
    
    /**
     * Return the application controller
     * 
     * @return MyFuses
     */
    public function getController();
    
    /**
     * Set the application Controller
     * 
     * @param MyFuses $myfuses
     */
    public function setController( MyFuses &$myFuses );
    
    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     * @access public
     */
    public function isDefault();
    
    /**
     * Set if the application is default or not
     * 
     * @param boolean $value
     * @access public
     */
    public function setDefault( $value );
    
    /**
     * Return if the application must be loaded of not
     *
     * @return boolean
     */
    public function mustLoad();
    
    /**
     * Set if the application must be loaded or not
     *
     * @param boolean $load
     */
    public function setLoad( $load );
    
    /**
     * Returns if the application must be parsed or not
     * 
     * @return boolean
     * @access public
     */
    public function mustParse();
    
    /**
     * Set if the application must be parsed or not
     * 
     * @param boolean $value
     * @access public
     */
    public function setParse( $parse );
    
    /**
     * Returns if application must be stored
     *
     * @return boolean
     */
    public function mustStore();
    
    /**
     * Set if application must be stored
     *
     * @param boolean $store
     */
    public function setStore( $store );
    
    /**
     * Return the fuseaction variable
     * 
     * @return string
     * @access public 
     */
    public function getFuseactionVariable();
    
    /**
     * Set the fusaction variable
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setFuseactionVariable( $fuseactionVariable );
    
	/**
     * Return the default fuseaction
     * 
     * @return string
     * @access public 
     */
    public function getDefaultFuseaction();
    
    /**
     * Set the defautl fuseaction
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setDefaultFuseaction( $defaultFuseaction );
    
	/**
     * Return precedence form or url
     * 
     * @return string
     * @access public 
     */
    public function getPrecedenceFormOrUrl();
    
    /**
     * Set precedence form or url
     * 
     * @param string $precedenceFormOrUrl
     * @access public
     */
    public function setPrecedenceFormOrUrl( $precedenceFormOrUrl );
    
	/**
     * Return the application mode
     * 
     * @return string
     * @access public 
     */
    public function getMode();
    
    /**
     * Set the application mode
     * 
     * @param string $mode
     * @access public
     */
    public function setMode( $mode );
    
	/**
     * Return the fusebox sctricMode
     * 
     * @return boolean
     * @access public 
     */
    public function isStrictMode();
    
    /**
     * Set the fusebox strictMode
     * 
     * @param boolean $strictMode
     * @access public
     */
    public function setStrictMode( $strictMode );
    
    /**
     * Return application password
     * 
     * @return string
     * @access public
     */
    public function getPassword();
    
    /**
     * Set the application password
     * 
     * @param $password
     * @access public
     */
    public function setPassword( $password );
    
    /**
     * Return if application must be parsed with comments
     * 
     * @return boolean
     */
    public function isParsedWithComments();
    
    /**
     * Set if application must be parsed with comments
     *
     * @param boolean $parsedWithComments
     */
    public function setParsedWithComments( $parsedWithComments );
    
    /**
     * Return if application is using conditional parse
     * 
     * @return boolean
     */
    public function isConditionalParse();
    
    /**
     * Set if application is using conditional parse
     * 
     * @param boolean $conditionalParse
     */
    public function setConditionalParse( $conditionalParse );
    
    public function isLexiconAllowed();
    
    public function setLexiconAllowed( $lexiconAllowed );
    
    public function isBadGrammarIgnored();
    
    public function setBadGrammarIgnored( $badGrammarIgnored );

    public function isAssertionsUsed();
    
    public function setAssertionsUsed( $assertionsUsed );
    
    public function getScriptLanguage();

    public function setScriptLanguage( $scriptLanguage );
    
    
    public function getScriptFileDelimiter();
    
    public function setScriptFileDelimiter( $scriptFileDelimiter );
    
    public function getMaskedFileDelimiters();
    
    public function setMaskedFileDelimiters( $maskedFileDelimiters );
    
    public function getCharacterEncoding();
    
    public function setCharacterEncoding( $characterEncoding );
    
    public function addClass( ClassDefinition $class );
    
    // TODO handle non existent class exception
    public function getClass( $name );
    
    // TODO handle non existent class exception
    public function deleteClass( $name );
    
    public function getClasses();
    
    /**
     * Return the pre process fuse action
     * 
     * @return CircuitAction
     */
    public function getPreProcessFuseAction();
    
    /**
     * Set the pre process fuse action
     * 
     * @param CirctuitAction $action
     */
    public function setPreProcessFuseAction( CirctuitAction $action );
    
    /**
     * Return the post process fuse action
     * 
     * @return CircuitAction
     */
    public function getPostProcessFuseAction();
    
    /**
     * Set the post process fuse action
     * 
     * @param CirctuitAction $action
     */
    public function postPreProcessFuseAction( CirctuitAction $action );
    
    /**
     * TODO add index parameter
     * Add one plugin in a ginven fase
     * 
     * @param Plugin $plugin
     * @param string $fase
     */
    public function addPlugin( Plugin $plugin );
    
    /**
     * Return all plugins of a given fase
     * 
     * @param string $fase
     * @return array
     */
    public function &getPlugins( $phase );
    
    /**
     * Set all plugins of a given fase
     * 
     * @param string $fase
     * @param array $plugins
     */
    public function setPlugins( $phase, $plugins );
    
    public function setRewrite( $rewrite );
    
    public function allowRewrite();
    
    /**
     * Return one plugin of a given fase and index
     * FIXME Handle non existent plugin error
     * 
     * @param string $phase
     * @param integer $index
     * @return Plugin
     */
    public function getPlugin( $phase, $index );
    
    /**
     * Clear the fase plugins array
     * 
     * @param string $fase
     */
    public function clearPlugins( $phase = null );
    
    /**
     * Return if the tools application is allowed
     *
     * @return boolean
     */
    public function isToolsAllowed();
    
    /**
     * Return the application tag
     *
     * @return string
     */
    public function getTag();
    
    /**
     * Set application tools flag
     *
     * @param boolean $tools
     */
    public function setTools( $tools );
    
    /**
     * Add one application load listener
     *
     * @param MyFusesApplicationLoaderListener $listener
     */
    public function addLoadListener( 
        MyFusesApplicationLoaderListener $listener );
    
    /**
     * Return all application load listerners
     *
     * @return array
     */
    public function getLoadListeners();
    
    /**
     * Add one application builder listener
     *
     * @param MyFusesApplicationBuilderListener $listener
     */
    public function addBuilderListener( 
        MyFusesApplicationBuilderListener $listener );
    
    /**
     * Return all application builder listerners
     *
     * @return array
     */
    public function getBuilderListeners();
    
    /**
     * Return application data
     *
     * @return array
     */
    public function &getData();
    
    /**
     * Set application data
     *
     * @param array $data
     */
    public function setData( $data );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */