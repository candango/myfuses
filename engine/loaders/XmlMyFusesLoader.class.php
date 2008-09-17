<?php
MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "core/AbstractPlugin.class.php" );
MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "core/AbstractVerb.class.php" );
MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "core/Application.class.php" );
MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "core/ClassDefinition.class.php" );
MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "core/BasicCircuit.class.php" );
MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "core/FuseAction.class.php" );
MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
    "engine/AbstractMyFusesLoader.class.php" );

/**
 * 
 */
class XmlMyFusesLoader extends AbstractMyFusesLoader {
    
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
        
        $data = self::getDataFromXml( "myfuses", $rootNode );
        
        $data[ 'file' ] = $this->getApplication()->getFile();
        
        return $data;
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
        $this->chooseApplicationFile();
        if( filectime( $this->getApplication()->getCompleteFile() ) > 
            $this->getApplication()->getLastLoadTime() ) {
            return true;
        }
        return false;
    }
    
    public function circuitWasModified( $name ) {
        $data = $this->getCachedApplicationData();
        
        if( !isset( $data[ 'circuits' ][ 'name' ] ) ) {
            return false;
        }
        
        $file = $this->getApplication()->getPath() . 
            $data[ 'circuits' ][ $name ][ 'attributes' ][ 'path' ] . 
            $data[ 'circuits' ][ $name ][ 'attributes' ][ 'file' ];
        
        if( filectime( $file ) > 
            $data[ 'circuits' ][ $name ][ 'attributes' ][ 'lastloadtime' ] ) {
            return true;
        }
        
        return false;
    }
    
    // TODO Throw some exception here!!!
    private function chooseCircuitFile( Circuit $circuit ) {
        
        $circuitPath = $circuit->getApplication()->getPath() . 
            $circuit->getPath();
          
        if ( is_file( $circuitPath . self::CIRCUIT_FILE ) ) {
            $circuit->setFile( self::CIRCUIT_FILE );
            return true;
        }
        
        if ( is_file( $circuitPath . self::CIRCUIT_PHP_FILE ) ) {
            $circuit->setFile( self::CIRCUIT_PHP_FILE );
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
        
        MyFuses::getInstance()->getDebugger()->registerEvent( 
            new MyFusesDebugEvent( MyFusesDebugger::MYFUSES_CATEGORY, 
                "Getting Application file \"" . 
                $this->getApplication()->getCompleteFile() . "\"" ) );
        
        $fileCode = fread( $fp, filesize( $this->getApplication()->
            getCompleteFile() ) );
        
        try {
            // FIXME put no warning modifier in SimpleXMLElement call
            $rootNode = @new SimpleXMLElement( $fileCode );    
        }
        catch ( Exception $e ) {
            // FIXME handle error
            echo "<b>" . $this->getApplication()->
                getCompleteFile() . "<b><br>";
            die( $e->getMessage() );    
        }
        
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
        
        $circuitFile = $circuit->getCompleteFile();
        
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
            echo "<b>" . $circuitFile . "<b><br>";
            die( $e->getMessage() );    
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
            
            foreach( $data[ "docNamespaces" ] as $namespace => $value ) {
                foreach( $node->attributes( $namespace, true ) as 
                    $name => $attribute ) {
                    $data[ "namespaceattributes" ][ $namespace ][ $name ] = 
                        "" . $attribute;
                }
            }
        }
        
        foreach( $node->attributes() as $key => $attribute ) {
            $data[ "attributes" ][ $key ] =  "" . $attribute ;
        }
        
        if( count( $node->children() ) ) {
            foreach( $node->children() as $key => $child ) {
                // PoG StYlEzZz
                $xml = preg_replace( 
                    "@([<|</])(\w+|\d+):(\w+|\d+)( |)@", "$1$2_ns_$3$4", 
                    $child->asXML() );
                $xml = preg_replace( 
                    "@(\w+|\d+):(\w+|\d+)([=])@", "$1_ns_$2$3", $xml );
                $child = new SimpleXMLElement( preg_replace( 
                    "@([<|</])(\w+|\d+):(\w+|\d+)( |)@", "$1$2_ns_$3$4", 
                    $xml ) );
                $data[ "children" ][] = self::getDataFromXML( $key, $child );    
            }
        }
        
        return $data;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */