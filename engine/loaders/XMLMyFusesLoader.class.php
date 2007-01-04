<?php
class XMLMyFusesLoader extends AbstractMyFusesLoader {
    
    /**
     * My Fuses application file constant
     * 
     * @var string
     * @access public
     * @static
     */
    const MYFUSES_APP_FILE = "myfuses.xml";
    
    /**
     * My Fuses php application file constant
     * 
     * @var string
     * @access public
     * @static 
     */
    const MYFUSES_PHP_APP_FILE = "myfuses.xml.php";
    
    /**
     * Enter description here...
     *
     * @param Application $application
     */
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
    
    /**
     * Load the application file
     * 
     * @param Application $application
     * @access private
     */
    private function loadApplicationFile( Application $application ) {
        
        $appMethods = array( "circuits" => "loadCircuits" );
        
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
        
        $rootNode = new SimpleXMLElement( $fileCode );
        
        if( count( $rootNode > 0 ) ) {
            foreach( $rootNode as $node ) {
                if ( isset( $appMethods[ $node->getName() ] ) ) {
                    $this->$appMethods[ $node->getName() ]( $application, 
                        $node );
                }                
            }
        }
        
    }

    private function loadCircuits( Application $application, 
        SimpleXMLElement $parentNode ) {
        
        $circuitMethods = array(
            "name" => "setName",
            "alias" => "setName",
            "path" => "setPath",
            "parent" => "setParentName"
        );
        
        if( count( $parentNode > 0 ) ) {
            foreach( $parentNode as $node ) {
                $name = "";
                $path = "";
                $parent = "";
                $circuit = new Circuit();
                foreach( $node->attributes() as $attribute ) {
	                if ( isset( $circuitMethods[ $attribute->getName() ] ) ) {
	                    $circuit->$circuitMethods[ $attribute->getName() ]( 
	                        "" . $attribute );
	                }
                }
                
                $application->addCircuit( $circuit );
            }
        }
        
        var_dump( $application->getCircuit( "user" )->getParent() );
        
    }
    
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */