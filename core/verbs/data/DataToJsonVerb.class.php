<?php
class DataToJsonVerb extends AbstractVerb {
    
    private $jsonName;
    
    private $value;
    
    private $root;
    
    private $die = true;
    
    private $clean = false;
    
    public function getJsonName() {
        return $this->jsonName;
    }
    
    public function setJsonName( $jsonName ) {
        $this->jsonName = $jsonName;
    }
    
    public function getValue(){
        return $this->value;
    }
    
    public function setValue( $value ) {
        $this->value = $value;
    }
    
    public function isDie(){
        return $this->die;
    }
    
    public function setDie( $die ) {
        $this->die = $die;
    }
    
    public function isClean(){
        return $this->clean;
    }
    
    public function setClean( $clean ) {
        $this->clean = $clean;
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
        
        if( isset( $data[ "attributes" ][ "name" ] ) ) {
            $this->setName( $data[ "attributes" ][ "name" ] );
        }
        
        if( isset( $data[ "attributes" ][ "value" ] ) ) {
            $this->setValue( $data[ "attributes" ][ "value" ] );
        }
        
        if( isset( $data[ "attributes" ][ "clean" ] ) ) {
            $this->setClean( strtolower( 
                $data[ "attributes" ][ "clean" ] ) == "true" ? true : false );
        }
        
        if( isset( $data[ "attributes" ][ "die" ] ) ) {
            $this->setDie( strtolower( 
                $data[ "attributes" ][ "die" ] ) == "true" ? true : false );
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
        
        if( is_null( $this->getJsonName() ) ) {
            
            if( $this->isClean() ) {
                $strOut .= str_repeat( "\t", $identLevel );
                $strOut .= "ob_clean();\n";
            }
            
            $strOut .= "print( MyFusesJsonUtil::toJson( \"" . 
                $this->getValue() . "\" ) );\n";
            
            if( $this->isDie() ) {
                $strOut .= str_repeat( "\t", $identLevel );
                $strOut .= "die();\n";
            }    
        }
        
        return $strOut;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */