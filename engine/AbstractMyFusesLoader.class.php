<?php
require_once "myfuses/engine/MyFusesLoader.class.php";

/**
 * Abstract MyFuses loader.<br>
 * 
 *
 */
abstract class AbstractMyFusesLoader implements MyFusesLoader {
    
    /**
     * Enter description here...
     *
     * @param Application $application
     */
    public function loadApplication( Application $application ) {
        
        // getting cache file
	    // TODO application load must be like fusebox official
        if( is_file( $application->getCompleteCacheFile() ) ) {
            require_once( $application->getCompleteCacheFile() );
            if( $this->applicationWasModified( $application ) ) {
                // circuits must be loaded when application changes
                foreach( $application->getCircits() as $circuit ) {
                    $circuit->setLastLoadTime( 0 );
                }
                
                $this->doLoadApplication( $application );
            }
            else{
                if( $application->getMode() == "development" ) {
	                $this->doLoadApplication( $application );
	            }
            }
        }
        else {
            $this->doLoadApplication( $application );
        }
        
        
        // TODO put loadCircuit in this file
        foreach( $application->getCircits() as $circuit ) {
            if( $circuit->getName() != "MYFUSES_GLOBAL_CIRCUIT" ) {
                //TODO handle missing file error
	            if( $this->circuitWasModified( $circuit ) ) {
	                $this->doLoadCircuit( $circuit );
	            }
	            else{
	                if( $application->getMode() == "development" ) {
	                    $this->doLoadCircuit( $circuit );
		            }
	            }
            }
        }
        
        $application->setLoaded( true );
        
    }
    
    
    public function doLoadApplication( Application $application  ) {
        $appMethods = array( 
            "circuits" => "loadCircuits", 
            "classes" => "loadClasses",
            "parameters" => "loadParameters",
            "globalfuseactions" => "loadGlobalFuseActions",
            "plugins" => "loadPlugins"
             );
        
        $data = $this->getApplicationData( $application );
        
        
        if( count( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                if ( isset( $appMethods[ $child[ 'name' ] ] ) ) {
                    $this->$appMethods[ $child[ 'name' ] ]( $application, 
                        $child );
                }            
            }
        }
        
        $application->setLastLoadTime( time() );
    }
    
    protected function loadCircuits( Application $application, $data ) {
        
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
                
                if( $application->hasCircuit( $name ) ) {
                    try { 
                        $circuit = $application->getCircuit( $name );
                    }
	                catch ( MyFusesCircuitException $mfe ) {
			            $mfe->breakProcess();
			        }
                }
                
                //TODO handle this parameters changes
                $circuit->setName( $name );
                $circuit->setPath( $path );
                $circuit->setParentName( $parent );
                
                $application->addCircuit( $circuit );
            }
        }
        
    }
    
    /**
     * Load all application classes
     * 
     * @param Application $application
     * @param array $parentNode
     */
    protected function loadClasses( Application $application, $data ) {
        $parameterAttributes = array(
            "name" => "name",
            "classPath" => "path"
        );
        
        if( isset( $data[ 'children' ] ) ) {
            if( count( $data[ 'children' ] > 0 )  ) {
		        foreach( $data[ 'children' ] as $child ) {
		          
		            $this->loadClass(  $application, $child );
		          
		        }
	        }
        }
        
    }
    
    protected function loadClass( Application $application, $data ) {
        
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
    
    protected function loadPlugins( Application $application, $data ) {
        $application->clearPlugins();
        if( count( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                $this->loadFase( $application, $child );
            }
            
        }
    }
    
    
    protected function loadFase( Application $application, $data ) {
        
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
	                
	                AbstractPlugin::getInstance( $application, $phase, 
	                    $name, $path, $file );
	                
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
    protected function loadParameters( Application $application, $data ) {
        
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
	            foreach( $child[ 'attributes' ] as $attributeName => $attribute ) {
	                if ( isset( $parameterAttributes[ $attributeName ] ) ) {
	                    // getting $name or $value
	                    $$parameterAttributes[ $attributeName ] = "" . $attribute; 
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
     * Load one circuit
     *
     * @param Circuit $circuit
     */
    public function loadCircuit( Circuit $circuit ) {
        
        $application = &$circuit->getApplication();
        
        if( is_file( $circuit->getApplication()->getCompleteCacheFile() ) ) {
            if( $this->circuitWasModified( $application ) ) {
                $this->doLoadApplication( $application );
            }
            else{
                if( $application->getMode() == "development" ) {
	                $this->doLoadApplication( $application );
	            }
            }
        }
        else {
            $this->doLoadApplication( $application );
        }
        
    }
    
    protected function doLoadCircuit( Circuit $circuit ){
        
       
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
	    
        foreach( $data[ 'attributes' ] as $attributeName => $attribute ) {
            if ( isset( $circuitParameterAttributes[ $attributeName ] ) ) {
                // getting $name
                $$circuitParameterAttributes[ $attributeName ] = "" . $attribute;
            }
        }
        
        
        
        $circuit->setAccessByString( $access );
        
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
            "class" => "",
            "namespace" => ""
        );
        
        $parameterAttributes = array(
            "name" => "name",
            "value" => "value"
        );
        
        $name = "";
	    
        foreach( $data[ 'attributes' ] as $attributeName => $attribute ) {
            if ( isset( $actionParameterAttributes[ $attributeName ] ) ) {
                // getting $name
                $$actionParameterAttributes[ $attributeName ] = "" . $attribute;
            }
        }
	    
        $action->setName( $name );
        
        $circuit->addAction( $action );
        
        if( count( $data[ 'children' ] ) > 0 ) {
            foreach( $data[ 'children' ] as $child ) {    
	            $this->loadVerb( $action, $child );
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
    protected function loadGlobalAction( Circuit &$circuit, $data ) {
        
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
     * @param Circuit $circuit
     * @param SimpleXMLElement $parentNode
     */
    protected function loadGlobalFuseActions( Application &$application, $data ) {
        
        $globalActionMethods = array(
            "preprocess" => "getPreProcessFuseAction",
            "postprocess" => "getPostProcessFuseAction"
        );   
        
        $circuit = new Circuit();
        
        $circuit->setName( "MYFUSES_GLOBAL_CIRCUIT" );
        
        $circuit->setPath( $application->getPath() );
        
        $circuit->setAccessByString( "internal" );
        
        $application->addCircuit( $circuit );
        
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
            $application->$globalActionMethods[ $action->getName() ]( $action );
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
            MyFusesLoader::XML_LOADER => "XMLMyFusesLoader"
        );
        
        return new $loaderArray[ $whichLoader ]();
    }
    
    
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */