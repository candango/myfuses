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
        
        // setting parsed path
        if ( is_null( $application->getParsedPath() ) ) {
            $application->setParsedPath( MyFuses::ROOT_PATH . "store" . DIRECTORY_SEPARATOR . 
                MyFuses::getInstance()->getApplication()->getName() . DIRECTORY_SEPARATOR ) ;
        }
        
        // getting cache file
	    // TODO application load must be like fusebox official
        if( is_file( $application->getCompleteCacheFile() ) ) {
            require_once( $application->getCompleteCacheFile() );
            if( $this->applicationWasModified( $application ) ) {
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
            $this->loadCircuit( $circuit );
        }
        
        $application->setLoaded( true );
        
    }
    
    
    public function doLoadApplication( Application $application  ) {
        
        $appMethods = array( 
            "circuits" => "loadCircuits", 
            "classes" => "loadClasses",
            "parameters" => "loadParameters"
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
    
    private function loadCircuits( Application $application, $data ) {
        
        $circuitMethods = array(
            "name" => "setName",
            "alias" => "setName",
            "path" => "setPath",
            "parent" => "setParentName"
        );
        
        if( count( $data[ 'children' ] > 0 ) ) {
            foreach( $data[ 'children' ] as $child ) {
                $name = "";
                $path = "";
                $parent = "";
                $circuit = new Circuit();
                foreach( $child[ 'attributes' ] as $attributeName => $attribute ) {
	                if ( isset( $circuitMethods[ $attributeName ] ) ) {
	                    $circuit->$circuitMethods[ $attributeName ]( 
	                        "" . $attribute );
	                }
                }
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
    private function loadClasses( Application $application, $data ) {
        
        $parameterAttributes = array(
            "name" => "name",
            "classPath" => "path"
        );
        if( count( $data[ 'children' ] > 0 ) ) {
	        foreach( $data[ 'children' ] as $child ) {
	          
	            $this->loadClass(  $application, $child );
	          
	        }
        }
        
    }
    
    public function loadClass( Application $application, $data ) {
        
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
     * Load all application parameters
     *
     * @param Application $application
     * @param SimpleXMLElement $parentNode
     */
    private function loadParameters( Application $application, $data ) {
        
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
    
    public function doLoadCircuit( Circuit $circuit ){
        
        $this->chooseCircuitFile( $circuit );
        
        $this->loadCircuitFile( $circuit );
        
        $circuit->setLastLoadTime( time() );
        
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