<?php
/**
 * Include file
 *
 */
class IncludeVerb extends AbstractVerb {
    
    /**
     * Verb file
     *
     * @var string
     */
    private $file;
    
    /**
     * Return the verb file
     *
     * @return string
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Set the verb file
     *
     * @param string $file
     */
    public function setFile( $file ) {
        $this->file = $file;
    }
    
    public function getData() {
        $data[ "name" ] = "include";
        $data[ "attributes" ][ "file" ] = $this->getFile();
        return $data;
    }
    
    public function setData( $data ) {
        $file = "";
        
        if( isset( $data[ "attributes" ][ "file" ] ) ) {
            $file = $data[ "attributes" ][ "file" ];
        }
        
        if( isset( $data[ "attributes" ][ "template" ] ) ) {
            $file = $data[ "attributes" ][ "template" ];
        }
        
        $this->setFile( $file );
    }
    
}