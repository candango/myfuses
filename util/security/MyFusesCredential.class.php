<?php
interface MyFusesCredential {

    public function addAttribute( $attributeName, $attributeValue );
    
    public function getAttribute( $attributeName );

    public function addRole( $role );

    public function getRoles();
    
    public function hasRoles( $roles );
    
    public function setRoles( $roles );

    public function isExpired();
    
    public function setExpireTime( $time );
    
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