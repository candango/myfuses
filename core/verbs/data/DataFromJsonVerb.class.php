<?php
class DataToJsonVerb extends AbstractVerb {
    
    private $url;
    
    private $name;
    
    public function getName() {
        return $name;
    }
    
    public function setName( $name ) {
        $this->name = $name;
    }
    
    public function getUrl(){
        return $this->url;
    }
    
    public function setUrl( $url ) {
        $this->url = $url;
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
        
        if( isset( $data[ "attributes" ][ "url" ] ) ) {
            $this->setUrl( $data[ "attributes" ][ "url" ] );
        }
        
        if( isset( $data[ "attributes" ][ "xfa" ] ) ) {
            $this->setUrl( MyFuses::getMySelfXfa( 
                $data[ "attributes" ][ "xfa" ] ) );
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
        
            $strOut = parent::getParsedCode( $commented, $identLevel );
            
            $strOut .= str_repeat( "\t", $identLevel );
            
            $strOut .= self::getVariableSetString( $this->getName(), 
                "" );
            
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
        
        
        return $strOut;
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */