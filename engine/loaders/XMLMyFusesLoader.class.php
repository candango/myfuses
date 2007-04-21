<?php
require_once "myfuses/core/AbstractVerb.class.php";
require_once "myfuses/core/Application.class.php";
require_once "myfuses/core/ClassDefinition.class.php";
require_once "myfuses/core/Circuit.class.php";
require_once "myfuses/core/FuseAction.class.php";

require_once "myfuses/engine/AbstractMyFusesLoader.class.php";
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
     * @param Application $application
     */
    public function getApplicationData( Application $application ) {
        
        $this->chooseApplicationFile( $application );
        
        $rootNode = $this->loadApplicationFile( $application );
        
        
        return $this->getDataFromXml( $rootNode );
        
    }
    
	/**
     * Find the file that the given application is using
     * TODO Throw some exception here!!!
     *
     * @param Application $application
     * @return boolean
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
    
    public function applicationWasModified( Application $application ) {
        if( filectime( $application->getCompleteFile() ) > 
            $application->getLastLoadTime() ) {
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
    private function loadApplicationFile( Application $application ) {
        
        $appMethods = array( 
            "circuits" => "loadCircuits", 
            "classes" => "loadClasses",
            "parameters" => "loadParameters"
             );
        
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
        
        
        return $this->getDataFromXml( $rootNode );
        
    }
    

    /**
     * Load a circuit file
     * 
     * @param Circuit $circuit
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
        
        $rootNode = new SimpleXMLElement( $fileCode );
        
        return $rootNode;
    }
    
    private function getDataFromXML( SimpleXMLElement $node ) {
        $data[ "name" ] = $node->getName(); 
        
        foreach( $node->attributes() as $attribute ) {
            $data[ "attributes" ][ $attribute->getName() ] = "" . $attribute;
        }
        
        if( count( $node->children() ) ) {
            foreach( $node->children() as $child ) {
                $data[ "children" ][] = $this->getDataFromXML( $child );    
            }
        }
        
        return $data;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */