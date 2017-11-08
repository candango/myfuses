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

require_once MYFUSES_ROOT_PATH . "engine/MyFusesBuilder.php";

/**
 * BasicBuilder - BasicBuilder
 * Basic implementation of a MyFusesBuilder.
 *
 * @category   controller
 * @package    myfuses.engine
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      4ea81cee237c94b5349825934ecad7e2675c7355
 */
class BasicBuilder implements MyFusesBuilder
{
    private $application;

    private $applicationBuilderListeners = array();

    /**
     * Returns the builder application application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set builder application
     *
     * @param Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    public function unsetApplication()
    {
        $this->application = null;
    }

    public static function buildApplication(Application $application)
    {
        $data = &$application->getLoader()->getCachedApplicationData();

        if ($application->mustParse()) {
            if (count($data['application']['children'])) {
                foreach ($data['application']['children'] as $child) {
                    switch($child['name' ]) {
                        case "circuits":
                            self::buildCircuits($application, $child);
                            break;
                        case "classes":
                            self::buildClasses($application, $child);
                            break;
                        case "parameters":
                            self::buildParameters($application, $child);
                            break;
                        case "globalfuseactions":
                            self::buildGlobalFuseActions($application, $child);
                            break;
                        case "plugins":
                            self::buildPlugins($application, $child);
                            break;    
                    }            
                }
            }
            // TODO destroy application cache
            //$application->getLoader()->destroyCachedApplicationData();
        } else {
            if (isset($data['application']['children'])) {
                if (count($data['application']['children'])) {
                    foreach ($data['application']['children'] as $child) {
                        switch($child['name']) {
                            case "globalfuseactions":
                                #TODO: Are we going too brute force here!?
                                self::buildGlobalFuseActions($application,
                                    $child);
                                break;    
                        }            
                    }
                }
            }
        }

        /*foreach ($this->getApplication()->getCircits() as $circuit)
        {
            if ($circuit->getName() != "MYFUSES_GLOBAL_CIRCUIT")
            {
                $this->buildCircuit($circuit);
            }
        }*/
        // FIXME call build listeners from application
        foreach ($application->getBuilderListeners() as $listener) {
            $listener->applicationBuildPerformed($application,
                $application->getLoader()->getCachedApplicationData());
        }
    }

    protected static function buildCircuits(Application $application, &$data)
    {
        if (count($data['children'] > 0)) {
            foreach ($data['children'] as $child) {
                $name = "";
                $path = "";
                $parent = "";
                foreach ($child['attributes'] as $attributeName => $attribute) {
                    switch ($attributeName) {
                        case "name":
                        case "alias":
                            $name = $attribute;
                            break;
                        case "path":
                            $path = $attribute;
                            break;
                        case "parent":
                            $parent = $attribute;
                            break;
                    }
                }

                if ($application->hasCircuit($name)) {
                    $circuit = $application->getCircuit($name);
                } else {
                    $circuit = new BasicCircuit();    
                }

                //TODO handle this parameters changes
                $circuit->setName($name);
                $circuit->setPath($path);
                $circuit->setParentName($parent);

                $application->addCircuit($circuit);

                $circuit->unsetPreFuseAction();
                $circuit->unsetPostFuseAction();

                //self::buildCircuit( $circuit );
            }
        }
    }

    public static function buildCircuit(Circuit $circuit)
    {
        $data = $circuit->getData();

        $access = "";
        $file = "";
        $permissions = "";
        $security = "";

        if (isset($data['attributes'])) {
            foreach ($data['attributes'] as $attributeName => $attribute) {
                switch ($attributeName) {
                    case "access":
                        $access = $attribute;
                        break;
                    case "file":
                        $file = $attribute;
                        break;
                    case "permissions":
                        $permissions = $attribute;
                        break;
                    case "security":
                        $security = $attribute;
                        break;
                }
            }
        }
        $circuit->setFile($file);
        $circuit->setAccessByString($access);
        $circuit->setPermissions($permissions);
        $circuit->setSecurity($security);

        if (isset($data['docNamespaces'])) {
            $circuit->setVerbPaths(serialize($data['docNamespaces']));
        }

        if (isset($data['namespaceattributes'])) {
            foreach ($data['namespaceattributes'] as
                     $namespace => $attributes) {
                foreach ($attributes as $name => $value) {
                    $circuit->setCustomAttribute($namespace, $name, $value);
                }
            }
        }

        if (array_key_exists('children', $data)) {
            if (!is_null($data['children']) &&
                count($data[ 'children'] > 0)) {
                foreach ($data['children'] as $child) {
                    switch ($child['name']) {
                        case "fuseaction":
                        case "action":
                            self::buildAction($circuit, $child);
                            break;
                        case "prefuseaction":
                        case "postfuseaction":
                            self::buildGlobalAction($circuit, $child);
                            break;
                    }
                }
            }
        }

        $circuit->setBuilt(true);
    }

    /**
     * Builds action
     * 
     * @param Circuit $circuit
     * @param array $data
     */
    public static function buildAction(Circuit $circuit, $data)
    {
        if (is_null($data)) {
            return false;
        }

        $name = "";
        $class = null;
        $path = null;
        $default = null;
        $permissions = "";
        $security = "";

        $customAttribute = array();

        foreach ($data['attributes'] as $attributeName => $attribute) {
            switch ($attributeName) {
                case "name":
                    $name = $attribute;
                    break;
                case "class":
                    $class = $attribute;
                    break;
                case "path":
                    $path = $attribute;
                    break;
                case "default":
                    $default = $attribute;
                    break;
                case "security":
                    $security = $attribute;
                    break;
                case  "permissions":
                    $permissions = $attribute;
            }

            if (strpos($attributeName, "_ns_" ) !== false) {
                list($namespace, $attrName) = explode("_ns_", $attributeName);
                $customAttribute[$namespace][$attrName] = $attribute;
            }
        }

        if (!is_null($path)) {
            if (!MyFusesFileHandler::isAbsolutePath($path)) {
                $path = $circuit->getApplication()->getPath() . $path;
            }
            require_once $path;    
        }

        if (is_null($class)) {
            $action = new FuseAction($circuit);
        } else {
            $action = new $class($circuit);
        }

        foreach ($customAttribute as $namespace => $attributes) {
            foreach($attributes as $attribute => $value) {
                $action->setCustomAttribute($namespace, $attribute, $value);
            }
        }

        if (!is_null($path)) {
            $action->setPath($path);
        }

        $action->setName($name);
        $action->setDefault($default);
        $action->setPermissions($permissions);
        $circuit->addAction($action);
        $action->setSecurity($security);

        if (isset($data['children'])) {
            if (count($data['children']) > 0) {
                foreach ($data['children'] as $child) {
                    self::buildVerb($action, $child);
                }
            }
        }

        return true;
    }

    /**
     * Build global action
     *
     * @param Circuit $circuit
     * @param array $data
     */
    protected function buildGlobalAction(Circuit $circuit, &$data) {
        $action = new FuseAction($circuit);
        $action->setName($data['name']);

        if (isset($data['children' ])) {
            if( count( $data[ 'children' ] ) > 0 ) {
	            foreach( $data[ 'children' ] as $child ) {
	                self::buildVerb( $action, $child );
	            }
	        }
        }

        switch ($action->getName()) {
            case "prefuseaction":
                $circuit->setPreFuseAction($action);
                break;
            case "postfuseaction":
                $circuit->setPostFuseAction($action);
                break;
        }
    }

    /**
     * Build the verb
     * 
     * @param CircuitAction $action
     * @param arrya $data
     */
    protected function buildVerb(CircuitAction $action, &$data) {
        $verb = AbstractVerb::getInstance( $data, $action );
        if (!is_null($verb)) {
            $action->addVerb($verb);
        }
    }

    /**
     * Builds all application classes
     *
     * @param Application $application
     * @param array $data
     */
    protected function buildClasses(Application $application, &$data) {
        // TODO: Check if we can remove this from the code
        $parameterAttributes = array(
            "name" => "name",
            "classPath" => "path"
        );

        if (isset($data['children'])) {
            if (count($data['children'] > 0)) {
                foreach( $data[ 'children' ] as $child ) {
                    self::buildClass($application, $child);
                }
            }
        }
    }

    protected static function buildClass(Application $application, &$data) {
        $name = "";
        $path = "";

        foreach ($data['attributes'] as $attributeName => $attribute) {
            switch ($attributeName) {
                case "name":
                case "alias":
                    $name = "" . $attribute;
                    break;
                case "classpath":
                    $path = "" . $attribute;
                    break;
            }
        }

        if (isset($name)) {
            if ($name != "") {
                $class = new ClassDefinition();
                $class->setName($name);
                $class->setPath($path);
                $application->addClass($class);
            }
        }
    }

    /**
     * Build all application parameters
     *
     * @param Application $application
     * @param array $data
     */
    protected static function buildParameters(Application $application, &$data)
    {
        // Setting default parameters
        $application->setFuseactionVariable("fuseaction");
        $application->setRewrite(false);
        $application->setIgnoreFuseactionVariable(false);
        $application->setDebug(false);
        $application->setCharacterEncoding("UTF-8");
        $application->setLexiconAllowed(true);
        $application->setStrictMode(false);
        $application->setSecurity("optimistic");

        if (count($data['children'] > 0)) {
            foreach ($data['children'] as $child) {
                $name = "";
                $value = "";
                foreach ($child['attributes'] as $attributeName => $attribute) {
                    switch ($attributeName) {
                        case "name":
                            $name = $attribute;
                            break;
                        case "value":
                            $value = $attribute;
                            break;
                    }
                }

                switch ($name) {
                    case "fuseactionVariable":
                        $application->setFuseactionVariable($value);
                        break;
                    case "defaultFuseaction":
                        $application->setDefaultFuseaction($value);
                        break;
                    case "precedenceFormOrUrl":
                        $application->setPrecedenceFormOrUrl($value);
                        break;
                    case "debug":
                        $application->setDebug($value);
                        break;
                    case "tools":
                        $application->setTools($value);
                        break;
                    case "mode":
                        $application->setMode($value);
                        break;
                    case "strictMode":
                        $application->setStrictMode($value);
                        break;
                    case "password":
                        $application->setPassword($value);
                        break;
                    case "parseWithComments":
                        $application->setParsedWithComments($value);
                        break;
                    case "conditionalParse":
                        $application->setConditionalParse($value);
                        break;
                    case "allowLexicon":
                        $application->setLexiconAllowed($value);
                        break;
                    case "ignoreBadGrammar":
                        $application->setLexiconAllowed($value);
                        break;
                    case "useAssertions":
                        $application->setAssertionsUsed($value);
                        break;
                    case "scriptLanguage":
                        $application->setScriptLanguage($value);
                        break;
                    case "scriptFileDelimiter":
                        $application->setScriptFileDelimiter($value);
                        break;
                    case "maskedFileDelimiters":
                        $application->setMaskedFileDelimiters($value);
                        break;
                    case "characterEncoding":
                        $application->setCharacterEncoding($value);
                        break;
                    case "security":
                        $application->setSecurity($value);
                        break;
                    case "rewrite":
                        $application->setRewrite($value);
                        break;
                    case "ignoreFuseactionVariable":
                        $application->setIgnoreFuseactionVariable($value);
                        break;
                }

                // putting into $application
                // TODO: this is not being hit
                // TODO: also there is a variable not set being referenced
                // TODO: check if that should go
                if (isset($applicationParameters[$name])) {
                    echo "<br>";
                    print_r($name);
                    echo "<br>";
                    print_r($value);
                    $application->$applicationParameters[$name]($value);
                }
            }
        }
    }

    /**
     * Build global fuseaction
     *
     * @param Application $application
     * @param array $data
     */
    protected static function buildGlobalFuseActions(
        Application $application,
        &$data
    ) {
        $globalActionMethods = array(
            "preprocess" => "getPreProcessFuseAction",
            "postprocess" => "getPostProcessFuseAction"
        );

        $circuit = new BasicCircuit();
        $circuit->setName("MYFUSES_GLOBAL_CIRCUIT");
        $circuit->setPath($application->getPath());
        $circuit->setAccessByString("internal");

        $application->addCircuit($circuit);

        if (count($data['children']) > 0) {
            foreach ($data['children'] as $child) {
                $action = new FuseAction($circuit);
                $action->setName(str_replace( "get", "",
                    $globalActionMethods[$child['name']]));

                if (isset($child['children'])) {
                    if (count($child['children'])) {
                        foreach ($child['children'] as $actionChild) {
                            self::buildVerb($action, $actionChild);
                        }
                    }
                }

                $circuit->addAction($action);
            }
        }

        if (isset($globalActionMethods[$action->getName()])) {
            $circuit->getApplication()->$globalActionMethods[
                $action->getName()]($action);
        }
    }

    protected function buildPlugins(Application $application, &$data)
    {
        $application->clearPlugins();
        if(count($data[ 'children'])) {
            foreach ($data[ 'children' ] as $child) {
                self::buildFase($application, $child);
            }
        }
    }

    protected function buildFase(Application $application, &$data)
    {
        // TODO: This is not being used, check if we can remove it
        $faseParams = array(
            'name' => 'name',
            'path' => 'path',
            'template' => 'file',
            'file' => 'file'
        );

        $phase = $data['attributes']['name'];

        if (isset($data['children' ])) {
            if (count($data['children'])) {
                foreach ($data['children'] as $child) {
                    $name = "";
                    $path = "";
                    $file = "";

                    foreach ($child['attributes'] as
                        $attributeName => $attribute) {
                        switch ($attributeName) {
                            case "name":
                                $name = $attribute;
                                break;
                            case "path":
                                $path = $attribute;
                                break;
                            case "file":
                            case "template":
                                break;
                        }
                    }

                    $parameters = array();

                    if (isset($child['children'])) {
                        foreach ($child['children'] as $key => $paramChild) {
                            if (strtolower($paramChild['name']) ==
                                "parameter") {
                                $param = array (
                                    'name' => $paramChild[
                                        'attributes']['name'],
                                    'value' => $paramChild[
                                        'attributes']['value']
                                );
                                $parameters[] = $param;
                            }
                        }
                    }

                    AbstractPlugin::getInstance($application, $phase, $name,
                        $path, $file, $parameters);
                }
            }
        }
    }

    /**
     * Add one application builder listener
     *
     * @param MyFusesApplicationBuilderListener $listener
     */
    public function addApplicationBuilderListener(
        MyFusesApplicationBuilderListener $listener
    ) {
        $this->applicationBuilderListeners[] = $listener;
    }

    /**
     * Return all application builder listerners
     *
     * @return array
     */
    private function getApplicationBuilderListeners()
    {
        return $this->applicationBuilderListeners;
    }
}
