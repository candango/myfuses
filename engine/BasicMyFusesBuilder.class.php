<?php
MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "engine/MyFusesBuilder.class.php" );

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
    
    public function buildApplication(){
        
        $data = &$this->getApplication()->getLoader()->
            getCachedApplicationData();
        
        if( count( $data[ 'application' ][ 'children' ] ) ) {
            foreach( $data[ 'application' ][ 'children' ] as $child ) {
                switch( $child[ 'name' ] ) {
                    case "circuits":
                        $this->buildCircuits( $child );
                        break;
                    case "classes":
                        $this->buildClasses( $child );
                        break;
                    case "parameters":
                        $this->buildParameters( $child );
                        break;
                    case "globalfuseactions":
                        $this->buildGlobalFuseActions( $child );
                        break;
                    case "plugins":
                        $this->buildPlugins( $child );
                        break;    
                }            
            }
        }
        
        /*foreach( $this->getApplication()->getCircits() as $circuit ) {
            if( $circuit->getName() != "MYFUSES_GLOBAL_CIRCUIT" ) {
                $this->buildCircuit( $circuit );
            }
        }*/
        
        foreach( $this->getApplicationBuilderListeners() as $listener ) {
            $listener->applicationBuildPerformed( $this->getApplication(), 
                $this->getApplication()->getLoader()->
                getCachedApplicationData() );
        }
        
    }
    
    protected function buildCircuits( &$data ) {
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
                
                $circuit = new Circuit();
                
                if( $this->getApplication()->hasCircuit( $name ) ) {
                    $circuit = $this->getApplication()->getCircuit( $name );
                }
                
                //TODO handle this parameters changes
                $circuit->setName( $name );
                $circuit->setPath( $path );
                $circuit->setParentName( $parent );
                
                $this->getApplication()->addCircuit( $circuit );
                
                $circuit->unsetPreFuseAction();
                $circuit->unsetPostFuseAction();
                
            }
        }
        
    }
    
    public function buildCircuit( Circuit $circuit ) {
        $appData = &$this->getApplication()->getLoader()->
            getCachedApplicationData();
            
        $data = &$appData[ 'circuits' ][ $circuit->getName() ];
        
        $circuit->setModified( $data[ 'attributes' ][ 'modified' ] );
        
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
        
        
        if( count( $data[ 'children' ] > 0 ) ) {
            foreach( $data[ 'children' ] as $child ) {
                switch( $child[ 'name' ] ) {
                    /*case "fuseaction":
                    case "action":
                        $this->buildAction( $circuit, $child );
                        break;*/
                    case "prefuseaction":
                    case "postfuseaction":
                        $this->buildGlobalAction( $circuit, $child );
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
    public function buildAction( Circuit $circuit, $name ) {
        
        $action = new FuseAction( $circuit );
        
        $appCData = &$circuit->getApplication()->getLoader()->
            getCachedApplicationData();
        
        $data = null;
        
        foreach( $appCData[ 'circuits' ] as $key => $circuitData ) {
            if( $key === $circuit->getName() ) {
                foreach( $circuitData[ 'children' ] as $actionData ) {
                    if( isset( $actionData[ 'attributes' ][ 'name' ] ) ) {
                        if( $actionData[ 'attributes' ][ 'name' ] === $name ) {
                            $data = $actionData;
                            break;
                        }
                    }
                }
                break;
            }
        }
        
        if( is_null( $data ) ) {
            return false;
        }
        
        // TODO implement class and namespace options
        $actionParameterAttributes = array(
            "name" => "name",
            "class" => "class",
            "path" => "path"
        );
        
        $parameterAttributes = array(
            "name" => "name",
            "value" => "value"
        );
        
        $name = "";
        
        $class = null;
        $path = null;
        
        foreach( $data[ 'attributes' ] as $attributeName => $attribute ) {
            if ( isset( $actionParameterAttributes[ $attributeName ] ) ) {
                // getting $name
                $$actionParameterAttributes[ $attributeName ] = "" . $attribute;
            }
            if( strpos( $attributeName, "_ns_"  ) !== false ) {
                list( $namespace, $attrName ) = explode( "_ns_", 
                    $attributeName );
                $action->setCustomAttribute( $namespace, 
                    $attrName, $attribute );
            }
        }
        
        if( !is_null( $path ) ) {
            if( !MyFusesFileHandler::isAbsolutePath( $path ) ) {
                $path = $this->getApplication()->getPath() . $path;
            }
            require_once $path;    
        }
        
        if( is_null( $class ) ){
            $action = new FuseAction( $circuit );    
        }
        else {
            $action = new $class( $circuit );
        }
        
        if( !is_null( $path ) ){
            $action->setPath( $path );    
        }
        
        $action->setName( $name );
        
        $circuit->addAction( $action );
        
        if( isset( $data[ 'children' ] ) ) {
            if( count( $data[ 'children' ] ) > 0 ) {
                foreach( $data[ 'children' ] as $child ) {    
                    $this->buildVerb( $action, $child );
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
        
        
        if( count( $data[ 'children' ] ) > 0 ) {
            foreach( $data[ 'children' ] as $child ) {    
                $this->buildVerb( $action, $child );
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
    protected function buildClasses( &$data ) {
        $parameterAttributes = array(
            "name" => "name",
            "classPath" => "path"
        );
        
        if( isset( $data[ 'children' ] ) ) {
            if( count( $data[ 'children' ] > 0 )  ) {
                foreach( $data[ 'children' ] as $child ) {
                  
                    $this->buildClass( $child );
                  
                }
            }
        }
        
    }
    
    protected function buildClass( &$data ) {
        
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
                $this->getApplication()->addClass( $class );
            }
        }
        
    }
    
    /**
     * Build all application parameters
     *
     * @param Application $application
     * @param array $data
     */
    protected function buildParameters( &$data ) {
        
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
                    $this->getApplication()->
                        $applicationParameters[ $name ]( $value );
                }
            }
        }
        
    }
    
    /**
     * Build global fuseaction
     *
     * @param array $data
     */
    protected function buildGlobalFuseActions( &$data ) {
        
        $globalActionMethods = array(
            "preprocess" => "getPreProcessFuseAction",
            "postprocess" => "getPostProcessFuseAction"
        );   
        
        $circuit = new Circuit();
        
        $circuit->setName( "MYFUSES_GLOBAL_CIRCUIT" );
        
        $circuit->setPath( $this->getApplication()->getPath() );
        
        $circuit->setAccessByString( "internal" );
        
        $this->getApplication()->addCircuit( $circuit );
        
        if( count( $data[ 'children' ] ) > 0 ) {
            foreach( $data[ 'children' ] as $child ) {    
                $action = new FuseAction( $circuit );
                
                $action->setName( str_replace( "get", "", $globalActionMethods[ $child[ 'name' ] ] ) );
        
                if( isset( $child[ 'children' ] ) ) {
                    if( count( $child[ 'children' ] ) ) {
                        foreach( $child[ 'children' ] as $actionChild ) {
                            $this->buildVerb( $action, $actionChild );
                        }
                    }
                }
                
                $circuit->addAction( $action );
            }
        }
        
        if( isset( $globalActionMethods[ $action->getName() ] ) ) {
            $circuit->getApplication()->$globalActionMethods[ $action->getName() ]( $action );
        }
        
    }
    
    protected function buildPlugins( &$data ) {
        $this->getApplication()->clearPlugins();
        if( count( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                $this->buildFase( $child );
            }
            
        }
    }
    
    
    protected function buildFase( &$data ) {
        
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
                    
                    AbstractPlugin::getInstance( $this->getApplication(), 
                        $phase, $name, $path, $file );
                    
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