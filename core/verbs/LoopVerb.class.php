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
    
    private $index;
    
    private $step;
    
    private $list;
    
    private $item;
    
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
    
    public function getIndex() {
        return $this->index;
    }

    public function setIndex( $index ) {
        $this->index = $index;
    }
    
    public function getStep() {
        return $this->step;
    }

    public function setStep( $step ) {
        $this->step = $step;
    }
    
    public function getList() {
        return $this->list;
    }

    public function setList( $list ) {
        $this->list = $list;
    }
    
    public function getItem() {
        return $this->item;
    }

    public function setItem( $item ) {
        $this->item = $item;
    }

    public function getData() {
        $data = parent::getData();
        
        if( !is_null( $this->getCondition() ) ) {
            $data[ "condition" ] = $this->getCondition();
        }
        elseif( !is_null( $this->getFrom() ) ) {
	        $data[ "attributes" ][ "from" ] = $this->getFrom();
	        $data[ "attributes" ][ "to" ] = $this->getTo();
	        $data[ "attributes" ][ "index" ] = $this->getIndex();
	        if( !is_null( $this->getStep() ) ) {
	            $data[ "attributes" ][ "step" ] = $this->getStep();
	        }
        }
        elseif( !is_null( $this->getList() ) ) {
            $data[ "attributes" ][ "list" ] = $this->getList();
            $data[ "attributes" ][ "item" ] = $this->getItem();
        }

        if( count( $this->loopVerbs ) ) {
            foreach( $this->loopVerbs as $verb ) {
                $data[ "children" ][] = $verb->getData();
            }
        }
        
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        
        if( isset( $data[ "attributes" ][ "condition" ] ) ) {
            $this->setCondition( $data[ "attributes" ][ "condition" ] );
        }
        elseif( isset( $data[ "attributes" ][ "from" ] ) ) {
            // TODO handle mising parameter error
            $this->setFrom( $data[ "attributes" ][ "from" ] );
            $this->setTo( $data[ "attributes" ][ "to" ] );
            $this->setIndex( $data[ "attributes" ][ "index" ] );
            if( isset( $data[ "attributes" ][ "step" ] ) ) {
                $this->setStep( $data[ "attributes" ][ "step" ] );
            }
        }
        elseif( isset( $data[ "attributes" ][ "list" ] ) ) {
            $this->setList( $data[ "attributes" ][ "list" ] );
            $this->setItem( $data[ "attributes" ][ "item" ] );
        }
        
        if( count( $data[ "children" ] ) ) {
            $this->setLoopVerbs( $data[ 'children' ] );
        }
        
    }
    
    private function setLoopVerbs( $children ) {
        
        foreach( $children as $child ){
            $verb = AbstractVerb::getInstance( 
                serialize( $child ), $this->getAction() );
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
    

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        $strOut = parent::getParsedCode( $commented, $identLevel );
        $strOut .= str_repeat( "\t", $identLevel );
        if( !is_null( $this->getCondition() ) ) {
            $strOut .= "while( " . $this->getCondition() . " ) {\n";
        }
        elseif( !is_null( $this->getFrom() ) ) {
            $from = $this->getFrom();
            $to = $this->getTo();
            $index = $this->getIndex();
            $step = ( is_null( $this->step ) ) ? 1 : $this->step;
            
            $signal = "<=";
            $signal1 = "+";
            
            
            if( $from > $to ) {
                $signal = "<=";
                $signal = "-";
            }
            
            $strOut .= "for( " . $index . " = " . $from . "; " . 
                $index . " " . $signal . " " . $to . "; " . 
                $index . " = " . $index . " " . $signal1 . " " . $step . " ) {\n";
        }
        elseif( !is_null( $this->getList() ) ) {
            $list = $this->getList();
            $item = $this->getItem();
            $strOut .= "foreach( " . $list . " as " . $item . " ) {\n";
        }

        foreach(  $this->loopVerbs as $verb ) {
            $strOut .= $verb->getParsedCode( $commented, $identLevel + 1 );
        }
        
        $strOut .= str_repeat( "\t", $identLevel );
        
        $strOut .= "}\n";
        
        return $strOut;
    }

}