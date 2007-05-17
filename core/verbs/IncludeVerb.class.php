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
    
	/**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode( $commented, $identLevel ) {
        $appName = $this->getAction()->getCircuit()->
            getApplication()->getName();
        $circuitName = $this->getAction()->getCircuit()->getName();

        $fileCall = "MyFuses::getApplication( \"" . $appName . 
            "\" )->getCircuit( \"" . $circuitName . "\" )->getCompletePath()";
            
        $strOut = parent::getParsedCode( $commented, $identLevel );
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "if ( file_exists( " . $fileCall . " . \"" . 
            $this->getFile() . "\" ) ) {\n";
        $strOut .= str_repeat( "\t", $identLevel + 1 );
        $strOut .= "include( " . $fileCall . " . \"" . 
            $this->getFile() . "\" );\n";
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "}\n\n";
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
            "MyFuses:request:action:include file=\"" . 
            $this->getFile() . "\"", $strOut );
        return $strOut;
    }

}