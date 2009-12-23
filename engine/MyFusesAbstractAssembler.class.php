<?php
/**
 * MyFusesAbstractAssembler - MyFusesAbstractAssembler.class.php
 * 
 * This is an abstract implementation of MyFusesAssembler interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement MyFusesAssembler inteface and you will save you a lot of 
 * work.
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
 * The Original Code is MyFuses "a Candango implementation of Fusebox 
 * Corporation Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2010.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id: ClassDefinition.class.php 379 2008-04-14 03:04:45Z flavio.garcia $
 */

/**
 * This is an abstract implementation of MyFusesAssembler interface. This class
 * implements all required methods required by his interface and need to be
 * extended by a concrete class to enable his instantiating. Extend this class
 * insted implement MyFusesAssembler inteface and you will save you a lot of 
 * work.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @copyright  Copyright (c) 2006 - 2010 Candango Group <http://www.candango.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:23 $
 * @since      Revision 750
 */
abstract class MyFusesAbstractAssembler implements MyFusesAssembler {
    
    /**
     * (non-PHPdoc)
     * @see engine/MyFusesAssembler#assemblyApplication()
     */
    public function assemblyApplication( Application $application, $data ) {
        
        $methods = array( 
            "circuits" => "assemblyCircuitReferences", 
            "classes" => "assemblyClasses",
            "parameters" => "assemblyParameters"
        );
        
        foreach( $data[ 'children' ] as $child ) {
            if( isset( $methods[ strtolower( $child[ 'name' ] ) ] ) ) {
                $this->$methods[ strtolower( $child[ 'name' ] ) ]( 
                    $application, $child );
            }   
        }
        
    }
    
    /**
     * Assembly in one give application all circuit references using the data
     * created by the loader.
     * 
     * @param $application
     * @param $data
     */
    private function assemblyCircuitReferences( Application $application, 
        $data ) {
        if( count( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                if( $child[ 'name' ] == 'circuit' ) {
                    $this->assemblyCircuitReference( $application, $child );
                }
            }
        }
    }
    
    /**
     * Assembly one circuit reference in one give application using the data
     * created by the loader. 
     * 
     * @param $application
     * @param $data
     */
    private function assemblyCircuitReference( Application $application, 
        $data ) {
        $methods = array( 
            "name" => "setName", 
            "alias" => "setName",
            "path" => "setPath",
            "parent" => "setParent"
        );
            
        $reference = new BasicCircuitReference();
            
        foreach( $data[ 'attributes' ] as $key => $value ) {
            if( isset( $methods[ strtolower( $key ) ] ) ) {
                $reference->$methods[ 
                    strtolower( $key ) ](   "" . $value );
            }
        }
        
        $application->addReference( $reference );
    }
    
    private function assemblyClasses( Application $application, $data ) {
        if( count( $data[ 'children' ] ) ) {
            foreach( $data[ 'children' ] as $child ) {
                if( $child[ 'name' ] == 'class' ) {
                    $this->assemblyClass( $application, $child );
                }
            }
        }  
    }
    
    private function assemblyClass( Application $application, $data ) {
        $methods = array( 
            "name" => "setName", 
            "alias" => "setName",
            "path" => "setPath",
            "classpath" => "setPath"
        );
            
        $definition = new BasicClassDefinition();
        
        foreach( $data[ 'attributes' ] as $key => $value ) {
            if( isset( $methods[ strtolower( $key ) ] ) ) {
                $definition->$methods[ strtolower( $key ) ]( $value );
            }
        }
        
        $application->addClass( $definition );
    }
    
    private function assemblyParameters( Application $application, $data ) {
        foreach( $data[ 'children' ] as $parameter ) {
            $name = "";
            $value = "";
            foreach( $parameter[ 'attributes' ] as $_key => $_value ) {
                if( strtolower( $_key ) == "name" ) {
                    $name = $_value;
                }
                
                if( strtolower( $_key ) == "value" ) {
                    $value = $_value;
                }
            }

            $this->setApplicationParameter( $application, $name, $value );    
        }
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
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */