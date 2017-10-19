<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

/**
 * MyFusesCredential - MyFusesCredential.php
 *
 * MyFuses credential.
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Goncalves Garcia <piraz at candango.org>
 * @since      2e0c26a744b984d6463db487a51387bb4005488e
 */
interface MyFusesCredential
{
	const ALL_ACTIONS = "CAN_ACCESS_ALL_ACTIONS";

	const NO_ACTION = "CAN_ACCESS_NO_ACTION";

    public function addAttribute($attributeName, $attributeValue);

    public function getAttribute($attributeName);

    public function addRole($role);

    public function getRoles();

    public function hasRoles($roles);

    public function setRoles($roles);

    public function isExpired();

    public function setTimeout($timeout);

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
    public function setAuthenticated($authenticated);
}
