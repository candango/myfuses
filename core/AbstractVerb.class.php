<?php
/**
 * Enter description here...
 *
 */
abstract class AbstractVerb implements Verb {
    
    /**
     * Verb action
     *
     * @var CircuitAction
     */
    private $action;
    
    /**
     * Verb name
     *
     * @var string
     */
    private $name;
    
    /**
     * Return the verb Action
     *
     * @return CircuitAction
     */
    public function getAction() {
        return $this->action;
    }
    
    /**
     * Set the verb Action
     *
     * @param CircuitAction $action
     */
    public function setAction( CircuitAction $action ) {
        $this->action = $action;
    }
    
    /**
     * Return the veb name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set the verb name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
    
    /**
     * Return a new string
     *
     * @param string $className
     * @param array $params
     * @param CircuitAction $action
     * @return Verb
     */
    public static function getInstance( $className, $params, CircuitAction $action = null ) {
        MyFuses::includeCoreFile( MyFuses::ROOT_PATH . "core" . 
                DIRECTORY_SEPARATOR . "verbs" . DIRECTORY_SEPARATOR .
                $className . ".class.php" );
        $verb = new $className();
        if( !is_null( $action ) ) {
            $verb->setAction( $action );
        }
        $verb->setParams( $params );
        return $verb;
    }
    
}