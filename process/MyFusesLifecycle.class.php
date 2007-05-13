<?php
class MyFusesLifecycle {
    
    /**
     * Lifecycle Phase
     *
     * @var string
     */
    private $phase;
    
    /**
     * Lifecycle circuit
     *
     * @var Circuit
     */
    private $circuit;
    
    /**
     * Lifecycle action
     *
     * @var CircuitAction
     */
    private $action;
    
    /**
     * Pre process fase constant<br>
     * Value "preProcess"
     * 
     * @var string
     */
    const PRE_PROCESS_PHASE = "preProcess";
    
    /**
     * Pre fuseaction fase constant<br>
     * Value "preFuseaction"
     * 
     * @var string
     */
    const PRE_FUSEACTION_PHASE = "preFuseaction";
    
    /**
     * Post fuseaction fase constant<br>
     * Value "postFuseaction"
     * 
     * @var string
     */
    const POST_FUSEACTION_PHASE = "postFuseaction";
    
    /**
     * Post process fase constant<br>
     * Value "postProcess"
     * 
     * @var string
     */
    const POST_PROCESS_PHASE = "postProcess";
    
    /**
     * Process error fase constant<br>
     * Value "processError"
     * 
     * @var string
     */
    const PROCESS_ERROR_PHASE = "processError";
    
    /**
     * Return the current lifecycle phase
     *
     * @return string
     */
    public function getPhase(){
        return $this->phase;
    }
    
    /**
     * Set the current lifecycle phase
     *
     * @param string $phase
     */
    public function setPhase( $phase ){
        $this->phase = $phase;
    }
    
    /**
     * Return the current lifecycle action
     *
     * @return CircuitAction
     */
    public function &getAction() {
        return $this->action;
    }
    
    /**
     * Set the current lifecycle action
     *
     * @param CircuitAction $circuit
     */
    public function setAction( CircuitAction &$action ) {
        $this->action = $action;
    }
    
}