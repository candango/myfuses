<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses
{

    use Candango\MyFuses\Core\Application;
    use Candango\MyFuses\Core\Plugin;
    use Candango\MyFuses\Core\Verbs\DoVerb;
    use Candango\MyFuses\Engine\Builder;
    use Candango\MyFuses\Exceptions\ActionException;
    use Candango\MyFuses\Exceptions\ApplicationException;
    use Candango\MyFuses\Exceptions\CircuitException;
    use Candango\MyFuses\Exceptions\MyFusesException;
    use Candango\MyFuses\I18n\I18nHandler;
    use Candango\MyFuses\Process\Context;
    use Candango\MyFuses\Process\DebugEvent;
    use Candango\MyFuses\Process\Debugger;
    use Candango\MyFuses\Process\FuseRequest;
    use Candango\MyFuses\Process\Lifecycle;
    use Candango\MyFuses\Util\FileHandler;

    define("Candango\\MyFuses\\ROOT_PATH", dirname(__FILE__));

    // cleaning file functions cache
    // As per documentation
    // http://php.net/manual/en/function.clearstatcache.php
    // We do that: "if the same file is being checked multiple times within a
    // single script, and that file is in danger of being removed or changed
    // during that script's operation, you may elect to clear the
    // status cache."
    // TODO: Use this function for the files being handled by the framework
    // TODO: Meaning: myfuses/fusebox.xml and circuit.xml
    clearstatcache();

    /**
     * MyFuses Controller - Controller.php
     *
     * Myfuses is a Framework that helps design, develop and maintain PHP
     * applications. It is based on Fusebox and was designed to be more
     * extensible and stable.
     *
     * The Controller register applications and handle requests based on
     * a application.circuit.action value defined on the fuseaction variable
     * received by get.
     *
     * MyFuses will convert paths sent to the main controller script and
     * transform them to the application.circuit.action pattern.
     *
     * @category   controller
     * @package    Candango.Myfuses
     * @author     Flavio Garcia <piraz at candango.org>
     * @since      f06b361b3bc6909ebf21f108d42b79a17cfb3924
     */
    class Controller
    {

        const MODE_DEVELOPMENT = "development";
        const MODE_PRODUCTION = "production";

        const ROOT_PATH = ROOT_PATH;


        /**
         * Controller builder
         *
         * @var Engine\BasicBuilder
         */
        private $builder;

        /**
         * Memcache enabled flag
         *
         * @var boolean
         */
        private $memcacheEnabled = false;

        /**
         * All myfuses memcache servers
         *
         * @var array
         */
        private $memcaheServers;

        /**
         * MyFuses memcache instance
         *
         * @var Memcache
         */
        private $memcache;

        /**
         * Path used by myfuses to search some plugin
         *
         * @var array
         */
        private $pluginPaths = array();

        /**
         * Path used by myfuses to search I18n files
         *
         * @var array
         */
        private $i18nPaths = array();

        /**
         * Path used by myfuses to search verbs
         *
         * @var array
         */
        private $verbPaths = array();

        /**
         * Unique controller instance to be created/referenced during the
         * request. It is returned by the getInstance method following a
         * singleton pattern.
         *
         * @var Controller
         */
        protected static $instance;

        /**
         * Array of registered applications
         *
         * @var array
         */
        protected $applications = array();

        /**
         * The MyFuses request instance
         *
         * @var FuseRequest
         */
        private $request;

        /**
         * Instance Lifecycle
         *
         * @var Lifecycle
         */
        private $lifecycle;

        /**
         * Default debugger
         *
         * @var Debugger
         */
        private $debugger;

        /**
         * Controller(Framework) parsed path.
         *
         * This path will be used to compose applications parsed path.
         *
         * @var string
         */
        private $parsedPath;

        /**
         * Default class to be used when registering an application in the
         * controller instance.
         *
         * @var string
         */
        private $applicationClass = "Candango\\MyFuses\\Core\\BasicApplication";

        /**
         * Default response type to be set in the header.
         *
         * It is possible to change this programmatically or using a verb.
         *
         * @var string
         */
        private $responseType = "text/html";

        const VERSION = "0.9.9";

        /**
         * I18n type flag. Default native.
         *
         * @var string
         */
        private static $i18nType = I18nHandler::NATIVE_TYPE;

        /**
         * MyFuses Controller constructor
         */
        protected function __construct()
        {
            $this->builder = new Engine\BasicBuilder();
            $this->debugger = new Process\Debugger();

            $pathStr = str_replace(array(DIRECTORY_SEPARATOR, ':', '/'), '_',
                dirname($_SERVER['SCRIPT_FILENAME']));

            $pathStr = str_replace('__', '_', $pathStr);

            if (substr($pathStr, 0, 1) == "_") {
                $pathStr = substr($pathStr, 1, strlen($pathStr));
            }

            $projectPath = FileHandler::sanitizePath(
                realpath(ROOT_PATH . DIRECTORY_SEPARATOR . ".." .
                    DIRECTORY_SEPARATOR . "..")
            );

            $parsedPath = $projectPath . "parsed" . DIRECTORY_SEPARATOR . $pathStr;

            $this->setParsedPath($parsedPath);

            // adding plugin paths
            $this->addPluginPath("plugins" . DIRECTORY_SEPARATOR);

            $this->addPluginPath(ROOT_PATH . DIRECTORY_SEPARATOR . "Plugins");

            // adding I18n paths
            $this->addI18nPath($projectPath . "i18n");
            $this->addI18nPath("i18n" . DIRECTORY_SEPARATOR);

            // adding verb paths
            $this->addVerbPath(ROOT_PATH);
        }

        /**
         * Return I18n type
         *
         * @return string
         */
        public static function getI18nType()
        {
            return self::$i18nType;
        }

        /**
         * Set I18n type
         *
         * @param string $i18nType
         */
        public static function setI18nType($i18nType)
        {
            self::$i18nType = $i18nType;
        }

        /**
         * Add one plugin path. MyFuses will be search plugins in this paths if
         * no path was informed.
         *
         * @param string $path
         */
        public function addPluginPath($path)
        {
            $this->pluginPaths[] = $path;
        }

        /**
         * Return all plugin paths
         *
         * @return array
         */
        public function getPluginPaths()
        {
            return $this->pluginPaths;
        }

        /**
         * Add one I18n path to myfuses
         *
         * @param string $path
         */
        public function addI18nPath($path)
        {
            $this->i18nPaths[] = $path;
        }

        /**
         * Return all I18n paths
         *
         * @return array
         */
        public function getI18nPaths()
        {
            return $this->i18nPaths;
        }

        /**
         * Add one verb path. MyFuses will be search verbs in this paths if
         * no path was informed.
         *
         * @param string $path
         */
        public function addVerbPath($path)
        {
            $this->verbPaths[] = $path;
        }

        /**
         * Return all verb paths
         *
         * @return array
         */
        public function getVerbPaths()
        {
            return $this->verbPaths;
        }

        /**
         * Enable/disable the memcache feature
         *
         * @param boolean $enable
         */
        public function enableMemcache($enable)
        {
            $this->memcacheEnabled = $enable;
        }

        /**
         * Add a memcache server to controller
         *
         * @param MyFusesMemcacheServer $server
         */
        public function addMemcacheServer(MyFusesMemcacheServer $server)
        {
            $this->memcaheServers[] = $server;
        }

        /**
         * Return all memcache servers
         *
         * @return array
         */
        private function getMemcacheServers()
        {
            return $this->memcaheServers;
        }

        /**
         * Return the memcache object
         *
         * @return Memcache
         */
        public function getMemcache()
        {
            return $this->memcache;
        }

        /**
         * Set the memcache object
         *
         * @param Memcache $memcache
         */
        private function setMemcache(Memcache $memcache)
        {
            $this->memcache = $memcache;
        }

        /**
         *  Add servers to mencache object
         */
        private function configureMemcache()
        {
            if (is_null($this->getMemcache())) {
                $this->setMemcache(new Memcache());
            }

            foreach ($this->getMemcacheServers() as $server) {
                $server->configureMemcache($this->getMemcache());
            }
        }

        /**
         * Return if the memcache is enabled
         *
         * @return boolean
         */
        public function isMemcacheEnabled()
        {
            return $this->memcacheEnabled;
        }

        public function getParsedPath()
        {
            return $this->parsedPath;
        }

        protected function setParsedPath($parsedPath)
        {
            $this->parsedPath = $parsedPath;
        }

        protected function getApplicationClass()
        {
            return $this->applicationClass;
        }

        protected function setApplicationClass($appClass)
        {
            $this->applicationClass = $appClass;
        }

        public function getResponseType()
        {
            return $this->responseType;
        }

        public function setResponseType($response)
        {
            $this->responseType = $response;
        }

        /**
         * Create a named application to be processed by the framework.
         *
         * Provide a config array to change the default path and file to be used by
         * this application.
         *
         * @param string $name
         * @param array $config
         * @return Application
         */
        public function createApplication(
            $name = Application::DEFAULT_APPLICATION_NAME,
            $config = null
        ) {
            $appClass = $this->getApplicationClass();
            $application = new $appClass($name);

            if (!is_null($config)) {
                if (isset($config['path'])) {
                    $application->setPath($config['path']);
                }
                if (isset($config['file'])) {
                    $application->setFile($config['file']);
                }

            } else {
                $application->setPath(dirname(str_replace("/",
                    DIRECTORY_SEPARATOR, $_SERVER['SCRIPT_FILENAME'])));
            }

            // setting parsed path
            $application->setParsedPath($this->getParsedPath().
                DIRECTORY_SEPARATOR . $application->getName() .
                DIRECTORY_SEPARATOR);

            $this->addApplication($application);

            return $application;
        }

        /**
         * Returns an existing application
         *
         * @param string $name
         * @return Application
         * @throws ApplicationException
         */
        public static function getApplication(
            $name = Application::DEFAULT_APPLICATION_NAME
        ) {
            if (isset(self::getInstance()->applications[$name])) {
                return self::getInstance()->applications[$name];
            }

            $params = array("applicationName" => $name);
            throw new ApplicationException($params,
                ApplicationException::NON_EXISTENT_APPLICATION);
        }

        /**
         * Returns if the application exisits
         *
         * @param string $name
         * @return boolean
         */
        public static function hasApplication($name)
        {
            try {
                self::getApplication($name);
                return true;
            } catch (ApplicationException $ae) {
                return false;
            }
        }

        /**
         * Returns an array of registered applications
         *
         * @return array
         */
        public function &getApplications()
        {
            return $this->applications;
        }

        /**
         * Add one application to controller
         *
         * @param Application $application
         */
        public function addApplication(Application $application)
        {
            if (count($this->applications) == 0) {
                $application->setDefault(true);
            }

            $application->setController($this);

            $this->applications[$application->getName()] = $application;

            $application->setController($this);

            $appDefaultName = Application::DEFAULT_APPLICATION_NAME;

            if ($appDefaultName != $application->getName()) {
                if ($application->isDefault()) {
                    if (isset($this->applications[$appDefaultName])) {
                        $this->applications[$appDefaultName]->setDefault(false);
                    }
                    $this->applications[$appDefaultName] =
                        &$this->applications[$application->getName()];
                }
            }
        }

        protected function createRequest()
        {
            $this->request = new FuseRequest();
        }

        public function getCurrentPhase()
        {
            return Lifecycle::getPhase();
        }

        public function setCurrentPhase($phase)
        {
            Lifecycle::setPhase($phase);
        }

        public function getCurrentCircuit()
        {
            return Lifecycle::getAction()->getCircuit();
        }

        public function getCurrentAction()
        {
            return Lifecycle::getAction();
        }

        public function setCurrentAction($fuseaction)
        {
            list($appName, $cName, $aName) = explode(".", $fuseaction);
            Lifecycle::setAction($this->getApplication($appName)->
            getCircuit($cName)->getAction($aName));
        }

        public function setCurrentProperties($phase, $fuseaction)
        {
            $this->setCurrentPhase($phase);
            $this->setCurrentAction($fuseaction);
        }

        /**
         * Return the controller builder
         *
         * @return Builder
         */
        public function getBuilder()
        {
            return $this->builder;
        }

        /**
         * Return the controller debugger
         *
         * @return Debugger
         */
        public function getDebugger()
        {
            return $this->debugger;
        }

        /**
         * Returns the current request
         *
         * @return FuseRequest
         */
        public function getRequest()
        {
            return $this->request;
        }

        public function createApplicationPath(Application $application)
        {
            if (!file_exists($application->getParsedPath())) {
                mkdir($application->getParsedPath(), 0755, true);

                $path = explode(DIRECTORY_SEPARATOR,
                    substr($application->getParsedPath(), 0,
                        strlen($application->getParsedPath()) - 1));

                while (FileHandler::sanitizePath($this->getParsedPath()) != (
                        implode(DIRECTORY_SEPARATOR, $path) .
                        DIRECTORY_SEPARATOR)) {
                    // TODO: Review the chmod permission
                    chmod(implode(DIRECTORY_SEPARATOR, $path), 0755);
                    $path = array_slice($path, 0, count($path) - 1);
                }
            }
        }

        protected function storeApplication(Application $application)
        {
            $strStore = "";

            if ($application->mustStore()) {
                if (!$this->isMemcacheEnabled()) {
                    $this->createApplicationPath($application);

                    $strStore .= $application->getCachedCode();

                    $fileName = $application->getCompleteCacheFile();

                    self::getInstance()->getDebugger()->registerEvent(
                        new DebugEvent(Debugger::MYFUSES_CATEGORY,
                            "Application " . $application->getName() . " Stored"));

                    FileHandler::writeFile($fileName, "<?php\n" . $strStore);
                } else {
                    $this->getMemcache()->set($application->getTag(),
                        serialize($application->getLoader()->
                        getCachedApplicationData()));
                }
            }

            foreach ($application->getCircuits() as $circuit) {
                if ($circuit->getName() !== "MYFUSES_GLOBAL_CIRCUIT") {
                    if ($circuit->isLoaded()) {
                        $fileName = $circuit->getCompleteCacheFile();
                        $dataFileName = $circuit->getCompleteCacheDataFile();
                        FileHandler::writeFile($fileName, "<?php\n" .
                            $circuit->getCachedCode());
                        FileHandler::writeFile($dataFileName,
                            serialize($circuit->getData()));
                    }
                }
            }
        }

        /**
         * Sotore all myfuses applications
         */
        protected function storeApplications()
        {
            foreach ($this->applications as $index => $application) {
                if ($index != Application::DEFAULT_APPLICATION_NAME) {
                    $this->storeApplication($application);
                }
            }
        }

        /**
         * This method parse the request and write the genereted
         * string in one file
         */
        public function parseRequest()
        {
            $circuit = $this->request->getAction()->getCircuit();

            $controllerName = $circuit->getApplication()->getControllerClass();

            $application = $circuit->getApplication();

            $requestApplication = $this->request->getApplication();

            $path = $requestApplication->getParsedPath() .
                $this->request->getCircuitName() . DIRECTORY_SEPARATOR;

            $fileName = $path . $this->request->getActionName() . ".action.php";

            $contextClass = "Candango\\MyFuses\\Process\\Context";
            $processExceptionClass = "Candango\\MyFuses\\Exceptions\\" .
                "ProcessException";

            // TODO handle file parse
            if (!is_file($fileName) || $circuit->isModified()) {
                $fuseQueue = $this->request->getFuseQueue();

                $myFusesString = $controllerName . "::getInstance()";

                $actionString = "\"" . $requestApplication->getName() .
                    "." . $this->request->getCircuitName() .
                    "." . $this->request->getActionName() . "\"";

                $strParse = "try {\n";

                // Started the global output buffer control
                $strParse .= "\tob_start();\n";

                $strParse .= "\t" . $myFusesString .
                    "->setCurrentProperties(\"" .
                    Lifecycle::PRE_PROCESS_PHASE . "\", " .
                    $actionString . ");\n\n";

                // parsing pre process plugins
                if (count($application->getPlugins(
                    Plugin::PRE_PROCESS_PHASE))) {
                    $pluginsStr = $controllerName .
                        "::getInstance()->getApplication(\"" .
                        $application->getName() . "\")->getPlugins(" .
                        "\"" . Plugin::PRE_PROCESS_PHASE . "\")";
                    $strParse .= "\tforeach (" . $pluginsStr .
                        " as \$plugin) {\n";
                    $strParse .= "\t\t\$plugin->run();\n\t}\n";
                    $strParse .= "\tforeach (" . $contextClass .
                        "::getContext() as \$key => \$value) " .
                        "{global \$\$value;}\n\n";
                }
                //end parsing pre process plugins
                foreach ($fuseQueue->getPreProcessQueue() as $parseable) {
                    $strParse .= $parseable->getParsedCode(
                        $requestApplication->isParsedWithComments(),
                        1);
                }

                foreach ($fuseQueue->getProcessQueue() as $parseable) {
                    $strParse .= $parseable->getParsedCode(
                        $requestApplication->isParsedWithComments(),
                        1);
                }
                $strParse .= "\t" . $myFusesString .
                    "->setCurrentProperties(\"" .
                    Lifecycle::POST_PROCESS_PHASE . "\", " .
                    $actionString . ");\n\n";

                $selector = true;

                foreach ($fuseQueue->getPostProcessQueue() as $parseable) {
                    $strParse .= $parseable->getParsedCode(
                        $requestApplication->isParsedWithComments(),
                        1);
                }

                // parsing post process plugins
                if (count($application->getPlugins(
                    Plugin::POST_PROCESS_PHASE))) {
                    $strParse .= "\t" . $myFusesString .
                        "->setCurrentProperties(\"" .
                        Lifecycle::POST_PROCESS_PHASE . "\", " .
                        $actionString . " );\n\n";
                    $pluginsStr = $controllerName .
                        "::getInstance()->getApplication(\"" .
                        $application->getName() . "\")->getPlugins(" .
                        " \"" . Plugin::POST_PROCESS_PHASE . "\" )";
                    $strParse .= "\tforeach(" . $pluginsStr .
                        " as \$plugin) {\n";
                    $strParse .= "\t\t\$plugin->run();\n\t}\n\n";
                }
                //end parsing post process plugins

                $strParse .= "\t\$strContent = " . $controllerName .
                    "::getInstance()->getResponseType() . \"; charset=\" . " .
                    $controllerName . "::getInstance()->getRequest()->" .
                    "getApplication()->getCharacterEncoding();\n";
                $strParse .= "\theader(\"Content-Type: \" . \$strContent);\n";
                // Flushed global output buffer content
                $strParse .= "\tob_end_flush();\n";
                $strParse .= "} catch (" . $processExceptionClass .
                    " \$pe) {\n";

                if (count($application->getPlugins(
                    Plugin::PROCESS_ERROR_PHASE))) {
                    $pluginsStr = $controllerName .
                        "::getInstance()->getApplication( \"" .
                        $application->getName() . "\" )->getPlugins(" .
                        " \"" . Plugin::PROCESS_ERROR_PHASE . "\" )";
                    $strParse .= "\tforeach(" . $pluginsStr .
                        " as \$plugin) {\n";
                    $strParse .= "\t\t\$plugin->handle( \$pe );\n\t}\n";
                    $strParse .= "\tforeach(" . $contextClass .
                        "::getContext() as " .
                        " \$key => \$value) {global \$\$value;}\n\n";
                }
                $strParse .= "}";

                $this->createApplicationPath($application);

                if (!file_exists($path)) {
                    mkdir($path);
                    // TODO: Review the chmod permission here
                    chmod($path, 0755);
                }

                FileHandler::writeFile($fileName, "<?php\n" .
                    Context::sanitizeHashedString($strParse));

                self::getInstance()->getDebugger()->registerEvent(
                    new DebugEvent(Debugger::MYFUSES_CATEGORY,
                        "Fuseaction " .
                        $this->getRequest()->getFuseActionName() .
                        " Compiled"));
            }

            Context::includeFile($fileName);
        }

        public static function includeFile($file)
        {
            include $file;
        }

        private function configureApplications()
        {
            foreach ($this->getApplications() as $index => $application) {
                if ($index != Application::DEFAULT_APPLICATION_NAME) {
                    $this->configureApplication($application);
                }
            }
        }

        protected function configureApplication(Application $application)
        {

        }

        /**
         * Process the user request
         */
        public function doProcess()
        {
            try {
                Lifecycle::configureLocale();

                if ($this->isMemcacheEnabled()) {
                    $this->configureMemcache();
                }

                // initilizing application if necessary
                Lifecycle::loadApplications();
                Lifecycle::buildApplications();
                Lifecycle::enableTools();

                $this->createRequest();
                $this->configureApplications();
                $this->parseRequest();

                self::getInstance()->getDebugger()->registerEvent(
                    new DebugEvent(Debugger::MYFUSES_CATEGORY,
                        "Request completed"));

                // storing all applications if necessary
                $this->storeApplications();

                Lifecycle::storeLocale();

                if ($this->getApplication()->isDebugAllowed()) {
                    print $this->getDebugger();
                }
            } catch (MyFusesException $e) {
                $e->breakProcess();
            }
        }

        /**
         * Returns one instance of MyFuses. Only one instance is created per
         * process.
         * MyFuses is implemented using the singleton pattern.
         *
         * @return Controller
         * @static
         */
        public static function getInstance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new Controller();
            }
            return self::$instance;
        }

        public static function getXfa($name)
        {
            return self::getInstance()->getRequest()->getAction()->getXfa(
                $name);
        }

        public static function getSelfPath()
        {
            $self = self::getProtocol() . "://" . $_SERVER['HTTP_HOST'];
            $self .= "/";

            if (substr($self, -1) == "/") {
                $self = substr($self, 0, strlen($self) - 1);
            }

            $self .= dirname($_SERVER['PHP_SELF']);

            if (substr($self, -1) != "/") {
                $self .= "/";
            }

            return $self;
        }

        public static function getRootUrl()
        {
            // TODO: It seems like request scheme isn't present all the time
            // TODO: We need to verify that
            // http://bit.ly/2yh6Yqo
            $rootUrl = self::getProtocol() . "://" . $_SERVER['HTTP_HOST'];

            if (substr($rootUrl, -1) != "/") {
                $rootUrl .= "/";
            }

            $scriptNameX = explode("/", $_SERVER['SCRIPT_NAME']);

            $pos = (count($scriptNameX) - 1);

            unset($scriptNameX[0]);
            unset($scriptNameX[$pos]);

            $rootDir = implode("/", $scriptNameX);

            $rootUrl = $rootUrl . ($rootDir==""? "": $rootDir . "/");

            return $rootUrl;
        }

        public static function getSelf()
        {
            $self = self::getProtocol() . "://" . $_SERVER['HTTP_HOST'];

            if (substr($self, -1) != "/") {
                $self .= "/";
            }

            // FIXME Fixing an error occurring with CGI.
            // FIXME Suppress redirect with CGI!!!
            if (self::isRewriting()) {
                $self1 = dirname($_SERVER['SCRIPT_NAME']);
                if (substr($self1, -1) != "/") {
                    $self1 .= "/";
                }
            } else {
                $self1 = $_SERVER['SCRIPT_NAME'];
            }

            if (substr($self1, 0, 1) == "/") {
                $self1 = substr($self1, 1, strlen($self1));
            }

            $self .= $self1;

            return $self;
        }

        public static function getProtocol()
        {
            // TODO: get from the parameter
            if(array_key_exists("REQUEST_SCHEME", $_SERVER)){
                return $_SERVER['REQUEST_SCHEME'];
            }
            return "http";
        }

        public static function getMySelf($showFuseactionVariable = true)
        {
            // FIXME Fixing an error occurring with CGI.
            // FIXME Suppress redirect with CGI!!!
            if (self::isRewriting()) {
                // If ignoreFuseactionVariable is true, we always don't return
                // it with the url
                if (self::getInstance()->getApplication()->
                    ignoreFuseactionVariable()) {
                    $showFuseactionVariable = false;
                }
                $mySelf = self::getSelf();
                if ($showFuseactionVariable) {
                    $mySelf .= self::getInstance()->getRequest()->
                        getApplication()->getFuseactionVariable() . "/";
                }
            } else {
                $mySelf = self::getSelf() . "?";
                $mySelf .= self::getInstance()->getRequest()->
                getApplication()->getFuseactionVariable();
                $mySelf .= "=";
            }

            return $mySelf;
        }

        public static function getMySelfXfa(
            $xfaName,
            $initQuery = false,
            $showFuseactionVariable = true
        ) {
            // FIXME Fixing an error occurring with CGI.
            // FIXME Suppress redirect with CGI!!!
            if (self::isRewriting()) {
                $xfaX = explode(".", self::getXfa($xfaName));

                $link = "";

                if (count($xfaX) == 1) {
                    if ($xfaX[0] == "") {
                        $link = self::getRootUrl();
                    } else {
                        $link = self::getMySelf($showFuseactionVariable) .
                            implode("/", explode(".", $xfaX));
                    }
                } else {
                    try {
                        $circuit = self::getApplication()->getCircuit($xfaX[0]);
                        try {
                            $action = $circuit->getAction($xfaX[1]);

                            if ($circuit->getName() . "." .
                                $action->getName() ==
                                self::getApplication()->
                                getDefaultFuseaction()) {
                                $link = self::getSelf();
                            } else if ($action->isDefault()) {
                                $link = self::getMySelf($showFuseactionVariable)
                                    . $xfaX[0];
                            } else {
                                $link = self::getMySelf(
                                    $showFuseactionVariable) .
                                    implode("/", $xfaX);
                            }
                        } catch (ActionException $mffae) {
                            $link = self::getMySelf($showFuseactionVariable) .
                                implode("/", $xfaX);
                        }
                    } catch (CircuitException $mfce) {
                        $link = self::getMySelf($showFuseactionVariable) .
                            implode("/", $xfaX);
                    }
                }
                if ($initQuery) {
                    $link .= "?";
                }
            } else {
                $link = self::getMySelf() . self::getXfa($xfaName);
                if ($initQuery) {
                    $link .= "&";
                }
            }
            return $link;
        }

        public static function doAction($actionName)
        {
            $actionNameX = explode(".", $actionName);
            if (count($actionNameX) < 3) {
                array_unshift($actionNameX,
                    self::getInstance()->getApplication()->getName());
            }
            $application = self::getInstance()->getApplication($actionNameX[0]);

            $circuit = $application->getCircuit($actionNameX[1]);

            $action = $circuit->getAction($actionNameX[2]);

            DoVerb::doAction($action);
        }

        /**
         * Returns true if the main controller will interpret a directory
         * request redirected by the web server to the main controller.
         *
         * To set myfuses to rewrite it is necessary to set the parameter
         * rewrite as true:
         *
         *     <parameter name="redirect" value="true" />
         *
         * @return bool
         */
        public static function isRewriting()
        {
            if (self::getInstance()->getApplication()->allowRewrite()) {
                return true;
            }

            /*if(isset($_SERVER['REDIRECT_URL' ]) &&
                Platform::getInstance()->getApplication()->allowRewrite() &&
                !Platform::strEndsWith($_SERVER['REQUEST_URI'], ".php")) {
                return true;
            }*/
            return false;
        }

        // From http://bit.ly/2wvhQ21
        // FIXME: This need to be on util
        public static function strStartsWith($haystack, $needle)
        {
            $length = strlen($needle);
            return (substr($haystack, 0, $length) === $needle);
        }

        public static function strEndsWith($haystack, $needle)
        {
            $length = strlen($needle);
            return $length === 0 ||
                (substr($haystack, -$length) === $needle);
        }

        /**
         * Includes core files.<br>
         * Throws IFBExeption when <code>file doesn't exists</code>.
         * In truth this method use require_once insted include_once.
         * Process must break if some core file doesn't exists.
         *
         * @param $file
         * @return void
         */
        /*public static function includeCoreFile($file)
        {
            if (file_exists($file)) {
                require_once $file;
            } else {
                throw new MyFusesMissingCoreFileException($file);
            }
        }*/

        public static function sendToUrl($url)
        {
            if (!headers_sent()) {
                header("Location: " . $url);
            } else {
                echo '<script type="text/javascript">';
                echo 'window.location.href="' . $url . '";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=' . $url .
                    '" />';
                echo '</noscript>';
            }
            die();
        }

        /**
         * MyFuses autoload method
         *
         * @param string $class
         */
        public static function autoload($class)
        {
            if (strpos($class, "Candango\\MyFuses")===0) {
                $pathX = array_slice(explode("\\", $class), 2);
                $file = ROOT_PATH . DIRECTORY_SEPARATOR .
                    join(DIRECTORY_SEPARATOR, $pathX) . ".php";
                require_once $file;
            }
        }

    }

    class MyFusesMemcacheServer
    {
        private $host;

        private $port;

        private $persistent;

        /**
         * Server constructor
         *
         * @param string $host
         * @param string $port
         * @param boolean $persistent
         */
        public function __construct(
            $host = null,
            $port = "11211",
            $persistent = false
        ) {
            $this->setHost($host);
            $this->setPort($port);
            $this->setPersistent($persistent);
        }

        public function getHost()
        {
            return $this->host;
        }

        public function setHost($host)
        {
            $this->host = $host;
        }

        public function getPort()
        {
            return $this->port;
        }

        public function setPort($port)
        {
            $this->port = $port;
        }

        public function isPersistent()
        {
            return $this->persistent;
        }

        public function setPersistent($persistent)
        {
            $this->persistent = $persistent;
        }

        public function configureMemcache(Memcache $memcache)
        {
            $memcache->addServer($this->getHost(), $this->getPort(),
                $this->isPersistent());
        }
    }

    spl_autoload_register("Candango\\MyFuses\\Controller::autoload");
}

namespace
{
    use Candango\MyFuses\Controller;
    use Candango\MyFuses\Security\AbstractSecurityManager;
    /**
     * This is an alias function to Platform::getMySelfXfa method.
     *
     * @param string $xfaName
     * @param boolean $initQuery
     * @param boolean $showFuseactionVariable
     * @return string
     */
    function xfa($xfaName, $initQuery = false, $showFuseactionVariable = true)
    {
        return Controller::getMySelfXfa($xfaName, $initQuery,
            $showFuseactionVariable);
    }

    /**
     * Creating security functions
     */

    /**
     * This function returns if the security credential is authenticated
     *
     * @return boolean Returns if the security credential is authenticated
     */
    function security_is_authenticated()
    {
        if (class_exists("AbstractSecurityManager")) {
            $manager = AbstractSecurityManager::getInstance();

            $credential = $manager->getCredential();
            return $credential->isAuthenticated();
        }
        return true;
    }
}
