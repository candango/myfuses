<?php
abstract class MyFusesAbstractLoader implements MyFusesLoader {
	
    
    /**
     * (non-PHPdoc)
     * @see engine/MyFusesLoader#loadApplication()
     */
    public function loadApplication( Application &$application ) {
        
        // Getting properties that developers can change in the bootstrap
        $default = $application->isDefault();
        $locale = $application->getLocale();
        
        $this->includeApplicationParsedFile( $application );
        
        // Setting properties defined by developers in the bootstrap
        $application->setDefault( $default );
        $application->setLocale( $locale );
        
        // Fixing application reference in myfuses
        MyFuses::getInstance()->addApplication( $application );
        
        $data = $this->getApplicationData( $application );
        
        
        
        //var_dump( $data );
        
        /*$appMethods = array( 
            "circuits" => "loadCircuits", 
            "classes" => "loadClasses",
            "parameters" => "loadParameters"
        );
        
        $path = $application->getPath();
        
        $file = $path . "myfuses.xml";
        
        if( file_exists( $file ) ) {
            
            $data = MyFusesFileHandler::readFile( $file );
            
            try {
                // FIXME put no warning modifier in SimpleXMLElement call
                $rootNode = @new SimpleXMLElement( $data );

                foreach ( $rootNode as $key => $node ) {
                    if( isset( $appMethods[ strtolower( $key ) ] ) ) {
                        $this->$appMethods[ 
                           strtolower( $key ) ]( $application, $node );
                    }
                }
            }
            catch ( Exception $e ) {
                // FIXME handle error
                echo "<b>" . $application->getPath() . "<b><br>";
                die( $e->getMessage() );    
            }
                
        }
        else {
            $exception = new MyFusesException( "Could not find the " . 
               "application \"" . $application->getName() . "\" file." );
            
            $exception->setType( 
               MyFusesException::MYFUSES_APPLICATION_FILE_DOENST_EXISTS_TYPE );
            
            $exception->setDescription( "MyFuses can't find the application " . 
                "descriptor file. Check the directory \"" . 
                $application->getPath() . "\" and see if even myfuses.xml" . 
                " or fusebox.xml files exists." );
            
            throw $exception;
        }
        
        $this->application = null;*/
    }
    
	/**
	 * (non-PHPdoc)
	 * @see myfuses/engine/MyFusesLoader#setApplicationParameter()
	 */
	public function setApplicationParameter( Application $application, 
	   $name, $value ) {
	   
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
        
        // putting into $application
        if( isset( $applicationParameters[ $name ] ) ) {
            $application->$applicationParameters[ $name ]( $value );
        }
	}
	
	/**
	 * (non-PHPdoc)
	 * @see engine/MyFusesLoader#addApplicationReference()
	 */
	public function addApplicationReference( Application $application, 
       CircuitReference $reference ) {
       $application->addReference( $reference );
    }
	
    /**
     * Include the appliation cache file to restore the cache
     * 
     * @param $application
     */
    private function includeApplicationParsedFile( Application &$application ) {
        // TODO Check if parsed application file exists
        $application = include $application->getParsedApplicationFile();   
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */