<?php
/**
 * AbstractVerb - AbstractVerb.class.php
 * 
 * AbstractVerb implements various methods defined in Verb interface.
 * All Custom verbs must extend AbstractVerb to be in compliance with the 
 * framework. 
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
 * The Original Code is Fuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flavio Goncalves Garcia.
 * Portions created by Flavio Goncalves Garcia are Copyright (C) 2006 - 2007.
 * All Rights Reserved.
 * 
 * Contributor(s): Flavio Goncalves Garcia.
 *
 * @category   verb
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */

require_once "myfuses/core/Verb.class.php";
require_once "myfuses/core/CircuitAction.class.php";

/**
 * AbstractVerb - AbstractVerb.class.php
 * 
 * AbstractVerb implements various methods defined in Verb interface.
 * All Custom verbs must extend AbstractVerb to be in compliance with the 
 * framework. 
 * 
 * PHP version 5
 *
 * @category   verb
 * @package    myfuses.core
 * @author     Flavio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2007 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 25
 */
abstract class AbstractVerb implements Verb {
    
    private static $verbTypes = array(
            "myfuses:do" => "DoVerb",
            "myfuses:if" => "IfVerb",
            "myfuses:include" => "IncludeVerb",
            "myfuses:instantiate" => "InstantiateVerb",
            "myfuses:invoke" => "InvokeVerb",
            "myfuses:loop" => "LoopVerb",
            "myfuses:relocate" => "RelocateVerb",
            "myfuses:set" => "SetVerb",
            "myfuses:switch" => "SwitchVerb",
            "myfuses:xfa" => "XfaVerb");
    
    /**
     * Verb action
     *
     * @var CircuitAction
     */
    private $action;
    
    /**
     * Verb name
     *
     * @var string
     */
    private $name;
    
    /**
     * Verb namespace
     *
     * @var string
     */
    private $namespace;
    
    /**
     * Verb parent
     * 
     * @var Verb
     */
    private $parent;
    
    /**
     * Return the verb Action
     *
     * @return CircuitAction
     */
    public function getAction() {
        return $this->action;
    }
    
    /**
     * Set the verb Action
     *
     * @param CircuitAction $action
     */
    public function setAction( CircuitAction $action ) {
        $this->action = $action;
    }
    
    /**
     * Return the veb name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set the verb name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Return the veb namespace
     *
     * @return string
     */
    public function getNamespace() {
        return $this->namespace;
    }
    
    /**
     * Set the verb namespace
     *
     * @param string $namespace
     */
    public function setNamespace( $namespace ) {
        $this->namespace = $namespace;
    }
    
    /**
     * Return the verb parent
     *	
     * @return Verb
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * Set the verb parent
     *
     * @param Verb $parent
     */
    public function setParent( Verb $parent ) {
        $this->parent = $parent;
    }
    
    /**
     * Return a new string
     *
     * @param string $className
     * @param array $params
     * @param CircuitAction $action
     * @return Verb
     */
    public static function getInstance( &$data, CircuitAction $action = null ) {
        
        //$data = stripslashes( $data );
        
        //$data = unserialize( $data );
        
        if ( isset( self::$verbTypes[ @$data[ "namespace" ] . ":" . 
            $data[ "name" ] ] ) ) {
            
            MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . "core" . 
                DIRECTORY_SEPARATOR . "verbs" . DIRECTORY_SEPARATOR .
                self::$verbTypes[ $data[ "namespace" ] . ":" . 
                    $data[ "name" ] ] . ".class.php" );
            
	        $verb = new self::$verbTypes[ $data[ "namespace" ] . ":" . 
                    $data[ "name" ] ]();
	        
	        if( !is_null( $action ) ) {
	            $verb->setAction( $action );
	        }
	        
	        $verb->setData( $data );

	        return $verb;
        }
        else {
            if( $action->getCircuit()->verbPathExists( 
                @$data[ "namespace" ] ) ) {
                
                $path = $action->getCircuit()->getVerbPath( 
                    $data[ "namespace" ] ); 
                
                $className = strtoupper( substr( 
                    $data[ "namespace" ], 0, 1 ) ) . 
                    substr( $data[ "namespace" ], 1, 
                    strlen( $data[ "namespace" ] ) - 1 )
                    . strtoupper( substr( $data[ "name" ], 0, 1 ) ) . 
                    substr( $data[ "name" ], 1, strlen( $data[ "name" ] ) - 1 )
                    . "Verb";
                
                if( !is_file( $path. $className . ".class.php" ) ) {
                    $params = $action->getErrorParams();
	                $params[ "verbName" ] = $data[ "name" ]; 
	                    throw new MyFusesVerbException( $params, 
	                        MyFusesVerbException::NON_EXISTENT_VERB );
                }
                
                require_once( $path. $className . ".class.php" );
                
                $verb = new $className();
                
		        if( !is_null( $action ) ) {
		            $verb->setAction( $action );
		        }
		        
		        $verb->setData( $data );
		        
		        return $verb;
            }
            else {
                    $params = $action->getErrorParams();
                    $params[ "verbName" ] = $data[ "name" ]; 
                    throw new MyFusesVerbException( $params, 
                        MyFusesVerbException::MISSING_NAMESPACE );
            }
        }
        return null;
    }
    
    public function getCachedCode() {
        $data = $this->getData();
        //$data[ "name" ] = str_replace( ":", "_ns_", $data[ "name" ] );
        $data = serialize( $data );
        $data = addslashes( $data );
        $data = str_replace( '$', '\$', $data );
        $strOut = "\$verb = AbstractVerb::getInstance( \"" . $data . "\"  , \$action );\n";
        return $strOut;
    }
    
	public function getData() {
	    if( $this->getNamespace() != "myfuses" ) {
	        $data[ "name" ] = $this->getName();
	    }
	    else {
	        $data[ "name" ] = $this->getName();    
	    }
	    $data[ "namespace" ] = $this->getNamespace();
	    return $data;
	}
	
	public function setData( $data ) {
	    $this->setName( $data[ "name" ] );
	    $this->setNamespace( $data[ "namespace" ] );
	}
	
	/**
	 * Return the parsed code
	 *
	 * @return string
	 */
	public function getParsedCode( $commented, $identLevel ) {
	    $strOut = "";
	    if( $commented ) {
	        $strOut = $this->getComments( $identLevel );
	    }
	    return $strOut;
	}

	public function getTrace( $toHtml = false ) {
	    $data = $this->getData();
	    $strTrace = "<" . $data[ "namespace" ] . ":" . $data[ "name" ];
	    if( isset( $data[ "attributes" ] ) ) {
	        foreach( $data[ "attributes" ] as $key => $value ) {
	            $strTrace .= " " . $key . "=\"" . $value . "\"";
	        }
	    }
	    $strTrace .= ">";
	    if( $toHtml ) {
	        return htmlentities( $strTrace );
	    }
	    return $strTrace;
	}
	
	/**
	 * Return the parsed comments
	 *
	 * @return string
	 */
	public function getComments( $identLevel ) {
	    $fuseactionName = $this->getAction()->getCompleteName();
	    $strOut = str_repeat( "\t", $identLevel );
	    $strOut .= "/* " . $fuseactionName .
	    ": " . $this->getTrace() . " */\n";
	    return $strOut;
	}
    
	public function getErrorParams() {
	    $params = $this->getAction()->getErrorParams();
	    $params[ 'verbName' ] = $this->getName();
	    return $params;
	}
	
	protected function getVariableSetString( $variable, $value ) {
	    $strOut = "MyFusesCodeHandler::setVariable( \"" . 
              $variable . "\", \"" . $value . "\" );\n\n";
        return $strOut;      
	}
	
	protected function getIncludeFileString( $fileName ) {
	    $strOut = "MyFusesCodeHandler::includeFile( " . 
	       $fileName . " );\n\n";
        $strOut .= self::getContextRestoreString();
        return $strOut;
	}
	
	protected function getContextRestoreString() {
	    $strOut = "foreach( MyFusesCodeHandler::getContext() as \$key => \$value ) {";
        $strOut .= "global \$\$value;";
        $strOut .= "}\n\n";
        return $strOut;
	}
	
	
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */