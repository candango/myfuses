<?php
class DataToJsonVerb extends AbstractVerb {
    
    private $name;
    
    private $value;
    
    private $root;
    
    private $output = false;
    
    public function getName() {
        return $name;
    }
    
    public function setName( $name ) {
        $this->name = $name;
    }
    
    public function getVariable(){
        return $this->variable;
    }
    
    public function isOutput() {
        return $this->output;
    }
    
    public function setOutput( $output ) {
        $this->output = $output;
    }
    
    public function setVariable( $variable ) {
        $this->variable = $variable;
    }
    
    public function getRoot(){
        return $this->root;
    }
    
    public function setRoot( $root ) {
        $this->root = $root;
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
        
        if( isset( $data[ "attributes" ][ "root" ] ) ) {
            $this->setRoot( $data[ "attributes" ][ "root" ] );
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