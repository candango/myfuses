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

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $comented, $identLevel ) {
        $strOut = str_repeat( "\t", $identLevel );
        $strOut .= "\$" . $this->getName() . " = \"" . 
            $this->getValue() . "\";\n\n";
        return $strOut; 
    }

    /**
     * Return the parsed comments
     *
     * @return string
     */
    public function getComments( $identLevel ) {
        
    }
}