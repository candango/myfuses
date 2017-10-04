<?php
interface MyFusesCredential {
    
	const ALL_ACTIONS = "CAN_ACCESS_ALL_ACTIONS";
	
	const NO_ACTION = "CAN_ACCESS_NO_ACTION";
	
    public function addAttribute( $attributeName, $attributeValue );
    
    public function getAttribute( $attributeName );

    public function addRole( $role );

    public function getRoles();
    
    public function hasRoles( $roles );
    
    public function setRoles( $roles );

    public function isExpired();
    
    public function setTimeout( $timeout );

    public function getData();

    public function setData($data);

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