<?php
class ClassDefinition {
    
    private $name;
    
    private $path;
    
    private $application;
    
    public function getName() {
        return $this->name;
    }
    
    public function setName( $name ) {
        $this->name = $name;
    }
    
    public function getPath() {
        return $this->path;
    }

    public function setPath( $path ) {
        $this->path = $path;
    }
    
    public function getCompletePath() {
        return $this->getApplication()->getPath() . $this->getPath();
    }
    
    /**
     * Get the Class Definition Application
     * 
     * @return Application
     */
    public function getApplication() {
        return $this->application;
    }

    /**
     * Set the Class Definition Application
     *
     * @param Application $application
     */
    public function setApplication( Application $application ) {
        $this->application = $application;
    }

}