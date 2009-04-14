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
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2009 Candango Open Source Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: MyFuses.class.php 662 2009-03-11 04:30:31Z flavio.garcia $
 */

/**
 * MyFuses XML loader can load all xml metadata and transform in myFuses data
 * structures.
 * 
 * PHP version 5
 *
 * @category   engine
 * @package    myfuses.engine.loaders
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2009 Candango Open Source Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision: 662 $
 * @since      Revision 17
 */
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
    
    
}