<?php
/**
 * Set verb
 *
 */
class SetVerb extends AbstractVerb {
    
    private $variableName;
    
    private $value;
    
    public function getVariableName() {
        return $this->variableName;
    }
    
    public function setVariableName( $variableName ) {
        $this->variableName = $variableName;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function setValue( $value ) {
        $this->value = $value;
    }
    
    public function getData() {
        $data = parent::getData();
        $data[ "attributes" ][ "name" ] = $this->getVariableName();
        $data[ "attributes" ][ "value" ] = $this->getValue();
        return $data;
    }
    
    public function setData( $data ) {
        parent::setData( $data );
        $this->setVariableName( $data[ "attributes" ][ "name" ] );
        $this->setValue( $data[ "attributes" ][ "value" ] );
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        $strOut = parent::getParsedCode( $commented, $identLevel );
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "\$" . $this->getVariableName() . " = \"" . 
            $this->getValue() . "\";\n\n";
        return $strOut; 
    }

    /**
     * Return the parsed comments
     *
     * @return string
     */
    public function getComments( $identLevel ) {
        $strOut = parent::getComments( $identLevel );
        $strOut = str_replace( "__COMMENT__",
        "MyFuses:request:action:set name=\"" . $this->getVariableName() .
        "\" value=\"" . $this->getValue() . "\"", $strOut );
        return $strOut;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */