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
    
    public function getParms() {
        $parms[ "file" ] = $this->getFile();
        return $parms;
    }
    
    public function getCachedCode() {
	    $strOut = "\$verb = AbstractVerb::getInstance( \"IncludeVerb\", array( \"file\" => \"" . $this->getFile() . "\" ) );\n";
        $strOut .= "\$verb->setFile( \"" . $this->getFile() . "\" );";
        return $strOut;
	}
    
    /**
     * Fill params
     *
     * @param array $params
     */
    public function setParams( $params ) {
        
        $this->setFile( $params[ "file" ] );
        
    }
    
}