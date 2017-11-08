<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

/**
 * Application  - Application.php
 *
 * This is the MyFuses application interface. Defines how an application must
 * be implemented.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      f06b361b3bc6909ebf21f108d42b79a17cfb3924
 */
interface Application extends ICacheable
{
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
    public function setLocale($locale);

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
    public function setDebug($debug);

    /**
     * Returns the application name
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the application name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Returns the application path
     *
     * @return string
     */
    public function getPath();

    /**
     * Sets the application path
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * Returns the application parsed path
     *
     * @return string
     */
    public function getParsedPath();

    /**
     * Sets the application parsed path
     *
     * @param string $parsedPath
     */
    public function setParsedPath($parsedPath);

    /**
     * Return application loader
     *
     * @return MyFusesLoader
     */
    public function getLoader();

    /**
     * Set the application loader
     *
     * @param MyFusesLoader $loader
     */
    public function setLoader(MyFusesLoader $loader);

    /**
     * Return application builder
     *
     * @return Builder
     */
    public function getBuilder();

    /**
     * Set application builder
     *
     * @param Builder $builder
     */
    public function setBuilder(Builder $builder);

    /**
     * Return the application file name
     * 
     * @return string
     */
    public function getFile();

    /**
     * Return the complete application file path
     * 
     * @return string
     */
    public function getCompleteFile();

	/**
     * Return the application cache file name
     * 
     * @return string
     */
    public function getCacheFile();

    /**
     * Return the complete application file path
     * 
     * @return string
     */
    public function getCompleteCacheFile();

    /**
     * Set the application file name
     * 
     * @param string $file
     */
    public function setFile($file);

    /**
     * Return the application last load time
     *
     * @return integer
     */
    public function getLastLoadTime();

    /**
     * Sets the application last load time
     * 
     * @param integer $lastLoadTime
     */
    public function setLastLoadTime($lastLoadTime);

    /**
     * Add a circuit to application
     *
     * @param Circuit $circuit
     */
    public function addCircuit(Circuit $circuit);

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
    public function hasCircuit($name);

    /**
     * Return a circuit by a given name
     *
     * @param string $name
     * @return Circuit
     */
    public function getCircuit($name);

    /**
     * Return all application circuits
     *
     * @return array
     */
    public function getCircuits();

    /**
     * Set the applciation circuits
     *
     * @param array $circuits
     */
    public function setCircuits($circuits);

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
     * @param MyFuses $myFuses
     */
    public function setController(MyFuses &$myFuses);

    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     */
    public function isDefault();

    /**
     * Set if the application is default or not
     * 
     * @param boolean $value
     */
    public function setDefault($value);

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
    public function setLoad($load);

    /**
     * Returns if the application must be parsed or not
     * 
     * @return boolean
     */
    public function mustParse();

    /**
     * Set if the application must be parsed or not
     * 
     * @param boolean $parse
     */
    public function setParse($parse);

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
    public function setStore($store);

    /**
     * Return the fuseaction variable
     * 
     * @return string
     */
    public function getFuseactionVariable();

    /**
     * Set the fusaction variable
     * 
     * @param string $fuseactionVariable
     */
    public function setFuseactionVariable($fuseactionVariable);

	/**
     * Return the default fuseaction
     * 
     * @return string
     */
    public function getDefaultFuseaction();

    /**
     * Set the defautl fuseaction
     * 
     * @param string $defaultFuseaction
     */
    public function setDefaultFuseaction($defaultFuseaction);

	/**
     * Return precedence form or url
     * 
     * @return string
     * @deprecated
     */
    public function getPrecedenceFormOrUrl();

    /**
     * Set precedence form or url
     * 
     * @param string $precedenceFormOrUrl
     * @deprecated
     */
    public function setPrecedenceFormOrUrl($precedenceFormOrUrl);

	/**
     * Return the application mode
     * 
     * @return string
     */
    public function getMode();

    /**
     * Set the application mode
     * 
     * @param string $mode
     */
    public function setMode($mode);

	/**
     * Return the fusebox strictMode
     * 
     * @return boolean
     */
    public function isStrictMode();

    /**
     * Set the fusebox strictMode
     * 
     * @param boolean $strictMode
     */
    public function setStrictMode($strictMode);

    /**
     * Return application password
     * 
     * @return string
     */
    public function getPassword();

    /**
     * Set the application password
     * 
     * @param $password
     */
    public function setPassword($password);

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
    public function setParsedWithComments($parsedWithComments);

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
    public function setConditionalParse($conditionalParse);

    public function isLexiconAllowed();

    public function setLexiconAllowed($lexiconAllowed);

    public function isBadGrammarIgnored();

    public function setBadGrammarIgnored($badGrammarIgnored);

    public function isAssertionsUsed();

    public function setAssertionsUsed($assertionsUsed);

    public function getScriptLanguage();

    public function setScriptLanguage($scriptLanguage);

    public function getScriptFileDelimiter();

    public function setScriptFileDelimiter($scriptFileDelimiter);

    public function getMaskedFileDelimiters();

    public function setMaskedFileDelimiters($maskedFileDelimiters);

    public function getCharacterEncoding();

    public function setCharacterEncoding($characterEncoding);

    /**
     * Return security mode
     *
     * @return string
     */
    public function getSecurity();

    /**
     * Set security mode
     *
     * @param $security
     */
    public function setSecurity($security);

    /**
     * Returns it the fuseaction variable should be ignored when myfuses
     * is rewriting.
     *
     * @return boolean
     */
    public function ignoreFuseactionVariable();

    /**
     * Set the application parameter to ignore the fuseaction variable when
     * myfuses is rewriting.
     *
     * @param boolean $ignoreFuseactionVariable
     */
    public function setIgnoreFuseactionVariable($ignoreFuseactionVariable);

    public function addClass(ClassDefinition $class);

    // TODO handle non existent class exception
    public function getClass($name);

    // TODO handle non existent class exception
    public function deleteClass($name);

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
    public function setPreProcessFuseAction(CirctuitAction $action);

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
    public function postPreProcessFuseAction(CirctuitAction $action);

    /**
     * TODO add index parameter
     * Add one plugin in a ginven fase
     * 
     * @param Plugin $plugin
     */
    public function addPlugin(Plugin $plugin);

    /**
     * Return all plugins of a given phase
     * 
     * @param string $phase
     * @return array
     */
    public function &getPlugins($phase);

    /**
     * Set all plugins of a given fase
     * 
     * @param string $phase
     * @param array $plugins
     */
    public function setPlugins($phase, $plugins);

    public function setRewrite($rewrite);

    public function allowRewrite();

    /**
     * Return one plugin of a given fase and index
     * FIXME Handle non existent plugin error
     * 
     * @param string $phase
     * @param integer $index
     * @return Plugin
     */
    public function getPlugin($phase, $index);

    /**
     * Clear the fase plugins array
     * 
     * @param string $phase
     */
    public function clearPlugins($phase = null);

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
    public function setTools($tools);

    /**
     * Add one application load listener
     *
     * @param MyFusesApplicationLoaderListener $listener
     */
    public function addLoadListener(MyFusesApplicationLoaderListener $listener);

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
        MyFusesApplicationBuilderListener $listener);

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
    public function setData($data);
}
