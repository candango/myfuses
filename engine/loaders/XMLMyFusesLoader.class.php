<?php
try {
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "core/AbstractPlugin.class.php" );
	MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
	    "core/AbstractVerb.class.php" );
	MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
	    "core/Application.class.php" );
	MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
	    "core/ClassDefinition.class.php" );
	MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
	    "core/Circuit.class.php" );
	MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
	    "core/FuseAction.class.php" );
	
	MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
	    "engine/AbstractMyFusesLoader.class.php" );    
}
catch( MyFusesMissingCoreFileException $mfmcfe ) {
    $mfmcfe->breakProcess();
}

/**
 * 
 */
class XMLMyFusesLoader extends AbstractMyFusesLoader {
    
    /**
     * My Fuses application file constant
     * 
     * @var string
     * @static
     */
    const MYFUSES_APP_FILE = "myfuses.xml";
    
    /**
     * My Fuses php application file constant
     * 
     * @var string
     * @static 
     */
    const MYFUSES_PHP_APP_FILE = "myfuses.xml.php";
    
    const CIRCUIT_FILE = "circuit.xml";
    
    const CIRCUIT_PHP_FILE = "circuit.xml.php";
    
    /**
     * Enter description here...
     *
     * @return array
     */
    public function getApplicationData() {
        
        $this->chooseApplicationFile();
        
        $rootNode = $this->loadApplicationFile();
        
        return self::getDataFromXml( "myfuses", $rootNode );
        
    }
    
	/**
     * Find the file that the given application is using
     * TODO Throw some exception here!!!
     *
     * @param Application $application
     * @return boolean
     */
    private function chooseApplicationFile() {
        if ( is_file( $this->getApplication()->getPath() . 
            self::MYFUSES_APP_FILE ) ) {
            $this->getApplication()->setFile( self::MYFUSES_APP_FILE );
            return true;
        }
        
        if ( is_file( $this->getApplication()->getPath() . 
            self::MYFUSES_PHP_APP_FILE ) ) {
            $this->getApplication()->setFile( self::MYFUSES_PHP_APP_FILE );
            return true;
        }
        
        return false;
    }
    
    public function applicationWasModified() {
        if( filectime( $this->getApplication()->getCompleteFile() ) > 
            $this->getApplication()->getLastLoadTime() ) {
            return true;
        }
        return false;
    }
    
    public function circuitWasModified( Circuit $circuit ) {
        
        if( filectime( $circuit->getCompleteFile() ) > 
            $circuit->getLastLoadTime() ) {
            return true;
        }
        
        return false;
    }
    
    // TODO Throw some exception here!!!
    private function chooseCircuitFile( Circuit $circuit ) {
        
        $circuitPath = $circuit->getApplication()->getPath() . $circuit->getPath();
        
        if ( is_file( $circuitPath . self::CIRCUIT_FILE ) ) {
            $circuit->setFile( self::CIRCUIT_FILE );
            return true;
        }
        
        if ( is_file( $circuitPath . self::CIRCUIT_APP_FILE ) ) {
            $circuit->setFile( self::CIRCUIT_APP_FILE );
            return true;
        }
        
        return false;
    }
    
    /**
     * Load the application file
     * 
     * @param Application $application
     */
    private function loadApplicationFile() {
        
        $appMethods = array( 
            "circuits" => "loadCircuits", 
            "classes" => "loadClasses",
            "parameters" => "loadParameters"
             );
        
        // TODO verify if all conditions is satisfied for a file load ocours
        if ( @!$fp = fopen( $this->getApplication()->
            getCompleteFile() ,"r" ) ) {
            throw new MyFusesFileOperationException( 
                $this->getApplication()->getCompleteFile(), 
                MyFusesFileOperationException::OPEN_FILE );
        }
        
        if ( !flock( $fp, LOCK_SH ) ) {
            throw new MyFusesFileOperationException( 
                $this->getApplication()->getCompleteFile(), 
                MyFusesFileOperationException::LOCK_FILE );
        }
        
        $fileCode = fread( $fp, filesize( $this->getApplication()->
            getCompleteFile() ) );
        
        $rootNode = new SimpleXMLElement( $fileCode );
        
        return $rootNode;
        
    }
    
    
    /**
     * Enter description here...
     *
     * @param Circuit $circuit
     */
    public function getCircuitData( Circuit $circuit ) {
        
        $this->chooseCircuitFile( $circuit );
        
        $rootNode = $this->loadCircuitFile( $circuit );
        
        return self::getDataFromXml( "circuit", $rootNode );
        
    }
    

    /**
     * Load a circuit file
     * 
     * @param Circuit $circuit
     * @return SimpleXMLElement
     */
    private function loadCircuitFile( Circuit $circuit ) {
        
        $circuitPath = $circuit->getApplication()->getPath() . $circuit->getPath();
        
        $circuitFile = $circuitPath . $circuit->getFile();
        
        // TODO verify if all conditions is satisfied for a file load ocours
        if ( @!$fp = fopen( $circuitFile ,"r" ) ){
            throw new MyFusesFileOperationException( 
                $circuitFile, MyFusesFileOperationException::OPEN_FILE );
        }
        
        if ( !flock( $fp, LOCK_SH ) ) {
            throw new MyFusesFileOperationException( 
                $circuitFile, MyFusesFileOperationException::LOCK_FILE );
        }
        
        $fileCode = fread( $fp, filesize( $circuitFile ) );
        
        try {
            // FIXME put no warning modifier in SimpleXMLElement call 
            @$rootNode = new SimpleXMLElement( $fileCode );    
        }
        catch ( Exception $e ) {
            // FIXME handle error
            die( "Parse error" );    
        }
        
        return $rootNode;
    }
    
    public static function getDataFromXML( $name, SimpleXMLElement $node ) {
        $nameX = explode( "_ns_", $name );
        
        if( count( $nameX ) > 1 ) {
            $data[ "name" ] = $nameX[ 1 ];
            $data[ "namespace" ] = $nameX[ 0 ];
        }
        else {
            $data[ "name" ] = $name;
            $data[ "namespace" ] = "myfuses";
        }
        
        if( count( $node->getDocNamespaces( true ) ) ) {
            $data[ "docNamespaces" ] = $node->getDocNamespaces( true );
        }
        
        foreach( $node->attributes() as $key => $attribute ) {
            $data[ "attributes" ][ $key ] = "" . $attribute;
        }
        
        if( count( $node->children() ) ) {
            foreach( $node->children() as $key => $child ) {
                // PoG StYlEzZz
                $child = new SimpleXMLElement( preg_replace( 
                    "@([<|</])(\w+|\d+):(\w+|\d+)( |)@", "$1$2_ns_$3$4", 
                    $child->asXML() ) );
                $data[ "children" ][] = self::getDataFromXML( $key, $child );    
            }
        }
        
        return $data;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */