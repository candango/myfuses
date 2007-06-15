<?php
class InstantiateVerb extends AbstractVerb {
    
    private $class;
    
    private $object;
    
    private $arguments;
    
    public function getClass() {
        return $this->class;
    }
    
    public function setClass( $class ) {
        $this->class = $class;
    }
    
    public function getObject() {
        return $this->object;
    }

    public function setObject( $object ) {
        $this->object = $object;
    }
    
    public function getArguments() {
        return $this->arguments;
    }

    public function setArguments( $arguments ) {
        $this->arguments = $arguments;
    }
    
    public function getData() {
        $data = parent::getData();
        $data[ "name" ] = "instantiate";
        $data[ "attributes" ][ "class" ] = $this->getClass();
        $data[ "attributes" ][ "object" ] = $this->getObject();
        if( !is_null( $this->getArguments() ) ) {
            $data[ "attributes" ][ "arguments" ] = $this->getArguments();
        }
        return $data;
    }

    public function setData( $data ) {
        parent::setData( $data );
        $this->setClass( $data[ "attributes" ][ "class" ] );
        
        $this->setObject( $data[ "attributes" ][ "object" ] );
        
        if( isset( $data[ "attributes" ][ "arguments" ] ) ) {
            $this->setArguments( $data[ "attributes" ][ "arguments" ] );
        }

    }
    
	/**
	 * Return the parsed code
	 *
	 * @return string
	 */
	public function getParsedCode( $commented, $identLevel ) {
	    $appName = $this->getAction()->getCircuit()->
	        getApplication()->getName();
        
	    $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();
	        
	    $fileCall = $controllerClass . "::getApplication( \"" . $appName .
	        "\" )->getClass( \"" . $this->getClass() . 
	        "\" )->getCompletePath()";

	    $strOut = parent::getParsedCode( $commented, $identLevel );
	    $strOut .= str_repeat( "\t", $identLevel );
	    $strOut .= "if ( file_exists( " . $fileCall . " ) ) {\n";
	    $strOut .= str_repeat( "\t", $identLevel + 1 );
	    $strOut .= "require_once( " . $fileCall . " );\n";
	    $strOut .= str_repeat( "\t", $identLevel );
	    $strOut .= "}\n";
	    $strOut .= str_repeat( "\t", $identLevel );
	    $strOut .= "\$" . $this->getObject() . " = new " . 
	        $this->getClass() . "( " . $this->getArguments() . " );\n\n";
	    
	
	    return $strOut;
	}
	
	/**
	 * Return the parsed comments
	 *
	 * @return string
	 */
	public function getComments( $identLevel ) {
	    $strOut = parent::getComments( $identLevel );
	
	    $strInst = "class=\"" . $this->getClass() . "\"";
	    $strInst .= " object=\"" . $this->getObject() . "\"";
	    if( !is_null( $this->getArguments() ) ) {
	        $strInst .= " arguments=\"" . $this->getArguments() . "\"";
	    }
	    
	    $strOut = str_replace( "__COMMENT__",
	        "MyFuses:request:action:instantiate " . $strInst, $strOut );
	    
	    return $strOut;
	}

}