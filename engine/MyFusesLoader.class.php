<?php
/**
 * Interface that defines one My Fuses Loader
 * 
 * 
 */
interface MyFusesLoader {
    
    const XML_LOADER = 0;
    
    /**
     * Do fisical load
     *
     * @param Application $application
     */
    public function doLoadApplication( Application $application );
    
    /**
     * Load the aplication
     *
     * @param Application $application
     */
    public function loadApplication( Application $application );
    
    public function applicationWasModified( Application $application );
    
    public function loadCircuit( Circuit $circuit );
    
    public function circuitWasModified( Circuit $circuit );
    
    public function getApplicationData( Application $application );
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */