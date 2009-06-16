<?php
require_once MyFuses::MYFUSES_ROOT_PATH . "engine/MyFusesBuilder.class.php";

/**
 * Interface that defines one My Fuses Loader
 * 
 * 
 */
class BasicMyFusesBuilder  implements MyFusesBuilder {
    
    private $application;

    private $applciationBuilderListeners = array();
    
    /**
     * Rerturn builder application
     *
     * @return Application
     */
    public function getApplication() {
        return $this->application;
    }
    
    /**
     * Set builder application
     *
     * @return Application
     */
    public function setApplication( Application $application ) {
        $this->application = $application;
    }
    
    public function unsetApplication(){
        $this->application = null;
    }
    
    public static function buildApplication( Application $application ) {
        
        $data = &$application->getLoader()->
                getCachedApplicationData();
        
        if( $application->mustParse() ) {
            if( count( $data[ 'application' ][ 'children' ] ) ) {
                foreach( $data[ 'application' ][ 'children' ] as $child ) {
                    switch( $child[ 'name' ] ) {
                        case "circuits":
                            self::buildCircuits( $application, $child );
                            break;
                        case "classes":
                            self::buildClasses( $application, $child );
                            break;
                        case "parameters":
                            self::buildParameters( $application, $child );
                            break;
                        case "globalfuseactions":
                            self::buildGlobalFuseActions( $application, $child );
                            break;
                        case "plugins":
                            self::buildPlugins( $application, $child );
                            break;    
                    }            
                }
            }
            // TODO destroy application cache
            //$application->getLoader()->destroyCachedApplicationData();
        }
        else{
            if( isset( $data[ 'application' ][ 'children' ] ) ) {
                if( count( $data[ 'application' ][ 'children' ] ) ) {
                    foreach( $data[ 'application' ][ 'children' ] as $child ) {
                        switch( $child[ 'name' ] ) {
                            case "globalfuseactions":
                                self::buildGlobalFuseActions( $application, $child );
                                break;    
                        }            
                    }
                }
            }
        }
        
        /*foreach( $this->getApplication()->getCircits() as $circuit ) {
            if( $circuit->getName() != "MYFUSES_GLOBAL_CIRCUIT" ) {
                $this->buildCircuit( $circuit );
            }
        }*/
        // FIXME call build listeners from application
        foreach( $application->getBuilderListeners() as $listener ) {
            $listener->applicationBuildPerformed( $application, 
                $application->getLoader()->getCachedApplicationData() );
        }
        
    }
    
    protected static function buildCircuits( 
        Application $application, &$data ) {
            
        $circuitAttributes = array(
            "name" => "name",
            "alias" => "name",
            "path" => "path",
            "parent" => "parent"
        );
        
        if( count( $data[ 'children' ] > 0 ) ) {
            foreach( $data[ 'children' ] as $child ) {
                $name = "";
                $path = "";
                $parent = "";
                
                foreach( $child[ 'attributes' ] as $attributeName => $attribute ) {
                    if ( isset( $circuitAttributes[ $attributeName ] ) ) {
                        $$circuitAttributes[ $attributeName ] = $attribute;
                    }
                }
                
                if( $application->hasCircuit( $name ) ) {
                    $circuit = $application->getCircuit( $name );
                }
                else {
                    $circuit = new BasicCircuit();    
                }
                
                //TODO handle this parameters changes
                $circuit->setName( $name );
                $circuit->setPath( $path );
                $circuit->setParentName( $parent );
                
                $application->addCircuit( $circuit );
                
                $circuit->unsetPreFuseAction();
                $circuit->unsetPostFuseAction();
                
                //self::buildCircuit( $circuit );        
                
            }
        }
        
    }
    
    public static function buildCircuit( Circuit $circuit ) {
        
        $data = &$circuit->getData();
        
        $circuitParameterAttributes = array(
            "access" => "access",
            "file" => "file"
        );
        
        $access = "";
        
        $file = "";
        
        if( isset( $data[ 'attributes' ] ) ) {
            foreach( $data[ 'attributes' ] as $attributeName => $attribute ) {
                if ( isset( $circuitParameterAttributes[ $attributeName ] ) ) {
                    // getting $name
                    $$circuitParameterAttributes[ $attributeName ] = "" . 
                        $attribute;
                }
            }
        }
        
        $circuit->setFile( $file );
        
        $circuit->setAccessByString( $access );
        
        if( isset( $data['docNamespaces'] ) ) {
            $circuit->setVerbPaths( serialize( $data['docNamespaces'] ) );
        }
        
        if( isset( $data[ 'namespaceattributes' ] ) ) {
            foreach( $data[ 'namespaceattributes' ] as $namespace => $attributes ) {
                foreach( $attributes as $name => $value )  {
                    $circuit->setCustomAttribute( $namespace, $name, $value );
                }
            }
        }
        
        
        if( count( $data[ 'children' ] > 0 ) && !is_null( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                
                switch( $child[ 'name' ] ) {
                    case "fuseaction":
                    case "action":
                        self::buildAction( $circuit, $child );
                        break;
                    case "prefuseaction":
                    case "postfuseaction":
                        self::buildGlobalAction( $circuit, $child );
                        break;
                        
                }
            }
        }
        
        $circuit->setBuilt( true );
    }
    
