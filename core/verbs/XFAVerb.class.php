<?php
/**
 * XFA file
 *
 */
class XFAVerb extends AbstractVerb {
    
    
    private $value;
    
    public function getValue() {
        return $this->value;
    }
    
    public function setValue( $value ) {
        $this->value = $value;
    }

    public function getData() {
        $data[ "name" ] = "xfa";
        $data[ "attributes" ][ "name" ] = $this->getName();
        $data[ "attributes" ][ "value" ] = $this->getValue();
        return $data;
    }
    
    public function setData( $data ) {
        $this->setName( $data[ "attributes" ][ "name" ] );
        if( count( explode( ".", $data[ "attributes" ][ "value" ] ) ) < 2 ) {
            $this->setValue(  $this->getAction()->getCircuit()->getName() . 
                "." . $data[ "attributes" ][ "value" ] );
        }
        else {
            $this->setValue( $data[ "attributes" ][ "value" ] );
        }
        
        $this->getAction()->addXFA( $this );
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $comented, $identLevel ) {

    }

    /**
     * Return the parsed comments
     *
     * @return string
     */
    public function getComments( $identLevel ) {

    }
}