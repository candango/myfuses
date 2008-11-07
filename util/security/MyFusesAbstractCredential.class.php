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
    private $timeExpire = 5;
    
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
     * Set an attribute value
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     */
    public function setAttribute( $attributeName, $attributeValue ){
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
    
    public function getExpireDate( $format = "m/d/Y h:i:s" ) {
        return date( $format, $this->createTime + $this->timeExpire );
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
    
}