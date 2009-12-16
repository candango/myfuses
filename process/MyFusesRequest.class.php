<?php
class MyFusesRequest {
	
	private $application;
	
	private $defaultFuseaction;
        
    private $fuseactionVariable;
	
    private $currentFuseaction;
    
    /**
     * Url path extra parameters
     *
     * @var array
     */
    private $extraParams = array();
    
	public function getApplication() {
		return $this->application;
	}
	
	public function setApplication( Application $application ) {
		$this->application = $application;
	}
	
    /**
     * Getter function for DefaultFuseaction
     *
     * @return string
     */
    public function getDefaultFuseaction(){
        return $this->defaultFuseaction;
    }
    
    /**
     * Setter function for DefaultFuseaction
     *
     * @param $defaultFuseaction string
     */
    public function setDefaultFuseaction( $defaultFuseaction ){
        $this->defaultFuseaction = $defaultFuseaction;
    }
    
    /**
     * Getter function for FuseactionVariable
     *
     * @return string
     */
    public function getFuseactionVariable(){
        return $this->fuseactionVariable;
    }
    
    /**
     * Setter function for FuseactionVariable
     *
     * @param $fuseactionVariable string
     */
    public function setFuseactionVariable( $fuseactionVariable ){
        $this->fuseactionVariable = $fuseactionVariable;
    }
	
    /**
     * Getter function for DefaultFuseaction
     *
     * @return string
     */
    public function getCurrentFuseaction(){
        return $this->currentFuseaction;
    }
    
    /**
     * Setter function for DefaultFuseaction
     *
     * @param $defaultFuseaction string
     */
    public function setCurrentFuseaction( $currentFuseaction ){
        $this->currentFuseaction = $currentFuseaction;
    }
    
}