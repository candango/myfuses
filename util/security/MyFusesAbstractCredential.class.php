<?php
require_once "myfuses/util/security/MyFusesCredential.class.php";

class MyFusesAbstractCredential implements MyFusesCredential {
    
    /**
     * Credential name
     *
     * @var string
     */
    private $name;
    
    /**
     * Credential password
     *
     * @var string
     */
    private $password;
    
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
     * Default constructor
     *
     */
    public function __construct() {
        $this->createTime = time();
    }
    
    /**
     * Return credential name
     *
     * @return string
     */
    public function getName() {
        return $name;
    }
    
    /**
     * Set credential name
     *
     * @param string $name
     */
    public function setName( $name ){
        $this->name = $name;
    }
    
    /**
     * Return credential password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }
    
    /**
     * Set credential password
     *
     * @param string $password
     */
    public function setPassword( $password ){
        $this->password = $password;
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
    
}