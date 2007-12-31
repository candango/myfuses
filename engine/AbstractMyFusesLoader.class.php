<?php
require_once "myfuses/engine/MyFusesLoader.class.php";

/**
 * Abstract MyFuses loader.<br>
 * 
 *
 */
abstract class AbstractMyFusesLoader implements MyFusesLoader {
    
    /**
     * Loader application
     * 
     * @var Application
     */
    private $application;
    
    /**
     * Return the application
     *
     * @return Application
     */
    public function &getApplication(){
        return $this->application;
    }
    
    /**
     * Set the loader Application
     *
     * @param Application $application
     */
    public function setApplication( Application &$application ) {
        $this->application = $application;
    }
    
    /**
     * Load the application
     *
     */
    public function loadApplication() {
        // getting cache file
	    // TODO application load must be like fusebox official
        if( is_file( $this->getApplication()->getCompleteCacheFile() ) 
            && ( $this->getApplication()->getMode() != "development" ) ) {
            require_once( $this->getApplication()->getCompleteCacheFile() );
            // correcting cached application reference
            $this->setApplication( 
                $this->getApplication()->getController()->getApplication( 
                    $this->application->getName() ) );
            
            if( $this->applicationWasModified() ) {
                $this->doLoadApplication();
            }
        }
        else {
            $this->doLoadApplication();
        }
        
        
        // TODO put loadCircuit in this file
        foreach( $this->getApplication()->getCircits() as $circuit ) {
            if( $circuit->getName() != "MYFUSES_GLOBAL_CIRCUIT" ) {
                //TODO handle missing file error
	            $this->loadCircuit( $circuit );
            }
        }
        
        $this->getApplication()->setLoaded( true );
        
    }
    
    
    public function doLoadApplication() {
        $this->getApplication()->setParse( true );
        
        // circuits must be loaded when application changes
        foreach( $this->getApplication()->getCircits() as $circuit ) {
            $circuit->setLastLoadTime( 0 );
        }
        
        $appMethods = array( 
            "circuits" => "loadCircuits", 
            "classes" => "loadClasses",
            "parameters" => "loadParameters",
            "globalfuseactions" => "loadGlobalFuseActions",
            "plugins" => "loadPlugins"
             );
        
        $data = $this->getApplicationData();
        
        
        if( count( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                if ( isset( $appMethods[ $child[ 'name' ] ] ) ) {
                    $this->$appMethods[ $child[ 'name' ] ]( $child );
                }            
            }
        }
        
        $this->getApplication()->setLastLoadTime( time() );
    }
    
    protected function loadCircuits( $data ) {
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
    
    /**
     * Load all application classes
     * 
     * @param array $parentNode
     */
    protected function loadClasses( $data ) {
        $parameterAttributes = array(
            "name" => "name",
            "classPath" => "path"
        );
        
        if( isset( $data[ 'children' ] ) ) {
            if( count( $data[ 'children' ] > 0 )  ) {
		        foreach( $data[ 'children' ] as $child ) {
		          
		            $this->loadClass( $child );
		          
		        }
	        }
        }
        
    }
    
    protected function loadClass( $data ) {
        
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
    
    protected function loadPlugins( $data ) {
        $this->getApplication()->clearPlugins();
        if( count( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                $this->loadFase( $child );
            }
            
        }
    }
    
    
    protected function loadFase( $data ) {
        
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
     * Load all application parameters
     *
     * @param Application $application
     * @param SimpleXMLElement $parentNode
     */
    protected function loadParameters( $data ) {
        
        $parameterAttributes = array(
	        "name" => "name",
	        "value" => "value"
        );

        $applicationParameters = array(
            "fuseactionVariable" => "setFuseactionVariable",
            "defaultFuseaction" => "setDefaultFuseaction",
            "precedenceFormOrUrl" => "setPrecedenceFormOrUrl",
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
     * Load one circuit
     *
     * @param Circuit $circuit
     */
    public function loadCircuit( Circuit $circuit ) {
        
        if( is_file( $circuit->getCompleteFile() ) ) {
            if( $this->circuitWasModified( $circuit ) ) {
                $this->doLoadCircuit( $circuit );
            }
            else{
                if( $circuit->getApplication()->getMode() == "development" ) {
	                $this->doLoadCircuit( $circuit );
	            }
            }
        }
        else {
            $this->doLoadCircuit( $circuit );
        }
        
    }
    
    protected function doLoadCircuit( Circuit $circuit ){
        $this->getApplication()->setParse( true );
        
        $circuitMethods = array( 
            "fuseaction" => "loadAction",
            "action" => "loadAction",
			"prefuseaction" => "loadGlobalAction",
			"postfuseaction" => "loadGlobalAction"
        );
        
        $circuitParameterAttributes = array(
            "access" => "access"
        );
        
        $data = $this->getCircuitData( $circuit );
        
        $access = "";
	    
        if( isset( $data[ 'attributes' ] ) ) {
	        foreach( $data[ 'attributes' ] as $attributeName => $attribute ) {
	            if ( isset( $circuitParameterAttributes[ $attributeName ] ) ) {
	                // getting $name
	                $$circuitParameterAttributes[ $attributeName ] = "" . $attribute;
	            }
	        }
        }
        
        
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
                if ( isset( $circuitMethods[ $child[ 'name' ] ] ) ) {
                    $this->$circuitMethods[ $child[ 'name' ] ]( $circuit, 
                        $child );
                }               
            }
        }
        
        $circuit->setLastLoadTime( time() );
        $circuit->setModified( true );
        
    }
    
    /**
     * Load the action
     * 
     * @param Circuit $circuit
     * @param SimpleXMLElement $parentNode
     */
    protected function loadAction( Circuit $circuit, $data ) {
        
        $action = new FuseAction( $circuit );
        
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
		            $this->loadVerb( $action, $child );
		        }
		        
	        }
        }
    }
    
    /**
     * Load the verb
     * 
     * @param CircuitAction $action
     * @param SimpleXMLElement $parentNode
     */
    protected function loadVerb( CircuitAction $action, $data ) {
        $verb = AbstractVerb::getInstance( serialize( $data ), $action );
		if( !is_null( $verb ) ){
		    $action->addVerb( $verb );    
		}
    }
    
    /**
     * Load global action
     *
     * @param Circuit $circuit
     * @param SimpleXMLElement $parentNode
     */
    protected function loadGlobalAction( Circuit $circuit, $data ) {
        
        $globalActionMethods = array(
            "prefuseaction" => "setPreFuseAction",
            "postfuseaction" => "setPostFuseAction"
        );   
            
        $action = new FuseAction( $circuit );
        
        $action->setName( $data[ 'name' ] );
        
        
        if( count( $data[ 'children' ] ) > 0 ) {
            foreach( $data[ 'children' ] as $child ) {    
                $this->loadVerb( $action, $child );
            }
        }
        if( isset( $globalActionMethods[ $action->getName() ] ) ) {
            $circuit->$globalActionMethods[ $action->getName() ]( $action );
        }
    }
    
    /**
     * Load global fuseaction
     *
     * @param array $data
     */
    protected function loadGlobalFuseActions( $data ) {
        
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
		                    $this->loadVerb( $action, $actionChild );
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
    
    /**
     * Enter description here...
     *
     * @param int $whichLoader
     * @return AbstractMyFusesLoader
     */
    public static function getLoader( $whichLoader ) {
        
        $loaderArray = array(
            MyFusesLoader::XML_LOADER => "XmlMyFusesLoader"
        );
        
        return new $loaderArray[ $whichLoader ]();
    }
    
    
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */