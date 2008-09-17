<?php
/**
 * Interface that defines one My Fuses Loader
 * 
 * 
 */
interface MyFusesLoader {
    
    const XML_LOADER = 0;
    
    /**
     * Load the aplication
     *
     * @param Application $application
     */
    public function loadApplication();
    
    public function applicationWasModified();
    
    public function circuitWasModified( $name );
    
    public function getApplicationData();
    
    /**
     * Load one circuit
     *
     * @param Circuit $circuit
     */
    public function loadCircuit( Circuit $circuit );
    
    /**
     * Add one application load listener
     *
     * @param MyFusesApplicationLoaderListener $listener
     */
    public function addApplicationLoadListener( 
        MyFusesApplicationLoaderListener $listener );
    
    /**
     * Return the application
     *
     * @return Application
     */
    public function getApplication();
    
    /**
     * Set the loader Application
     *
     * @param Application $application
     */
    public function setApplication( Application $application );
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */