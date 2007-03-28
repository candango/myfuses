<?php
/**
 * Set verb
 *
 */
class SetVerb extends AbstractVerb {
    
    
    private $value;
    
    public function getValue() {
        return $this->value;
    }
    
    public function setValue( $value ) {
        $this->value = $value;
    }
    
    public function getData() {
        $data[ "name" ] = "set";
        $data[ "attributes" ][ "name" ] = $this->getName();
        $data[ "attributes" ][ "value" ] = $this->getValue();
        return $data;
    }
    
    public function setData( $data ) {
        $this->setName( $data[ "attributes" ][ "name" ] );
        $this->setValue( $data[ "attributes" ][ "value" ] );
    }
    
}