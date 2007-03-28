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
        $data[ "name" ] = "if";
        
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
        $this->setCondition( $data[ "attributes" ][ "condition" ] );
        
        if( count( $data[ "children" ] ) ) {
            
            foreach( $data[ "children" ] as $child ) {
                
                $type = $child[ "name" ];
                
                if ( count( $child[ "children" ] ) ) {
                    $this->setIfVerbs( $type, $child[ 'children' ] );    
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
    
}