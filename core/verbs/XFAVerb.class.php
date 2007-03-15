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
    
    public function getParms() {
        $parms[ "name" ] = $this->getName();
        return $parms;
    }
    
    public function getCachedCode() {
	    $strOut = "\$verb = AbstractVerb::getInstance( \"XFAVerb\", array( \"name\" => \"" . $this->getName() . "\", \"value\" => \"" . $this->getValue() . "\" ) );\n";
        return $strOut;
	}
    
    /**
     * Fill params
     *
     * @param array $params
     */
    public function setParams( $params ) {
        
        $this->setName( $params[ "name" ] );
        $this->setValue( $params[ "value" ] );
        
    }
    
}