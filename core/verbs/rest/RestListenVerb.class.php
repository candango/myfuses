<?php
class RestListenVerb extends AbstractVerb {
    
    private $variable;
    
    private $method;
    
    public function getVariable(){
        return $this->variable;
    }
    
    public function setVariable( $variable ) {
        $this->variable = $variable;
    }
    
    public function getMethod(){
        return $this->method;
    }
    
    public function setMethod( $method ) {
        $this->method = $method;
    }
    
    /**
     * Set verb data
     * 
     * @param array $data
     */
    public function setData( $data ) {
        
        parent::setData( $data );
        
        if( isset( $data[ "attributes" ][ "variable" ] ) ) {
            $this->setVariable( $data[ "attributes" ][ "variable" ] );
        }
        
        if( isset( $data[ "attributes" ][ "method" ] ) ) {
            $this->setMethod( $data[ "attributes" ][ "method" ] );
        }
        
    }
    
    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        $strOut = parent::getParsedCode( $commented, $identLevel );
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "var_dump( " . $this->getVariable() . " );\n";
        
        if( $this->isDie() ) {
            $strOut .= str_repeat( "\t", $identLevel );
            $strOut .= "die();\n";
        }
        
        return $strOut;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */