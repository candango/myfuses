<?php
/**
 * MyFusesXmlLoader - MyFusesXmlLoader.class.php
 * 
 * MyFuses XML loader can load all xml metadata and transform in myFuses data
 * structures.
 * 
 * PHP version 5
 * 
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * This product includes software developed by the Fusebox Corporation 
 * (http://www.fusebox.org/).
 * 
 * The Original Code is myFuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2009.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   engine
 * @package    myfuses.engine.loaders
 * @author     Flavio Goncalves Garcia <flavio dot garcia at candango dot org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

/**
 * MyFuses XML loader can load all xml metadata and transform in myFuses data
 * structures.
 * 
 * PHP version 5
 *
 * @category   engine
 * @package    myfuses.engine.loaders
 * @author     Flavio Goncalves Garcia <flavio dot garcia at candango dot org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 125
 */
class MyFusesXmlLoader extends MyFusesAbstractLoader {
    
    /**
     * This constant contains the name of application file descriptor used in 
     * the Fusebox framework. This constant is used to maintain compatibility.
     * 
     * @var string
     */
    const FUSEBOX_APP_FILE = "fusebox.xml";
    
    /**
     * This constant contains the name of application file descriptor used in 
     * the Fusebox framework but the extension is php. This constant is used 
     * to maintain compatibility.
     * 
     * @var string
     */
    const FUSEBOX_APP_PHP_FILE = "fusebox.xml.php";
	
    /**
     * This constant contains the name of application file descriptor used by
     * default in the myFuses framework.
     * 
     * @var string
     */
    const MYFUSES_APP_FILE = "myfuses.xml";
    
    /**
     * This constant contains the name of application file descriptor used in 
     * the myFuses framework but the extension is php.
     * 
     * @var string
     */
    const MYFUSES_APP_PHP_FILE = "myfuses.xml.php";
    
    /**
     * (non-PHPdoc)
     * @see engine/MyFusesLoader#getApplicationData()
     */
    public function getApplicationData( Application $application ) {
        // TODO if the result of choose application file is false
        // throw exception
        $result = $this->chooseApplicationFile( $application ); 
        
        $rootNode = $this->loadApplicationFile( $application );
        
        $data = self::getDataFromXml( "myfuses", $rootNode );
        
        $data[ 'file' ] = $application->getFile();
        
        return $data;
    }
    
    /**
     * Fill the application with the name of the application descriptor file
     * founded in the application root path. MyFuses descriptor files has
     * priority over fusebox files.
     * 
     * @param $application The application verified
     * @return boolean
     */
    public function chooseApplicationFile( Application $application ) {
        
        if( is_file( $application->getPath() . self::MYFUSES_APP_FILE ) ) {
            $application->setFile( self::MYFUSES_APP_FILE );
            return true;
        }
        
        if ( is_file( $application->getPath() . self::MYFUSES_APP_PHP_FILE ) ) {
            $application->setFile( self::MYFUSES_APP_PHP_FILE );
            return true;
        }
        
        if ( is_file( $application->getPath() . self::FUSEBOX_APP_FILE ) ) {
            $application->setFile( self::FUSEBOX_APP_FILE );
            return true;
        }
        
        if ( is_file( $application->getPath() . self::FUSEBOX_APP_PHP_FILE ) ) {
            $application->setFile( self::FUSEBOX_APP_PHP_FILE );
            return true;
        }
        
        return false;
    }
    
    /**
     * Load the application file and returns one SimpleXMLElement with the xml
     * document structure
     * 
     * @param Application $application
     * @return SimpleXMLElement The root elemet
     */
    private function loadApplicationFile( Application $application ) {
        
        $fileCode = MyFusesFileHandler::readFile( 
            $application->getCompleteFile() );
        
        /*MyFuses::getInstance()->getDebugger()->registerEvent( 
            new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                "Getting Application file \"" . 
                $this->getApplication()->getCompleteFile() . "\"" ) );*/
        
        try {
            // FIXME put no warning modifier in SimpleXMLElement call
            $rootNode = @new SimpleXMLElement( $fileCode );    
        }
        catch ( Exception $e ) {
            // FIXME handle error
            echo "<b>" . $this->getApplication()->
                getCompleteFile() . "<b><br>";
            die( $e->getMessage() );    
        }
        
        return $rootNode;
        
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
        
        if( count( $node->children() ) ) {
            foreach( $node->children() as $key => $child ) {
                if( $key == 'class' ) {
                    $this->loadClass( $application, $child );
                }
            }
        }
        	
    }
    
    private function loadClass( Application $application, 
        SimpleXMLElement $node ) {
        
        $definitionMethods = array( 
            "name" => "setName", 
            "alias" => "setName",
            "path" => "setPath",
            "classpath" => "setPath"
        );
            
        $definition = new BasicClassDefinition();
            
        foreach( $node->attributes() as $key => $attribute ) {
            if( isset( $definitionMethods[ strtolower( $key ) ] ) ) {
                $definition->$definitionMethods[ 
                    strtolower( $key ) ](   "" . $attribute );
            }
        }
        
        $application->addClass( $definition );
    }
    
    private function loadParameters( Application $application, 
        SimpleXMLElement $node ) {
        
        foreach( $node as $parameter ) {
            $name = "";
            $value = "";
	        foreach( $parameter->attributes() as $_key => $_value ) {
	            if( strtolower( $_key ) === "name" ) {
	            	$name = "" . $_value;
	            }
	            
	            if( strtolower( $_key ) === "value" ) {
                    $value = "" . $_value;
                }
	        }

	        $this->setApplicationParameter( $application, $name, $value );    
        }
    }
    
    /**
     * This function digs the descriptor file and build an array of meta data
     * that will be used by the abastract loader.
     * 
     * @param $name
     * @param $node
     * @return array An array of strings
     */
    public static function getDataFromXML( $name, SimpleXMLElement $node ) {
        $nameX = explode( "_ns_", $name );
        
        if( count( $nameX ) > 1 ) {
            $data[ "name" ] = $nameX[ 1 ];
            $data[ "namespace" ] = $nameX[ 0 ];
        }
        else {
            $data[ "name" ] = $name;
            $data[ "namespace" ] = "myfuses";
        }
        
        if( count( $node->getDocNamespaces( true ) ) ) {
            $data[ "docNamespaces" ] = $node->getDocNamespaces( true );
            
            foreach( $data[ "docNamespaces" ] as $namespace => $value ) {
                foreach( $node->attributes( $namespace, true ) as 
                    $name => $attribute ) {
                    $data[ "namespaceattributes" ][ $namespace ][ $name ] = 
                        "" . $attribute;
                }
            }
        }
        
        foreach( $node->attributes() as $key => $attribute ) {
            $data[ "attributes" ][ $key ] =  "" . $attribute ;
        }
        
        if( count( $node->children() ) ) {
            foreach( $node->children() as $key => $child ) {
                // PoG StYlEzZz
                $xml = preg_replace( 
                    "@([<|</])(\w+|\d+):(\w+|\d+)( |)@", "$1$2_ns_$3$4", 
                    $child->asXML() );
                $xml = preg_replace( 
                    "@(\w+|\d+):(\w+|\d+)([=])@", "$1_ns_$2$3", $xml );
                $child = new SimpleXMLElement( preg_replace( 
                    "@([<|</])(\w+|\d+):(\w+|\d+)( |)@", "$1$2_ns_$3$4", 
                    $xml ) );
                $data[ "children" ][] = self::getDataFromXML( $key, $child );    
            }
        }
        
        return $data;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */