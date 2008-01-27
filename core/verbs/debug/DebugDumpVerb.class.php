<?php
class DebugDumpVerb extends AbstractVerb {
    
    private $variable;
    
    private $die = false;
    
    public function getVariable(){
        return $this->variable;
    }
    
    public function setVariable( $variable ) {
        $this->variable = $variable;
    }
    
    public function isDie(){
        return $this->die;
    }
    
    public function setDie( $die ) {
        if( is_bool( $die ) ) {
            $this->die = $die;    
        }
        else {
            $this->die = ( $die == "true" ) ? true : false;
        }
    }
    
    /**
     * Get verb data
     * 
     * @return array
     */
    public function getData() {
        $data = parent::getData();
        
        $data[ "attributes" ][ "variable" ] = $this->getVariable();
        
        $data[ "attributes" ][ "die" ] = $this->isDie() ? "true" : "false";
        
        return $data;
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
        else  {
            $params = $this->getErrorParams();
            $params[ 'attrName' ] = "variable";
            throw new MyFusesVerbException( $params, 
                MyFusesVerbException::MISSING_REQUIRED_ATTRIBUTE );
        }
        
        if( isset( $data[ "attributes" ][ "die" ] ) ) {
            $this->setDie( $data[ "attributes" ][ "die" ] );
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