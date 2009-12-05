<?php
require_once "myfuses/util/security/MyFusesCredential.class.php";

class MyFusesAbstractCredential implements MyFusesCredential {
    
    /**
     * Credential attributes
     *
     * @var array
     */
    private $attributes = array();
    
    /**
     * Credential roles
     *
     * @var array
     */
    private $roles = array();
    
    /**
     * Credential circuits allowed
     * 
     * @var array
     */
    private $circuits = array();
    
    /**
     * Credential create time
     *
     * @var int
     */
    private $createTime;
    
    /**
     * Credential timeout expiration
     *
     * @var int
     */
    private $timeExpire = 900;
    
    /**
     * Navigation time left
     *
     * @var int
     */
    private $navigationTimeLeft = 0;
    
    /**
     * Credential autenticated flag
     *
     * @var boolean
     */
    private $authenticated = false;
    
    /**
     * Default constructor
     *
     */
    public function __construct() {
        $this->createTime = time();
    }
    
    /**
     * Return an attribute value
     *
     * @param String $attributeName
     * @return mixed
     */
    public function getAttribute( $attributeName ) {
        return $this->attributes[ $attributeName ];
    }
    
    /**
     * Add an attribute value
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     */
    public function addAttribute( $attributeName, $attributeValue ){
        $this->attributes[ $attributeName ] = $attributeValue;
    }
    
    /**
     * Add new role to credential
     *
     * @param string $role
     */
    public function addRole( $role ) {
        $this->roles[] = $role;
    }
    
    /**
     * Return all credential roles
     *
     * @return array
     */
    public function getRoles() {
        return $this->roles;
    }
    
    public function hasRoles( $roles ) {
        $roleX = explode( ',', $roles );
        
        foreach( $roleX as $role ) {
            foreach( $this->getRoles() as $roleMe ) {
                if( $role == $roleMe ) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Set to credential an array of roles
     *
     * @param array $roles
     */
    public function setRoles( $roles ){
        $this->roles = $roles;
    }
    
    public function isExpired() {
        if( ( $this->createTime + $this->timeExpire ) < time() ) {
            return true;
        }
        return false;
    }
    
    /**
     * Increase the navigation lime left
     *
     */
    public function increaseNavigationTime() {
        $this->navigationTimeLeft += $this->timeExpire;
    }
    
    public function getExpireDate( $format = "m/d/Y h:i:s" ) {
        return date( $format, $this->createTime + $this->timeExpire );
    }
    
    public function setExpireTime( $time ) {
        $this->timeExpire = $time;
    }
    
    /**
     * Returns if the credential is atenticated
     *
     * @return boolean
     */
    public function isAuthenticated() {
        return $this->authenticated;
    }
    
    /**
     * Set credential atenticated status
     *
     * @param $autenticated boolean
     */
    public function setAuthenticated( $authenticated ) {
        $this->authenticated = $authenticated;
    }
    
    /**
     * Add one circuit to the credencial
     * 
     * @param $circuit
     */
    public function addCircuit( $circuit ) {
    	if( !isset( $this->circuits[ $circuit ] ) ) {
    		$this->circuits[ $circuit ] = array();
    	}
    }
    
    /**
     * Add one action to the credencial
     * 
     * @param $circuit
     * @param $action
     */
    public function addAction( $circuit, $action ) {
        $actionExists = false;
    	if( isset( $this->circuits[ $circuit ] ) ) {
            foreach( $this->circuits[ $circuit ] as $actionRegistered ) {
            	if( $actionRegistered == $action ) {
            		$actionExists = true;
            	}
            }
        }
        
        if( !$actionExists ) {
            $this->circuits[ $circuit ][] = $action;
        }
    }
    
    /**
     * Verifies if the credential is allowed to access the given circuit
     * 
     * @param $circuit
     * @return boolean
     */
    public function isAllowedCircuit( $circuit ) {
    	if( isset( $this->circuits[ $circuit ] ) ) {
    		return true;
    	}
    	return false;
    }
    
    
    /**
     * Verifies if the credential is allowed to access the given circuit action
     * 
     * @param $circuit
     * @param $action
     * @return boolean
     */
    public function isAllowedAction( $circuit, $action ) {
        if( isset( $this->circuits[ $circuit ][ $action ] ) ) {
            return true;
        }
        return false;
    }
    
}