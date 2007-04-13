<?php
/**
 * Enter description here...
 *
 */
abstract class AbstractVerb implements Verb {
    
    private static $verbTypes = array(
            "do" => "DoVerb",
            "if" => "IfVerb",
            "include" => "IncludeVerb",
            "instantiate" => "InstantiateVerb",
            "invoke" => "InvokeVerb",
            "loop" => "LoopVerb",
            "relocate" => "RelocateVerb",
            "set" => "SetVerb",
            "xfa" => "XfaVerb");
    
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
        
        if ( isset( self::$verbTypes[ $data[ "name" ] ] ) ) {
            MyFuses::includeCoreFile( MyFuses::ROOT_PATH . "core" . 
                DIRECTORY_SEPARATOR . "verbs" . DIRECTORY_SEPARATOR .
                self::$verbTypes[ $data[ "name" ] ] . ".class.php" );
        
	        $verb = new self::$verbTypes[ $data[ "name" ] ]();
	        
	        if( !is_null( $action ) ) {
	            $verb->setAction( $action );
	        }
	        
	        $verb->setData( $data );

	        return $verb;
        }
        return null;
    }
    
    public function getCachedCode() {
	    $data = $this->getData();
	    $data = serialize( $data );
	    $data = addslashes( $data );
	    $data = str_replace( '$', '\$', $data );
	    $strOut = "\$verb = AbstractVerb::getInstance( \"" . $data . "\"  , \$action );\n";
        return $strOut;
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

}