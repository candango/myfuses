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
    public function doLoadApplication();
    
    /**
     * Load the aplication
     *
     * @param Application $application
     */
    public function loadApplication();
    
    public function applicationWasModified();
    
    public function loadCircuit( Circuit $circuit );
    
    public function circuitWasModified( Circuit $circuit );
    
    public function getApplicationData();
    
    /**
     * Return the application
     *
     * @return Application
     */
    public function &getApplication();
    
    /**
     * Set the loader Application
     *
     * @param Application $application
     */
    public function setApplication( Application &$application );
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */