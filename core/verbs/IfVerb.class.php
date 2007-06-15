<?php
/**
 * If file
 *
 */
class IfVerb extends AbstractVerb {
    
    
    private $condition;
    
    private $trueVerbs = array();
    
    private $falseVerbs = array();
    
    public function getCondition() {
        return $this->condition;
    }
    
    public function setCondition( $condition ) {
        $this->condition = $condition;
    }

    public function getData() {
        $data = parent::getData();
        
        $data[ "attributes" ][ "condition" ] =  $this->getCondition();
        
        if( count( $this->trueVerbs ) ) {
            $child[ "name" ] = "true";
            foreach( $this->trueVerbs as $verb ) {
                $child[ "children" ][] = $verb->getData();
            }
            $data[ "children" ][] = $child;
        }
        
        unset( $child );
        
        if( count( $this->falseVerbs ) ) {
            $child[ "name" ] = "false";
            foreach( $this->falseVerbs as $verb ) {
                $child[ "children" ][] = $verb->getData();
            }
            $data[ "children" ][] = $child;
        }
        
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        
        $this->setCondition( $data[ "attributes" ][ "condition" ] );
        
        if( isset( $data[ "children" ] ) ) {
	        if( count( $data[ "children" ] ) ) {
	            
	            foreach( $data[ "children" ] as $child ) {
	                
	                $type = $child[ "name" ];
	                
	                if ( count( $child[ "children" ] ) ) {
	                    $this->setIfVerbs( $type, $child[ 'children' ] );    
	                }
	                
	            }
	        }    
        }
        
    }
    
    private function setIfVerbs( $type, $children ) {
        
        $method = "";
        
        if( $type == 'true' ) {
            $method = "addTrueVerb";
        }
        else {
            $method = "addFalseVerb";
        }
           
        foreach( $children as $child ){
            $verb = AbstractVerb::getInstance( serialize( $child ), $this->getAction() );
            if( !is_null( $verb ) ) {
                $this->$method( $verb );
            }
        }
        
    }
    
    /**
     * Add a true verb
     *
     * @param Verb $verb
     */
    public function addTrueVerb( Verb $verb ) {
       $this->trueVerbs[] = $verb;
       $verb->setParent( $this ); 
    }
    
	/**
     * Add a false verb
     *
     * @param Verb $verb
     */
    public function addFalseVerb( Verb $verb ) {
       $this->falseVerbs[] = $verb;
       $verb->setParent( $this ); 
    }
	
    /**
	 * Return the parsed code
	 *
	 * @return string
	 */
    public function getParsedCode( $commented, $identLevel ) {
	    $strOut = parent::getParsedCode( $commented, $identLevel );
	    
	    $trueOccour = false;
	    
	    if( count( $this->trueVerbs ) ) {
	        $strOut .= str_repeat( "\t", $identLevel );
	        
	        $strOut .= "if( " . $this->getCondition() . " ) {\n";
		    
		    foreach(  $this->trueVerbs as $verb ) {
		        $strOut .= $verb->getParsedCode( $commented, $identLevel + 1 );
		    }
		    
		    $strOut .= str_repeat( "\t", $identLevel );
		    
		    $strOut .= "}\n";
		    
		    $trueOccour = true;
	    }

	    if( count( $this->falseVerbs ) ) {
	        
	        $strOut .= str_repeat( "\t", $identLevel );
	        
	        if( $trueOccour ) {
	            $strOut .= "else {\n";
	        }
	        else {
	            $strOut .= "if( !( " . $this->getCondition() . " ) ) {\n";
	        }
	        
	        foreach(  $this->falseVerbs as $verb ) {
	            $strOut .= $verb->getParsedCode( $commented, $identLevel + 1 );
	        }

	        $strOut .= str_repeat( "\t", $identLevel );

	        $strOut .= "}\n";
	        
	    }
	    
	    return $strOut;
    }
	
	/**
	 * Return the parsed comments
	 *
	 * @return string
	 */
	public function getComments( $identLevel ) {
	    
	    $strOut = parent::getComments( $identLevel );
        $strCondition = "condition=\"" . $this->getCondition() . "\"";
        $strOut = str_replace( "__COMMENT__",
	        "MyFuses:request:action:if " . $strCondition , $strOut );
	    return $strOut;
	    
	}

}