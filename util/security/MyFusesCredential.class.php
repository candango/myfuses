<?php
interface MyFusesCredential {

    public function setAttribute( $attributeName, $attributeValue );
    
    public function getAttribute( $attributeName );

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