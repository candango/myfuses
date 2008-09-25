<?php
interface MyFusesCredential {

    /**
     * Return credential name
     *
     * @return string
     */
    public function getName();

    /**
     * Set credential name
     *
     * @param string $name
     */
    public function setName( $name );

    /**
     * Return credential password
     *
     * @return string
     */
    public function getPassword();

    /**
     * Enter description here...
     *
     * @param string $password
     */
    public function setPassword( $password );

    public function addRole( $role );

    public function getRoles();

    public function setRoles( $roles );

    public function isExpired();
    
    /**
     * Returns if the credential is athenticated
     *
     * @return boolean
     */
    public function isAuthenticated();
    
    /**
     * Set credential athenticated status
     *
     * @param $authenticated boolean
     */
    public function setAuthenticated( $authenticated );
    
}