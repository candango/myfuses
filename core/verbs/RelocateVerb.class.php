<?php
/**
 * Relocate file
 *
 */
class RelocateVerb extends AbstractVerb {
    
    
    private $url;
    
    public function getUrl() {
        return $this->url;
    }
    
    public function setUrl( $url ) {
        $this->url = $url;
    }

    public function getData() {
        $data[ "name" ] = "relocate";
        $data[ "attributes" ][ "url" ] = $this->getUrl();
        return $data;
    }
    
    public function setData( $data ) {
        $this->setUrl( $data[ "attributes" ][ "url" ] );
    }
    
}