<?php
require_once "myfuses/core/Verb.class.php";
require_once "myfuses/core/CircuitAction.class.php";

/**
 * Enter description here...
 *
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
    public static function getInstance( $data, CircuitAction $action = null ) {
        
        $data = stripslashes( $data );
        
        $data = unserialize( $data );
        
        if ( isset( self::$verbTypes[ $data[ "namespace" ] . ":" . 
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
            if( $action->getCircuit()->verbPathExists( $data[ "namespace" ] ) ) {
                
                $path = $action->getCircuit()->getVerbPath( $data[ "namespace" ] ); 
                
                $className = strtoupper( substr( $data[ "namespace" ], 0, 1 ) ) . 
                    substr( $data[ "namespace" ], 1, strlen( $data[ "namespace" ] ) - 1 )
                    . strtoupper( substr( $data[ "name" ], 0, 1 ) ) . 
                    substr( $data[ "name" ], 1, strlen( $data[ "name" ] ) - 1 )
                    . "Verb";
                    
                MyFuses::includeCoreFile( $path. $className . ".class.php" );
                
                $verb = new $className();
                
		        if( !is_null( $action ) ) {
		            $verb->setAction( $action );
		        }
		        
		        $verb->setData( $data );
		        
		        return $verb;
            }
            else {
                die( "Non existent verb path" );
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

	/**
	 * Return the parsed comments
	 *
	 * @return string
	 */
	public function getComments( $identLevel ) {
	    $fuseactionName = $this->getAction()->getCompleteName();
	    $strOut = str_repeat( "\t", $identLevel );
	    $strOut .= "/* " . $fuseactionName .
	    ": <__COMMENT__> */\n";
	    return $strOut;
	}
    
	public function getErrorParams() {
	    $params = $this->getAction()->getErrorParams();
	    $params[ 'verbName' ] = $this->getName();
	    return $params;
	}
	
}