    /**
     * Builds action
     * 
     * @param Circuit $circuit
     * @param SimpleXMLElement $parentNode
     */
    public static function buildAction( Circuit $circuit, $data ) {
        
        if( is_null( $data ) ) {
            return false;
        }
        
        $actionParameterAttributes = array(
            "name" => "name",
            "class" => "class",
            "path" => "path",
            "default" => "default"
        );
        
        $parameterAttributes = array(
            "name" => "name",
            "value" => "value"
        );
        
        $name = "";
        
        $class = null;
        $path = null;
        
        $default = null;
        
        $customAttribute = array();
        
        foreach( $data[ 'attributes' ] as $attributeName => $attribute ) {
            if ( isset( $actionParameterAttributes[ $attributeName ] ) ) {
                // getting $name
                $$actionParameterAttributes[ $attributeName ] = "" . $attribute;
            }
            if( strpos( $attributeName, "_ns_"  ) !== false ) {
                list( $namespace, $attrName ) = explode( "_ns_", 
                    $attributeName );
                $customAttribute[ $namespace ][ $attrName ] = $attribute;
            }
        }
        
        if( !is_null( $path ) ) {
            if( !MyFusesFileHandler::isAbsolutePath( $path ) ) {
                $path = $circuit->getApplication()->getPath() . $path;
            }
            require_once $path;    
        }
        
        if( is_null( $class ) ){
            $action = new FuseAction( $circuit );    
        }
        else {
            $action = new $class( $circuit );
        }
        
        foreach( $customAttribute as $namespace => $attributes ) {
            foreach( $attributes as $attribute => $value ) {
                $action->setCustomAttribute( $namespace, 
                    $attribute, $value );
            }
        }
        
        if( !is_null( $path ) ){
            $action->setPath( $path );    
        }
        
        $action->setName( $name );
        
        $action->setDefault( $default );
        
        $circuit->addAction( $action );
        
        if( isset( $data[ 'children' ] ) ) {
            if( count( $data[ 'children' ] ) > 0 ) {
                foreach( $data[ 'children' ] as $child ) {    
                    self::buildVerb( $action, $child );
                }
                
            }
        }
        return true;
    }
    
    /**
     * Build global action
     *
     * @param Circuit $circuit
     * @param SimpleXMLElement $parentNode
     */
    protected function buildGlobalAction( Circuit $circuit, &$data ) {
        
        $globalActionMethods = array(
            "prefuseaction" => "setPreFuseAction",
            "postfuseaction" => "setPostFuseAction"
        );   
            
        $action = new FuseAction( $circuit );
        
        $action->setName( $data[ 'name' ] );
        
        if( isset( $data[ 'children' ] ) ) {
            if( count( $data[ 'children' ] ) > 0 ) {
	            foreach( $data[ 'children' ] as $child ) {
	                self::buildVerb( $action, $child );
	            }
	        }
        }
        
        if( isset( $globalActionMethods[ $action->getName() ] ) ) {
            $circuit->$globalActionMethods[ $action->getName() ]( $action );
        }
        
    }

    
    /**
     * Build the verb
     * 
     * @param CircuitAction $action
     * @param SimpleXMLElement $parentNode
     */
    protected function buildVerb( CircuitAction $action, &$data ) {
        $verb = AbstractVerb::getInstance( $data, $action );
        if( !is_null( $verb ) ){
            $action->addVerb( $verb );    
        }
    }
    
    /**
     * Builds all application classes
     * 
     * @param array $parentNode
     */
    protected function buildClasses( Application $application, &$data ) {
        $parameterAttributes = array(
            "name" => "name",
            "classPath" => "path"
        );
        
        if( isset( $data[ 'children' ] ) ) {
            if( count( $data[ 'children' ] > 0 )  ) {
                foreach( $data[ 'children' ] as $child ) {
                    self::buildClass( $application, $child );
                }
            }
        }
        
    }
    
    protected static function buildClass( Application $application, &$data ) {
        
        $parameterAttributes = array(
            "name" => "name",
            "alias" => "name",
            "classpath" => "path"
        );
        
        $name = "";
        $path = "";
        
        foreach( $data[ 'attributes' ] as $attributeName => $attribute ) {
            if ( isset( $parameterAttributes[ $attributeName ] ) ) {
                // getting $name or $value
                $$parameterAttributes[ $attributeName ] = "" . $attribute;
            }
        }
        
        if( isset($name) ) {
            if( $name != "" ) {
                $class = new ClassDefinition();
                $class->setName( $name );
                $class->setPath( $path );
                $application->addClass( $class );
            }
        }
        
    }
    
    /**
     * Build all application parameters
     *
     * @param Application $application
     * @param array $data
     */
    protected static function buildParameters( 
        Application $application, &$data ) {
        
        $parameterAttributes = array(
            "name" => "name",
            "value" => "value"
        );

        $applicationParameters = array(
            "fuseactionVariable" => "setFuseactionVariable",
            "defaultFuseaction" => "setDefaultFuseaction",
            "precedenceFormOrUrl" => "setPrecedenceFormOrUrl",
            "debug" => "setDebug",
            "tools" => "setTools",
            "mode" => "setMode",
            "strictMode" => "setStrictMode",
            "password" => "setPassword",
            "parseWithComments" => "setParsedWithComments",
            "conditionalParse" => "setConditionalParse",
            "allowLexicon" => "setLexiconAllowed",
            "ignoreBadGrammar" => "setBadGrammarIgnored",
            "useAssertions" => "setAssertionsUsed",
            "scriptLanguage" => "setScriptLanguage",
            "scriptFileDelimiter" => "setScriptFileDelimiter",
            "maskedFileDelimiters" => "setMaskedFileDelimiters",
            "characterEncoding" => "setCharacterEncoding"
        );
        
        if( count( $data[ 'children' ] > 0 ) ) {
            foreach( $data[ 'children' ] as $child ) {    
                $name = "";
                $value = "";
                foreach( $child[ 'attributes' ] as 
                    $attributeName => $attribute ) {
                    if ( isset( $parameterAttributes[ $attributeName ] ) ) {
                        // getting $name or $value
                        $$parameterAttributes[ $attributeName ] = "" . 
                            $attribute; 
                    }
                }
                // putting into $application
                if( isset( $applicationParameters[ $name ] ) ) {
                    $application->$applicationParameters[ $name ]( $value );
                }
            }
        }
        
    }
    
    /**
     * Build global fuseaction
     *
     * @param array $data
     */
    protected static function buildGlobalFuseActions( 
        Application $application, &$data ) {
        
        $globalActionMethods = array(
            "preprocess" => "getPreProcessFuseAction",
            "postprocess" => "getPostProcessFuseAction"
        );   
        
        $circuit = new BasicCircuit();
        
        $circuit->setName( "MYFUSES_GLOBAL_CIRCUIT" );
        
        $circuit->setPath( $application->getPath() );
        
        $circuit->setAccessByString( "internal" );
        
        $application->addCircuit( $circuit );
        
        if( count( $data[ 'children' ] ) > 0 ) {
            foreach( $data[ 'children' ] as $child ) {    
                $action = new FuseAction( $circuit );
                
                $action->setName( str_replace( "get", "", 
                    $globalActionMethods[ $child[ 'name' ] ] ) );
                    
                if( isset( $child[ 'children' ] ) ) {
                    if( count( $child[ 'children' ] ) ) {
                        foreach( $child[ 'children' ] as $actionChild ) {
                            self::buildVerb( $action, $actionChild );
                        }
                    }
                }
                
                $circuit->addAction( $action );
            }
        }
        
        if( isset( $globalActionMethods[ $action->getName() ] ) ) {
            $circuit->getApplication()->$globalActionMethods[ 
                $action->getName() ]( $action );
        }
        
    }
    
    protected function buildPlugins( Application $application, &$data ) {
        $application->clearPlugins();
        if( count( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                self::buildFase( $application, $child );
            }
        }
    }
    
    
    protected protected function buildFase( Application $application, &$data ) {
        
        $faseParams = array(
            'name' => 'name',
            'path' => 'path',
            'template' => 'file',
            'file' => 'file'
        );
            
        $phase = $data[ 'attributes' ][ 'name' ]; 
        
        if( isset( $data[ 'children' ] ) ) {
            if( count( $data[ 'children' ] ) ) {
                foreach( $data[ 'children' ] as $child ) {
                    $name = "";
                    $path = "";
                    $file = "";
                    
                    foreach( $child[ 'attributes' ] as 
                        $attributeName => $attribute ) {
                        $$faseParams[ $attributeName ] = $attribute;
                    }
                    
                    $paramters = array();
                    
                    if( isset( $child[ 'children' ] ) ) {
                        foreach( $child[ 'children' ] as $key => $paramChild ) {
                            
                            if( strtolower( $paramChild[ 'name' ] ) == 'parameter' ) {
                                $param = array( 
                                    'name' => $paramChild[ 'attributes' ][ 'name' ], 
                                    'value' => $paramChild[ 'attributes' ][ 'value' ] );
                                $paramters[] = $param;
                            }
                        }
                    }
                    
                    AbstractPlugin::getInstance( $application, 
                        $phase, $name, $path, $file, $paramters );
                    
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
        MyFusesApplicationBuilderListener $listener ){
        $this->applciationBuilderListeners[] = $listener;
    }
    
    /**
     * Return all application builder listerners
     *
     * @return array
     */
    private function getApplicationBuilderListeners() {
        return $this->applciationBuilderListeners;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */