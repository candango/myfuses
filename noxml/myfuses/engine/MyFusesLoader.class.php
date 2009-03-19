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
    
    private $parser;
    
    private $path = array();
    
    /**
     * 
     * @var Application
     */
    private $application;
    
    public function __construct() {
        
        if( !( $this->parser = xml_parser_create() ) ) { 
            die ("Cannot create parser");
        }
        
        xml_set_object( $this->parser, $this );
        
        xml_set_element_handler( $this->parser, "startTag", "endTag" );

        //xml_set_character_data_handler( $this->parser, "tagContents" );

        xml_parser_set_option( $this->parser, XML_OPTION_CASE_FOLDING, 0 );
        
    }
    
    public function loadApplication( Application $application ) {
        
        $this->application = $application;
        
        $path = $application->getPath();
        
        $file = $path . "myfuses.xml";
        
        if( file_exists( $file ) ) {
            
            $data = MyFusesFileHandler::readFile( $file );
            
            if ( ! xml_parse( $this->parser, $data ) ) {
                $reason = xml_error_string( xml_get_error_code( $xmlparser ) );
                $reason .= xml_get_current_line_number( $xmlparser );
                die( $reason );
            }
            
        }
        
        $this->application = null;
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