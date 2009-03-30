<?php
interface MyFusesLoader {
	
	/**
	 * 
	 * @param $application
	 * @param $name
	 * @param $value
	 */
    public function setApplicationParameter( Application $application, 
        $name, $value );
        
    public function addApplicationReference( Application $application, 
       CircuitReference $reference );
}

abstract class MyFusesAbstractLoader implements MyFusesLoader {
	
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
	
	public function addApplicationReference( Application $application, 
       CircuitReference $reference ) {
       $application->addReference( $reference );
    }
	
}

class MyFusesXmlLoader extends MyFusesAbstractLoader {
    
    public function loadApplication( Application $application ) {
        
    	$appMethods = array( 
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
        
        $this->application = null;
    }
    
    private function loadCircuits( Application $application, 
        SimpleXMLElement $node ) {
    	
        if( count( $node->children() ) ) {
            foreach( $node->children() as $key => $child ) {
	            if( $key == 'circuit' ) {
	            	$this->loadCircuit( $application, $child );
	            }
            }
        }
    }
    
    private function loadCircuit( Application $application, 
        SimpleXMLElement $node ) {
    	
        $referenceMethods = array( 
            "name" => "setName", 
            "alias" => "setName",
            "path" => "setPath",
            "parent" => "setParent"
        );
        	
        $reference = new BasicCircuitReference();
        	
        foreach( $node->attributes() as $key => $attribute ) {
        	if( isset( $referenceMethods[ strtolower( $key ) ] ) ) {
                $reference->$referenceMethods[ 
                    strtolower( $key ) ](   "" . $attribute );
            }
        }
        
        $application->addReference( $reference );
        
    }
    
    private function loadClasses( Application $application, 
        SimpleXMLElement $node ) {
        	
    }
    
    private function loadParameters( Application $application, 
        SimpleXMLElement $node ) {
            
    }
    
    private function startTag($parser, $name, $attribs) {
        
        $this->path[] = $name;
        
        /*foreach( $this->path as $deph => $element ) {
            if( $deph == 0 ) {
                
                $this->item =& $this->item[ $element ];
            }
            else {
                $this->item =& $this->item["children"][ $element ];
            }
        }*/
        
        if( $this->path === array( "myfuses", "circuits", "circuit" ) ) {
        	
        	$reference = new BasicCircuitReference();
        	
        	foreach( $attribs as $key => $value ) {
        		
        		$name = "";
        		
        		$path = "";
        		
        		$parent = "";
        		
        	    if( isset( $attribs[ 'alias' ] ) ) {
                    $reference->setName( $attribs[ 'alias' ] );
                }
        		
        		if( isset( $attribs[ 'name' ] ) ) {
        			$reference->setName( $attribs[ 'name' ] );
        		}
        		
        		if( isset( $attribs[ 'path' ] ) ) {
        			$reference->setPath( $attribs[ 'path' ] );
        		}
        		
        	    if( isset( $attribs[ 'parent' ] ) ) {
        	    	$reference->setParent( $attribs[ 'parent' ] );
                }
                
        	}
        	
        	$this->addApplicationReference( $this->application, $reference );
        }
        
        if( $this->path === array( "myfuses", "parameters", "parameter" ) ) {
            $this->setApplicationParameter( $this->application, 
                $attribs[ 'name' ], $attribs[ 'value' ] );
        }
        
        /*$this->item[ "name" ] = $name;
        $this->item[ "namespace" ] = "myfuses";
        
        $this->item[ "attributes" ] = array();
        
        if (is_array($attribs)) {
          foreach( $attribs as $key => $val ) {
            $this->item[ "attributes" ][ $key ] =  "" . $val ;
          }
        }*/
    }

    private function endTag($parser, $name) {
        array_pop( $this->path );
    }
    
}