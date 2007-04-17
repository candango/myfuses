<?php
require_once "myfuses/core/AbstractVerb.class.php";
require_once "myfuses/core/Application.class.php";
require_once "myfuses/core/ClassDefinition.class.php";
require_once "myfuses/core/Circuit.class.php";
require_once "myfuses/core/FuseAction.class.php";

require_once "myfuses/engine/AbstractMyFusesLoader.class.php";
/**
 * 
 */
class XMLMyFusesLoader extends AbstractMyFusesLoader {
    
    /**
     * My Fuses application file constant
     * 
     * @var string
     * @static
     */
    const MYFUSES_APP_FILE = "myfuses.xml";
    
    /**
     * My Fuses php application file constant
     * 
     * @var string
     * @static 
     */
    const MYFUSES_PHP_APP_FILE = "myfuses.xml.php";
    
    const CIRCUIT_FILE = "circuit.xml";
    
    const CIRCUIT_PHP_FILE = "circuit.xml.php";
    
    /**
     * Enter description here...
     *
     * @param Application $application
     */
    public function doLoad( Application $application ) {
        
        $this->chooseApplicationFile( $application );
        
        $this->loadApplicationFile( $application );
        
    }
    
	/**
     * Find the file that the given application is using
     * TODO Throw some exception here!!!
     *
     * @param Application $application
     * @return boolean
     */
    private function chooseApplicationFile( Application $application ) {
        if ( is_file( $application->getPath() . self::MYFUSES_APP_FILE ) ) {
            $application->setFile( self::MYFUSES_APP_FILE );
            return true;
        }
        
        if ( is_file( $application->getPath() . self::MYFUSES_PHP_APP_FILE ) ) {
            $application->setFile( self::MYFUSES_PHP_APP_FILE );
            return true;
        }
        
        return false;
    }
    
    public function applicationWasModified( Application $application ) {
        if( filectime( $application->getCompleteFile() ) > 
            $application->getLastLoadTime() ) {
            return true;
        }
        return false;
    }
    
    // TODO Throw some exception here!!!
    private function chooseCircuitFile( Circuit $circuit ) {
        
        $circuitPath = $circuit->getApplication()->getPath() . $circuit->getPath();
        
        if ( is_file( $circuitPath . self::CIRCUIT_FILE ) ) {
            $circuit->setFile( self::CIRCUIT_FILE );
            return true;
        }
        
        if ( is_file( $circuitPath . self::CIRCUIT_APP_FILE ) ) {
            $circuit->setFile( self::CIRCUIT_APP_FILE );
            return true;
        }
        
        return false;
    }
    
    /**
     * Load the application file
     * 
     * @param Application $application
     */
    private function loadApplicationFile( Application $application ) {
        
        $appMethods = array( 
            "circuits" => "loadCircuits", 
            "classes" => "loadClasses",
            "parameters" => "loadParameters"
             );
        
        // TODO verify if all conditions is satisfied for a file load ocours
        if ( @!$fp = fopen( $application->getCompleteFile() ,"r" ) ){
            throw new MyFusesFileOperationException( 
                $application->getCompleteFile(), 
                MyFusesFileOperationException::OPEN_FILE );
        }
        
        if ( !flock( $fp, LOCK_SH ) ) {
            throw new MyFusesFileOperationException( 
                $application->getCompleteFile(), 
                MyFusesFileOperationException::LOCK_FILE );
        }
        
        $fileCode = fread( $fp, filesize( $application->getCompleteFile() ) );
        
        $rootNode = new SimpleXMLElement( $fileCode );
        
        if( count( $rootNode > 0 ) ) {
            foreach( $rootNode as $node ) {
                if ( isset( $appMethods[ $node->getName() ] ) ) {
                    $this->$appMethods[ $node->getName() ]( $application, 
                        $node );
                }                
            }
        }
        
    }

    private function loadCircuits( Application $application, 
        SimpleXMLElement $parentNode ) {
        
        $circuitMethods = array(
            "name" => "setName",
            "alias" => "setName",
            "path" => "setPath",
            "parent" => "setParentName"
        );
        
        if( count( $parentNode > 0 ) ) {
            foreach( $parentNode as $node ) {
                $name = "";
                $path = "";
                $parent = "";
                $circuit = new Circuit();
                foreach( $node->attributes() as $attribute ) {
	                if ( isset( $circuitMethods[ $attribute->getName() ] ) ) {
	                    $circuit->$circuitMethods[ $attribute->getName() ]( 
	                        "" . $attribute );
	                }
                }
                $application->addCircuit( $circuit );
            }
        }
        
        foreach( $application->getCircits() as $circuit ) {
            $this->loadCircuit( $circuit );
        }
        
    }
    
    /**
     * Load all application classes
     * 
     * @param Application $application
     * @param SimpleXMLElement $parentNode
     */
    private function loadClasses( Application $application, 
        SimpleXMLElement $parentNode ) {
        
        $parameterAttributes = array(
            "name" => "name",
            "classPath" => "path"
        );
        if( count( $parentNode > 0 ) ) {
	        foreach( $parentNode as $node ) {
	          
	            $this->loadClass(  $application, $node );
	          
	        }
        }
    }
    
    public function loadClass( Application $application,
        SimpleXMLElement $parentNode ) {
        
        $parameterAttributes = array(
            "name" => "name",
            "alias" => "name",
            "classpath" => "path"
        );

        
        $name = "";
        $path = "";
        
        foreach( $parentNode->attributes() as $attribute ) {
            
            if ( isset( $parameterAttributes[ $attribute->getName() ] ) ) {
                // getting $name or $value
                $$parameterAttributes[ $attribute->getName() ] = "" . $attribute;
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
    private function loadParameters( Application $application, 
        SimpleXMLElement $parentNode ) {
        
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
        
        foreach( $parentNode as $node ) {    
	        if( count( $parentNode > 0 ) ) {
	            $name = "";
	            $value = "";
	            foreach( $node->attributes() as $attribute ) {
	                if ( isset( $parameterAttributes[ $attribute->getName() ] ) ) {
	                    // getting $name or $value
	                    $$parameterAttributes[ $attribute->getName() ] = "" . $attribute; 
	                }
	            }
	        }
	        
	        // putting into $application
	        if( isset( $applicationParameters[ $name ] ) ) {
	            $application->$applicationParameters[ $name ]( $value );
	        }
        }
        
    }
    
    /**
     * Load one circuit
     *
     * @param Circuit $circuit
     */
    private function loadCircuit( Circuit $circuit ) {
        
        $this->chooseCircuitFile( $circuit );
        
        $this->loadCircuitFile( $circuit );
        
    }

    /**
     * Load a circuit file
     * 
     * @param Circuit $circuit
     */
    private function loadCircuitFile( Circuit $circuit ) {
        
        $circuitMethods = array( 
            "fuseaction" => "loadAction",
            "action" => "loadAction",
			"prefuseaction" => "loadGlobalAction",
			"postfuseaction" => "loadGlobalAction"
        );
        
        $circuitParameterAttributes = array(
            "access" => "access"
        );
        
        $circuitPath = $circuit->getApplication()->getPath() . $circuit->getPath();
        
        $circuitFile = $circuitPath . $circuit->getFile();
        
        // TODO verify if all conditions is satisfied for a file load ocours
        if ( @!$fp = fopen( $circuitFile ,"r" ) ){
            throw new MyFusesFileOperationException( 
                $circuitFile, MyFusesFileOperationException::OPEN_FILE );
        }
        
        if ( !flock( $fp, LOCK_SH ) ) {
            throw new MyFusesFileOperationException( 
                $circuitFile, MyFusesFileOperationException::LOCK_FILE );
        }
        
        $fileCode = fread( $fp, filesize( $circuitFile ) );
        
        $rootNode = new SimpleXMLElement( $fileCode );
        
        $access = "";
	    
        foreach( $rootNode->attributes() as $attribute ) {
            if ( isset( $circuitParameterAttributes[ $attribute->getName() ] ) ) {
                // getting $name
                $$circuitParameterAttributes[ $attribute->getName() ] = "" . $attribute;
            }
        }
        
        $circuit->setAccessByString( $access );
        
        if( count( $rootNode > 0 ) ) {
            foreach( $rootNode as $node ) {
                if ( isset( $circuitMethods[ $node->getName() ] ) ) {
                    $this->$circuitMethods[ $node->getName() ]( $circuit, 
                        $node );
                }               
            }
        }
    }
    
    /**
     * Load the action
     * 
     * @param Circuit $circuit
     * @param SimpleXMLElement $parentNode
     */
    private function loadAction( Circuit $circuit, SimpleXMLElement $parentNode ) {
        
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
	    
        foreach( $parentNode->attributes() as $attribute ) {
            if ( isset( $actionParameterAttributes[ $attribute->getName() ] ) ) {
                // getting $name
                $$actionParameterAttributes[ $attribute->getName() ] = "" . $attribute;
            }
        }
	    
        $action->setName( $name );
        
        $circuit->addAction( $action );
        
        if( count( $parentNode > 0 ) ) {
            foreach( $parentNode as $node ) {    
	            $this->loadVerbXML( $action, $node );
	        }
	        
        }
        
    }
    
    /**
     * Load the verb xml
     * 
     * @param CircuitAction $action
     * @param SimpleXMLElement $parentNode
     */
    public function loadVerbXML( CircuitAction $action, SimpleXMLElement $parentNode ) {
        $data = $this->getDataFromXML( $parentNode );
		$verb = AbstractVerb::getInstance( serialize( $data ), $action );
		if( !is_null( $verb ) ){
		    $action->addVerb( $verb );    
		}
    }
    
    private function getDataFromXML( SimpleXMLElement $node ) {
        $data[ "name" ] = $node->getName(); 
        
        foreach( $node->attributes() as $attribute ) {
            $data[ "attributes" ][ $attribute->getName() ] = "" . $attribute;
        }
        
        if( count( $node->children() ) ) {
            foreach( $node->children() as $child ) {
                $data[ "children" ][] = $this->getDataFromXML( $child );    
            }
        }
        
        return $data;
    }
    
    /**
     * Load global action
     *
     * @param Circuit $circuit
     * @param SimpleXMLElement $parentNode
     */
    private function loadGlobalAction( Circuit &$circuit, 
        SimpleXMLElement $parentNode ) {
        
        $globalActionMethods = array(
            "prefuseaction" => "setPreFuseAction",
            "postfuseaction" => "setPostFuseAction"
        );   
            
        $action = new FuseAction( $circuit );
        
        $action->setName( $parentNode->getName() );
        
        if( count( $parentNode > 0 ) ) {
            foreach( $parentNode as $node ) {
                $this->loadVerbXML( $action, $node );
            }
             
        }
        if( isset( $globalActionMethods[ $action->getName() ] ) ) {
            $circuit->$globalActionMethods[ $action->getName() ]( $action );
        }
        
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */