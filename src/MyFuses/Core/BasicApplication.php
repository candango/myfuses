<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core;

use Candango\MyFuses\Engine\AbstractLoader;
use Candango\MyFuses\Engine\ApplicationBuilderListener;
use Candango\MyFuses\Engine\ApplicationLoaderListener;
use Candango\MyFuses\Engine\Loader;
use Candango\MyFuses\Controller;
use Candango\MyFuses\Process\Lifecycle;
use Candango\MyFuses\Util\FileHandler;

/**
 * Application  - Application.php
 *
 * This is the basic MyFuses application class.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      7254c75984775e0c9620d66af6f9c1c67288bba5
 */
class BasicApplication implements Application
{
    /**
     * Application loader
     * 
     * @var Loader
     */
    private $loader;

    /**
     * Application locale. English locale is seted by default.
     *
     * @var string
     */
    private $locale = "en";

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
    private $rewrite = false;

    /**
     * Application debug flag
     *
     * @var boolean
     */
    private $debug = false;

    /**
     * Application name
     * 
     * @var string
     */
    private $name;

    /**
     * Application path
     *
     * @var string
     */
    private $path;

    /**
     * Application pased path. This is the path where MyFuses will put all
     * parsed files generated.
     *
     * @var string
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
     * @var Controller
     */
    private $controller;

    /**
     * Default application flag
     *
     * @var boolean
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
     * #TODO: Application parameter precedenceFormOrUrl must be removed
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
     * Flag that indicates that the application has lexicon allowed
     * #TODO: This must be removed. We will allow lexicons all the time
     *
     * @var boolean
     * @deprecated
     */
    private $lexiconAllowed = true;

    /**
     * Flag that indicates that bad grammar will be ignored
     * #TODO: This is a big feature, not using at this moment
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
     * #TODO: This is not relevant as we serve only php
     * #TODO: The php5 distinction was used during transction from php 4 to 5
     * 
     * @var string
     */
    private $scriptLanguage = "php5";

    /**
     * Application script file delimiter
     * #TODO: This is not relevant as we serve only php
     * 
     * @var string
     */
    private $scriptFileDelimiter = "php";

    /**
     * Application masked file delimiters
     * #TODO: This is a big feature, not using at this time
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
     * Security mode parameter
     *
     * @var string
     */
    private $security = "optimistic";

    /**
     * Flag that defines if the fuseaction variable should be ignored
     *
     * @var bool
     */
    private $ignoreFuseactionVariable = false;

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
     *
     * @deprecated
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
     * @var array
     */
    private $data = array();

    /**
     * Application constructor
     * 
     * @param string $name
     * @param Loader $loader
     */
    public function __construct(
        $name = Application::DEFAULT_APPLICATION_NAME,
        $loader = null
    )
    {
        $this->setName($name);

        if (is_null($loader)) {
            $loader = AbstractLoader::getLoader(Loader::XML_LOADER);
        }

        $this->setLoader($loader);

        $this->plugins[Lifecycle::PRE_PROCESS_PHASE] = array();
        $this->plugins[Lifecycle::PRE_FUSEACTION_PHASE] = array();
        $this->plugins[Lifecycle::POST_FUSEACTION_PHASE] = array();
        $this->plugins[Lifecycle::POST_PROCESS_PHASE] = array();
        $this->plugins[Lifecycle::PROCESS_ERROR_PHASE] = array();
        $this->plugins[Lifecycle::FUSEACTION_EXCEPTION_PHASE] = array();
    }

    /**
     * Return if the degug is alowed
     *
     * @return boolean
     */
    public function isDebugAllowed()
    {
        return $this->debug;
    }

    /**
     * Set application debug flag
     *
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        if (is_bool($debug)) {
            $this->debug = $debug;    
        } else {
            if ($debug == "true") {
                $this->debug = true;
            } else {
                $this->debug = false;
            }
        }
    }

    /**
     * Returns the application name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the application name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the application path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the application path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        if (substr($path, -1) != DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }
        if (FileHandler::isAbsolutePath($path)) {
            $this->path = $path;    
        } else {
            $this->path = FileHandler::sanitizePath(getcwd()) . $path;
        }
    }

    /**
     * Returns the application parsed path
     *
     * @return string
     */
    public function getParsedPath()
    {
        return $this->parsedPath;
    }

    /**
     * Sets the application parsed path
     *
     * @param string $parsedPath
     */
    public function setParsedPath($parsedPath)
    {
        $this->parsedPath = $parsedPath;
    }

    /**
     * Return application loader
     *
     * @return Loader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Set the application loader
     *
     * @param Loader $loader
     */
    public function setLoader(Loader $loader)
    {
        $this->loader = $loader;
        $loader->setApplication($this);
    }

    /**
     * Return application locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set application locale
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Return application builder
     *
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Set application builder
     *
     * @param Builder $builder
     */
    public function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
        $builder->setApplication($this);
    }

    /**
     * Return the application file name
     * 
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Return the complete application file path
     * 
     * @return string
     */
    public function getCompleteFile()
    {
        return $this->path . $this->file;
    }

	/**
     * Return the application cache file name
     * 
     * @return string
     */
    public function getCacheFile()
    {
        return $this->name . ".myfuses.php";
    }

    /**
     * Return the complete application file path
     * 
     * @return string
     */
    public function getCompleteCacheFile()
    {
        return $this->parsedPath . $this->getCacheFile();
    }

    /**
     * Set the application file name
     * 
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Return the application last load time
     *
     * @return integer
     */
    public function getLastLoadTime()
    {
        return $this->lastLoadTime;
    }

    /**
     * Sets the application last load time
     * 
     * @param integer $lastLoadTime
     */
    public function setLastLoadTime($lastLoadTime)
    {
        $this->lastLoadTime = $lastLoadTime;
    }

    /**
     * Add a circuit to application
     *
     * @param Circuit $circuit
     */
    public function addCircuit(Circuit $circuit)
    {
        $this->circuits[$circuit->getName()] = $circuit;
        $circuit->setApplication($this);
        // updating all circuits parents
        //$this->updateCircuitsParents();
    }

    /**
     * Update or link circuits with the corespondent parents
     */
    public function updateCircuitsParents()
    {
        foreach ($this->circuits as $circuit) {
            if ($circuit->getParentName() != "") {
                try {
                    if (!is_null($this->getCircuit($circuit->getParentName()))) {
	                    $circuit->setParent($this->getCircuit(
	                        $circuit->getParentName()));
	                }
                } catch (CircuitException $ce) {
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
    public function hasCircuit($name)
    {
        if ( isset($this->circuits[$name])) {
           return true;
        }
        return false;
    }
    
    /**
     * Returns a circuit by its given name
     *
     * @param string $name
     * @return Circuit
     * @throws CircuitException
     */
    public function getCircuit($name)
    {
        $circuit = null;

    	if ( isset($this->circuits[$name])) {
    		$circuit = $this->circuits[$name];
    	}

    	if (is_null($circuit)) {
    	    $params = array("circuitName" => $name, "application" => &$this);
                throw new CircuitException($params,
                    CircuitException::NON_EXISTENT_CIRCUIT);
    	}

    	Lifecycle::checkCircuit($circuit);
        return $circuit;
    }

    /**
     * Return all application circuits
     *
     * @return array
     */
    public function getCircuits()
    {
    	return $this->circuits;
    }

    /**
     * Set the applciation circuits
     *
     * @param array $circuits
     */
    public function setCircuits($circuits)
    {
    	$this->circuits = $circuits;
    }

    public function getControllerClass()
    {
        return get_class($this->controller);
    }

    /**
     * Return the application controller
     * 
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set the application Controller
     * 
     * @param Controller $controller
     */
    public function setController(Controller &$controller)
    {
        $this->controller = &$controller;
    }

    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * Set if the application is default or not
     * 
     * @param boolean $value
     */
    public function setDefault($value)
    {
        $this->default = $value;
    }

    /**
     * Return if the application must be loaded of not
     *
     * @return boolean
     */
    public function mustLoad()
    {
        return $this->load;
    }

    /**
     * Set if the application must be loaded or not
     *
     * @param boolean $load
     */
    public function setLoad($load)
    {
        $this->load = $load;
    }

    /**
     * Returns if the application must be parsed or not
     * 
     * @return boolean
     */
    public function mustParse()
    {
        return $this->parse;
    }

    /**
     * Set if the application must be parsed or not
     * 
     * @param boolean $parse
     */
    public function setParse($parse)
    {
        $this->parse = $parse;
    }

    /**
     * Returns if application must be stored
     *
     * @return boolean
     */
    public function mustStore()
    {
        return $this->store;
    }

    /**
     * Set if application must be stored
     *
     * @param boolean $store
     */
    public function setStore($store)
    {
        $this->store = $store;
    }

    /**
     * Return the fuseaction variable
     * 
     * @return string
     */
    public function getFuseactionVariable()
    {
        return $this->fuseactionVariable;
    }

    /**
     * Set the fuseaction variable
     * 
     * @param string $fuseactionVariable
     */
    public function setFuseactionVariable($fuseactionVariable)
    {
        $this->fuseactionVariable = $fuseactionVariable;
    }

	/**
     * Return the default fuseaction
     * 
     * @return string
     */
    public function getDefaultFuseaction()
    {
        return $this->defaultFuseaction;
    }

    /**
     * Set the default fuseaction
     * 
     * @param string $defaultFuseaction
     */
    public function setDefaultFuseaction($defaultFuseaction)
    {
        $this->defaultFuseaction = $defaultFuseaction;
    }

	/**
     * Return precedence form or url
     * 
     * @return string
     * @deprecated
     */
    public function getPrecedenceFormOrUrl()
    {
        return $this->precedenceFormOrUrl;
    }

    /**
     * Set precedence form or url
     * 
     * @param string $precedenceFormOrUrl
     * @deprecated
     */
    public function setPrecedenceFormOrUrl($precedenceFormOrUrl)
    {
        $this->precedenceFormOrUrl = $precedenceFormOrUrl;
    }

	/**
     * Return the application mode
     * 
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the application mode
     * 
     * @param string $mode
     */
    public function setMode($mode)
    {
        if (in_array($mode, array(Controller::MODE_DEVELOPMENT,
            Controller::MODE_PRODUCTION))) {
            $this->mode = $mode;
        } else {
            // TODO: This must be a warning in the log when implmented.
            $this->mode = Controller::MODE_DEVELOPMENT;
        }
    }

	/**
     * Return the fusebox sctricMode
     * 
     * @return boolean
     */
    public function isStrictMode()
    {
        return $this->strictMode;
    }

    /**
     * Set the fusebox strictMode
     * 
     * @param boolean $strictMode
     */
    public function setStrictMode($strictMode)
    {
    	if (is_bool($strictMode)) {
            $this->strictMode = $strictMode;    
        } else {
            if ($strictMode == "true") {
                $this->strictMode = true;
            } else {
                $this->strictMode = false;
            }
        }
    }    

    /**
     * Return application password
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the application password
     * 
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Return if application must be parsed with comments
     * 
     * @return boolean
     */
    public function isParsedWithComments()
    {
        return $this->parsedWithComments;
    }

    /**
     * Set if application must be parsed with comments
     *
     * @param boolean $parsedWithComments
     */
    public function setParsedWithComments($parsedWithComments)
    {
        if (is_bool($parsedWithComments)) {
            $this->parsedWithComments = (boolean) $parsedWithComments;
        } else {
            if ($parsedWithComments == "true") {
                $this->parsedWithComments = true;
            } else {
                $this->parsedWithComments = false;
            }
        }
    }

    /**
     * Return if application is using conditional parse
     * 
     * @return boolean
     * @deprecated
     */
    public function isConditionalParse()
    {
        return $this->conditionalParse;
    }

    /**
     * Set if application is using conditional parse
     * 
     * @param boolean $conditionalParse
     * @deprecated
     */
    public function setConditionalParse($conditionalParse)
    {
        if (is_bool($conditionalParse)) {
            $this->conditionalParse = (boolean) $conditionalParse;
        } else {
            if ($conditionalParse == "true") {
                $this->conditionalParse = true;
            } else {
                $this->conditionalParse = false;
            }
        }
    }

    public function isLexiconAllowed()
    {
        return $this->lexiconAllowed;
    }

    public function setLexiconAllowed($lexiconAllowed)
    {
        if (is_bool($lexiconAllowed)) {
            $this->lexiconAllowed = (boolean) $lexiconAllowed;
        } else {
            if ($lexiconAllowed == "true") {
                $this->lexiconAllowed = true;
            } else {
                $this->lexiconAllowed = false;
            }
        }
    }

    public function isBadGrammarIgnored()
    {
        return $this->badGrammarIgnored;
    }

    public function setBadGrammarIgnored($badGrammarIgnored)
    {
        if (is_bool($badGrammarIgnored)) {
            $this->badGrammarIgnored = (boolean) $badGrammarIgnored;    
        } else {
            if ($badGrammarIgnored == "true") {
                $this->badGrammarIgnored = true;
            } else {
                $this->badGrammarIgnored = false;
            }
        }
    }

    public function isAssertionsUsed()
    {
        return $this->assertionsUsed;
    }

    public function setAssertionsUsed($assertionsUsed)
    {
        if (is_bool($assertionsUsed)) {
            $this->assertionsUsed = (boolean) $assertionsUsed;    
        } else {
            if ($assertionsUsed == "true") {
                $this->assertionsUsed = true;
            } else {
                $this->assertionsUsed = false;
            }
        }
    }

    public function getScriptLanguage()
    {
        return $this->scriptLanguage;
    }

    public function setScriptLanguage($scriptLanguage)
    {
        $this->scriptLanguage = $scriptLanguage;
    }

    public function getScriptFileDelimiter()
    {
        return $this->scriptFileDelimiter;
    }

    public function setScriptFileDelimiter($scriptFileDelimiter)
    {
        $this->scriptFileDelimiter = $scriptFileDelimiter;
    }

    public function getMaskedFileDelimiters()
    {
        return $this->maskedFileDelimiters;
    }

    public function setMaskedFileDelimiters($maskedFileDelimiters)
    {
        return $this->maskedFileDelimiters = explode(",",
            $maskedFileDelimiters);
    }

    public function getCharacterEncoding()
    {
        return $this->characterEncoding;
    }

    public function setCharacterEncoding($characterEncoding)
    {
        $this->characterEncoding = $characterEncoding;
    }

    /**
     * Return security mode
     *
     * @return string
     */
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * Set security mode
     *
     * @param $security
     */
    public function setSecurity($security)
    {
        $allowedModes = array("optimistic", "pessimistic", "disabled");
        if (in_array($security, $allowedModes)) {
            $this->security = $security;
        } else {
            // If invalid set to optimistic
            $this->security = $allowedModes[0];
        }
    }

    /**
     * Returns it the fuseaction variable should be ignored when myfuses
     * is rewriting.
     *
     * @return boolean
     */
    public function ignoreFuseactionVariable()
    {
        return $this->ignoreFuseactionVariable;
    }

    /**
     * Set the application parameter to ignore the fuseaction variable when
     * myfuses is rewriting.
     *
     * @param boolean $ignoreFuseactionVariable
     */
    public function setIgnoreFuseactionVariable($ignoreFuseactionVariable)
    {
        if (is_bool($ignoreFuseactionVariable)) {
            $this->ignoreFuseactionVariable =
                (boolean) $ignoreFuseactionVariable;
        } else {
            if ($ignoreFuseactionVariable == "true") {
                $this->ignoreFuseactionVariable = true;
            } else {
                $this->ignoreFuseactionVariable = false;
            }
        }
    }

    public function addClass(ClassDefinition $class)
    {
        $class->setApplication($this);
        $this->classes[$class->getName()] = $class;
    }

    // TODO handle non existent class exception
    public function getClass($name)
    {
        return $this->classes[$name];
    }

    // TODO handle non existent class exception
    public function deleteClass($name)
    {
        $this->classes[$name]->setApplication(null);
        unset($this->classes[$name]);
    }

    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Return the pre process fuse action
     * 
     * @return CircuitAction
     */
    public function getPreProcessFuseAction()
    {
        return $this->preProcessFuseAction;
    }

    /**
     * Set the pre process fuse action
     * 
     * @param CirctuitAction $action
     */
    public function setPreProcessFuseAction(CirctuitAction $action)
    {
        $this->preProcessFuseAction = $action;
    }

    /**
     * Return the post process fuse action
     * 
     * @return CircuitAction
     */
    public function getPostProcessFuseAction()
    {
        return $this->postProcessFuseAction;
    }

    /**
     * Set the post process fuse action
     * 
     * @param CirctuitAction $action
     */
    public function postPreProcessFuseAction(CirctuitAction $action)
    {
        $this->postProcessFuseAction = $action;
    }

    /**
     * TODO add index parameter
     * Add one plugin in a ginven fase
     * 
     * @param Plugin $plugin
     */
    public function addPlugin(Plugin $plugin)
    {
        $index = count($this->plugins[$plugin->getPhase()]);
        $this->plugins[$plugin->getPhase()][$index] = $plugin;
        $plugin->setApplication($this);
        $plugin->setIndex($index);
    }

    /**
     * Return all plugins of a given fase
     * 
     * @param string $phase
     * @return array
     */
    public function &getPlugins($phase)
    {
        return $this->plugins[$phase];
    }

    /**
     * Set all plugins of a given fase
     * 
     * @param string $phase
     * @param array $plugins
     */
    public function setPlugins($phase, $plugins)
    {
        $this->plugins[$phase] = $plugins;
    }

    public function setRewrite($rewrite)
    {
        if (is_bool($rewrite)) {
            $this->rewrite = (boolean) $rewrite;
        } else {
            if ($rewrite == "true") {
                $this->rewrite = true;
            } else {
                $this->rewrite = false;
            }
        }
    }

    public function allowRewrite()
    {
        return $this->rewrite;
    }

    /**
     * Return a plugin by a given phase and index
     * FIXME Handle non existent plugin error
     * 
     * @param string $phase
     * @param integer $index
     * @return Plugin
     */
    public function getPlugin($phase, $index)
    {
        return $this->plugins[$phase][$index];
    }

    /**
     * Clear the phase plugins array
     * 
     * @param string $phase
     */
    public function clearPlugins($phase = null)
    {
        if (is_null($phase)) {
            foreach ($this->plugins as $phaseName => $phase) {
                foreach ($phase as $plugin) {
                    $plugin->clearApplication();
                }
                $this->plugins[$phaseName] = array();
            }
        } else {
            foreach ($this->plugins[ $phase ] as $plugin) {
	            $plugin->clearApplication();
	        }
	        $this->plugins[$phase] = array();
        }
    }

    /**
     * Return if the tools application is allowed
     *
     * @return boolean
     */
    public function isToolsAllowed()
    {
        return $this->tools; 
    }

    /**
     * Return the application tag
     *
     * @return string
     */
    public function getTag()
    {
        return get_class($this->getController()) . "_" . get_class($this) .
            "_" . $this->getName();
    }

    /**
     * Set application tools flag
     *
     * @param boolean $tools
     */
    public function setTools($tools)
    {
        if (is_bool($tools))
        {
            $this->tools = (boolean) $tools;
        } else {
            if ($tools == "true")
            {
                $this->tools = true;
            } else {
                $this->tools = false;
            }
        }
    }

    /**
     * Return the application cache code
     * 
     * @return string
     */
    public function getCachedCode()
    {
        $strOut = "\$application = new " . get_class($this) . "(\"" .
            $this->getName() . "\");\n";
        $strOut .= "\$application->setPath(\"" . addslashes($this->getPath()) .
            "\");\n";
        $strOut .= "\$application->setRewrite(" .
            ($this->allowRewrite() ? "true" : "false") . ");\n";
        $strOut .= "\$application->setParsedPath(\"" .
            addslashes($this->getParsedPath()) . "\");\n";
        $strOut .= "\$application->setFile(\"" .
            addslashes($this->getFile()) . "\");\n";
        $strOut .= "\$application->setLastLoadTime(" .
            $this->getLastLoadTime() . ");\n";
        $strOut .= "\$application->setLocale(\"" .
            $this->getLocale() . "\");\n";
        $strOut .= "\$application->setLoader(new " .
            get_class($this->getLoader()) . "());\n";

        /*if ($this->isDefault() ) {
            $strOut .= "\$application->setDefault( true );\n";
        }*/

        // parameters
        $strOut .= "\n\$application->setFuseactionVariable(\"" .
            $this->getFuseactionVariable() . "\");\n";
        $strOut .= "\$application->setDefaultFuseaction(\"" .
            $this->getDefaultFuseaction() . "\");\n";
        $strOut .= "\$application->setPrecedenceFormOrUrl(\"" .
            $this->getPrecedenceFormOrUrl() . "\");\n";
        $strOut .= "\$application->setMode(\"" .
            $this->getMode() . "\");\n";
        $strOut .= "\$application->setPassword(\"" .
            $this->getPassword() . "\");\n";
        $strOut .= "\$application->setParsedWithComments(" .
            ($this->isParsedWithComments() ? "true" : "false") . ");\n";
        $strOut .= "\$application->setConditionalParse(" .
            ($this->isConditionalParse() ? "true" : "false") . ");\n";
        $strOut .= "\$application->setLexiconAllowed(" .
            ($this->isLexiconAllowed() ? "true" : "false") . ");\n";
        $strOut .= "\$application->setBadGrammarIgnored(" .
            ($this->isBadGrammarIgnored() ? "true" : "false") . ");\n";
        $strOut .= "\$application->setAssertionsUsed(" .
            ($this->isAssertionsUsed() ? "true" : "false") . ");\n";
        $strOut .= "\$application->setScriptLanguage(\"" .
            $this->getScriptLanguage() . "\");\n";
        $strOut .= "\$application->setScriptFileDelimiter(\"" .
            $this->getScriptFileDelimiter() . "\");\n";
        $strOut .= "\$application->setDebug(" .
            ($this->isDebugAllowed() ? "true" : "false") . ");\n";
        $strOut .= "\$application->setTools(" .
            ($this->isToolsAllowed() ? "true" : "false") . ");\n";

        if (!is_null($this->getMaskedFileDelimiters())) {
            $strOut .= "\$application->setMaskedFileDelimiters(\"" .
                implode(",", $this->getMaskedFileDelimiters()) . "\");\n";
        }
        $strOut .= "\$application->setCharacterEncoding(\"" .
            $this->getCharacterEncoding() . "\");\n";
        $strOut .= "\$application->setSecurity(\"" .
            $this->getSecurity() . "\");\n";

        $strOut .= "\$application->setIgnoreFuseactionVariable(" .
            ($this->ignoreFuseactionVariable() ? "true" : "false") . ");\n";
        // end paramenters

        $controllerClass = $this->getControllerClass();

        $strOut .= $controllerClass . 
            "::getInstance()->addApplication(\$application);\n\n";
        $strOut .= $this->getCircuitsCachedCode();
        $strOut .= $this->getGlobalFuseactionCode();
        $strOut .= $this->getClassesCacheCode();
        $strOut .= $this->getPluginsCacheCode();
        return $strOut;
    }

    /**
     * Returns all application circuits cache code
     * 
     * @return string
     */
    private function getCircuitsCachedCode()
    {
        $strOut = "";        
        foreach ($this->circuits as $circuit) {
            if ($circuit->getName() != 'MYFUSES_GLOBAL_CIRCUIT') {
                $bCircuitClass = "Candango\\MyFuses\\Core\\BasicCircuit";
                $strOut .= "\$circuit = new " . $bCircuitClass . "();\n";
                $strOut .= "\$circuit->setName(\"" . $circuit->getName() .
                    "\" );\n";
                $strOut .= "\$circuit->setPath(\"" .
                    addslashes($circuit->getPath()) . "\");\n";
                $strOut .= "\$circuit->setParentName(\"" .
                    $circuit->getParentName() . "\");\n";
                $strOut .= "\$application->addCircuit(\$circuit);\n\n";
            }
        }
        return $strOut;
    }

    private function getGlobalFuseactionCode()
    {
        $bCircuitClass = "Candango\\MyFuses\\Core\\BasicCircuit";
        $strOut = str_replace('$circuit = ' . $this->getControllerClass() .
            '::getApplication("' . $this->getName() .
            '")->getCircuit("MYFUSES_GLOBAL_CIRCUIT");',
            "\$circuit = new " . $bCircuitClass .
            "();\n\$circuit->setName(\"MYFUSES_GLOBAL_CIRCUIT\");",
            $this->getCircuit('MYFUSES_GLOBAL_CIRCUIT')->getCachedCode());
        $strOut .= "\$application->addCircuit(\$circuit);\n\n";
        return $strOut;
    }

    private function getClassesCacheCode()
    {
        $strOut = "";        
        foreach ($this->classes as $class)
        {
            $strOut .= $class->getCachedCode() . "\n";
        }
        return $strOut;
    }

    private function getPluginsCacheCode()
    {
        $strOut = "";

        foreach ($this->plugins as $phase) {
            foreach ($phase as $plugin) {
                $strOut .= $plugin->getCachedCode() . "\n";    
            }
        }
        return $strOut;
    }

    /**
     * Add one application load listener
     *
     * @param ApplicationLoaderListener $listener
     */
    public function addLoadListener(ApplicationLoaderListener $listener)
    {
        $this->loaderListeners[] = $listener;
    }

    /**
     * Return all application load listerners
     *
     * @return array
     */
    public function getLoadListeners()
    {
        return $this->loaderListeners;
    }

    /**
     * Add one application builder listener
     *
     * @param ApplicationBuilderListener $listener
     */
    public function addBuilderListener(ApplicationBuilderListener $listener)
    {
        $this->builderListeners[] = $listener;
    }

    /**
     * Return all application builder listerners
     *
     * @return array
     */
    public function getBuilderListeners()
    {
        return $this->builderListeners;
    }

    /**
     * Return application data
     *
     * @return array
     */
    public function &getData()
    {
        return $this->data;
    }

    /**
     * Set application data
     *
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
