<?php
class XMLMyFusesLoader extends AbstractMyFusesLoader {
    
    /**
     * MyFuses app file constat
     * 
     * @var string
     * @access public
     * @static
     */
    const MYFUSES_APP_FILE = "myfuses.xml";
    
    /**
     * Enter description here...
     *
     */
    const MYFUSES_PHP_APP_FILE = "myfuses.xml.php";
    
    public function doLoad( Application $application ) {
        
        $this->chooseApplicationFile( $application );
        
        $this->loadApplicationFile( $application );
        
    }
    
	/**
     * Find the file that the given application is using
     *
     * @param Application $application
     * @return boolean
     * @access private
     */
    private function chooseApplicationFile( Application $application ) {
        if ( is_file( $application->getPath() . self::MYFUSES_APP_FILE ) ) {
            $application->setFile( self::MYFUSES_APP_FILE );
            return true;
        }
        
        if ( is_file( $application->getPath() . self::MYFUSES_PHP_APP_FILE ) ) {
            $application->setFile( self::MYFUSES_PHP_APP_FILE );
            return true;
        }
        
        return false;
    }
    
    private function loadApplicationFile( Application $application ) {
        
        // TODO verify if all conditions is satisfied for a file load ocours
        if ( @!$fp = fopen( $application->getCompleteFile() ,"r" ) ){
            throw new MyFusesFileOperationException( 
                $application->getCompleteFile(), 
                MyFusesFileOperationException::OPEN_FILE );
        }
        
        if ( !flock( $fp, LOCK_SH ) ) {
            throw new MyFusesFileOperationException( 
                $application->getCompleteFile(), 
                MyFusesFileOperationException::LOCK_FILE );
        }
        
        $fileCode = fread( $fp, filesize( $application->getCompleteFile() ) );
        
        $rootNode = $this->getRootNode( $fileCode );
        
    }
    
    /**
     * Return the root name using a XMLReader to do that
     *
     * @param string $fileCode
     * @return string
     */
    private function getRootName( $fileCode ) {
        $reader = new XMLReader();
        
        $reader->XML( $fileCode );
        
        $reader->read();
        
        $rootName = $reader->name;
        
        $reader->close();
        
        return $rootName;
    }
    
    /**
     * Return thea root node from a xml string
     *
     * @param string $fileCode
     * @return DOMElement
     */
    private function getRootNode( $fileCode ) {
        
        $rootName = $this->getRootName( $fileCode );
        
        $document = new DOMDocument();
        
        $document->loadXML( $fileCode );
        
        $nodeList = $document->getElementsByTagName( $rootName );
        
        foreach ( $nodeList as $node ) {
            return $node;
        }
        
        return null;
        
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */