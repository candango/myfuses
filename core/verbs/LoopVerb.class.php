<?php
/**
 * Loop verb file
 *
 */
class LoopVerb extends AbstractVerb {
    
    private $condition;
    
    private $loopVerbs = array();
    
    private $from;
    
    private $to;
    
    public function getCondition() {
        return $this->condition;
    }
    
    public function setCondition( $condition ) {
        $this->condition = $condition;
    }
    
    public function getFrom() {
        return $this->from;
    }
    
    public function setFrom( $from ) {
        $this->from = $from;
    }
    
    public function getTo() {
        return $this->to;
    }
    
    public function setTo( $to ) {
        $this->to = $to;
    }
    
    public function getData() {
        $data[ "name" ] = "loop";
        
        if( !is_null( $this->getCondition() ) ) {
            $data[ "condition" ] = $this->getCondition();
        }
        else {
            if( !is_null( $this->getFrom() ) ) {
                $data[ "attributes" ][ "from" ] = $this->getFrom();
                $data[ "attributes" ][ "to" ] = $this->getTo();
            }
        }
        
        if( count( $this->loopVerbs ) ) {
            foreach( $this->loopVerbs as $verb ) {
                $data[ "children" ][] = $verb->getData();
            }
        }
        
        return $data;
    }
    
    public function setData( $data ) {
        
        if( isset( $data[ "attributes" ][ "condition" ] ) ) {
            $this->setCondition( $data[ "attributes" ][ "condition" ] );
        }
        else {
            // TODO handle mising parameter error
            if( isset( $data[ "attributes" ][ "from" ] ) ) {
                $this->setFrom( $data[ "attributes" ][ "from" ] );
                $this->setTo( $data[ "attributes" ][ "to" ] );
            }
            
        }
        
        
        if( count( $data[ "children" ] ) ) {
            $this->setLoopVerbs( $data[ 'children' ] );
        }
        
    }
    
    private function setLoopVerbs( $children ) {
        
        foreach( $children as $child ){
            $verb = AbstractVerb::getInstance( serialize( $child ), $this->getAction() );
            if( !is_null( $verb ) ) {
                $this->addLoopVerb( $verb );
            }
        }
        
    }
    
	/**
     * Add one verb into loop verbs
     *
     * @param Verb $verb
     */
    public function addLoopVerb( Verb $verb ) {
       $this->loopVerbs[] = $verb;
       $verb->setParent( $this ); 
    }
    
}