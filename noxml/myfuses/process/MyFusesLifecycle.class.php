<?php
abstract class MyFusesLifecycle {
    
    public static function loadApplications( MyFuses $controller ) {
        
        foreach( $controller->getApplications() as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                self::loadApplication( $application );	
            }
        }
        
    }
    
    public static function loadApplication( Application $application ) {
        
    	$loader = new MyFusesXmlLoader();
    	
    	$loader->loadApplication( $application );
    	
    }
    
	public static function createRequest( MyFuses $controller ) {
		
		
		
	}
	
	public static function executeProcess( MyFuses $controller ) {
		
		$application = $controller->getApplication();
		
		if( !$application->isStarted() ) {
			$application->fireApplicationStart();
			$application->setStarted( true );
		}
		
		$application->firePreProcess();
		
		$application->firePostProcess();
		
	}
	
    public static function storeApplications( MyFuses $controller ) {
        
        foreach( $controller->getApplications() as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                self::storeApplication( $application );
            }
        }
        
    }
    
    public static function storeApplication( Application $application ) {
        
    	$serializedApp = "<?php\nreturn unserialize( '" . serialize( $application ) . "' );\n\n";
        
        MyFusesFileHandler::createPath( $application->getParsedPath() );
        
        MyFusesFileHandler::writeFile( $application->getParsedApplicationFile(), $serializedApp );
        
    }
    
    public static function restoreApplication( $applicationName ) {
    	
    	$applicationFile = MyFusesFileHandler::sanitizePath( 
           MyFuses::getInstance()->getRootParsedPath() . 
           $applicationName ) . $applicationName . 
           MyFuses::getInstance()->getStoredApplicationExtension();
    	
    	if( file_exists( $applicationFile ) ) {
    		
    		return include $applicationFile;
    		
    	}
    	
    	return null;
    }
    
}


class MyFusesXmlLoader {
	
	private $parser;
	
	private $struct = array();
	
	private $path = array();
	
	private $item;
	
	public function __construct() {
		
		if( !( $this->parser = xml_parser_create() ) ) { 
		    die ("Cannot create parser");
		}
		
		xml_set_object( $this->parser, $this );
		
		xml_set_element_handler( $this->parser, "startTag", "endTag" );

        xml_set_character_data_handler( $this->parser, "tagContents" );

        xml_parser_set_option( $this->parser, XML_OPTION_CASE_FOLDING, 0 );
        
	}
	
	public function loadApplication( Application $application ) {

		$this->struct = array();
		
		$this->item = null;
		
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
        
        return $this->struct;
	}
	
    private function startTag($parser, $name, $attribs) {
        
    	$this->path[] = $name;
    	
    	$this->item =& $this->struct;
    	
    	foreach( $this->path as $deph => $element ) {
    		if( $deph == 0 ) {
    			
    			$this->item =& $this->item[ $element ];
    		}
    		else {
    			$this->item =& $this->item["children"][ $element ];
    		}
    	}
    	
	    $this->item[ "name" ] = $name;
	    $this->item[ "namespace" ] = "myfuses";
	    
	    $this->item[ "attributes" ] = array();
	    
	    if (is_array($attribs)) {
	      foreach( $attribs as $key => $val ) {
	        $this->item[ "attributes" ][ $key ] =  "" . $val ;
	      }
	    }
    }

    private function endTag($parser, $name) {
    	array_pop( $this->path );
    }

    function tagContents($parser, $data) {
    	if( trim( $data ) !== "" ) {
    		$this->item[ "value" ] = $data;
    	}
    }
	
}