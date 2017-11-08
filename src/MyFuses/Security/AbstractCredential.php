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

namespace Candango\MyFuses\Security;

require_once MYFUSES_ROOT_PATH . "util/security/MyFusesCredential.php";

/**
 * MyFusesAbstractCredential - MyFusesAbstractCredential.php
 *
 * MyFuses Abstract Credential
 *
 * @category   security
 * @package    myfuses.util.security
 * @author     Flavio Goncalves Garcia <flavio.garcia at candango.org>
 * @since      2e0c26a744b984d6463db487a51387bb4005488e
 */
class AbstractCredential implements Credential
{
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
    private $timeout = 900;

    /**
     * expiration time
     *
     * @var int
     */
    private $expirationTime = 0;

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
    public function __construct()
    {
        $this->createTime = time();
        $this->expirationTime = time() + $this->timeout;
    }

    /**
     * Return an attribute value
     *
     * @param String $attributeName
     * @return mixed
     */
    public function getAttribute($attributeName)
    {
        return $this->attributes[$attributeName];
    }

    /**
     * Add an attribute value
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     */
    public function addAttribute($attributeName, $attributeValue)
    {
        $this->attributes[$attributeName] = $attributeValue;
    }

    /**
     * Add new role to credential
     *
     * @param string $role
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    /**
     * Return all credential roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function hasRoles($roles)
    {
        $roleX = explode(",", $roles);

        foreach ($roleX as $role) {
            foreach ($this->getRoles() as $roleMe) {
                if ($role == $roleMe) {
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
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function isExpired()
    {
        if ($this->expirationTime < time()) {
            return true;
        }
        return false;
    }

    /**
     * Increase the navigation lime left
     *
     */
    public function increaseNavigationTime()
    {
        $this->expirationTime = time() + $this->timeout;
    }

    public function getExpirationDate($format = "m/d/Y h:i:s")
    {
        return date($format, $this->expirationTime);
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        $this->increaseNavigationTime();
    }

    /**
     * Returns if the credential is atenticated
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    /**
     * Set credential atenticated status
     *
     * @param boolean $authenticated
     */
    public function setAuthenticated($authenticated)
    {
    	$this->authenticated = $authenticated;
        if ($this->authenticated) {
            $this->increaseNavigationTime();
        }
    }

    /**
     * Add one circuit to the credencial
     * 
     * @param $circuit
     */
    public function addCircuit($circuit)
    {
    	if (!isset($this->circuits[$circuit ])) {
    		$this->circuits[$circuit] = array();
    	}
    }

    /**
     * Add one action to the credencial
     * 
     * @param $circuit
     * @param $action
     */
    public function addAction($circuit, $action)
    {
        $actionExists = false;
    	if (isset($this->circuits[$circuit ])) {
            foreach ($this->circuits[$circuit] as $actionRegistered) {
            	if ($actionRegistered == $action) {
            		$actionExists = true;
            	}
            }
        }

        if (!$actionExists) {
            $this->circuits[$circuit][] = $action;
        }
    }

    /**
     * Verifies if the credential is allowed to access the given circuit
     * 
     * @param $circuit
     * @return boolean
     */
    public function isAllowedCircuit($circuit)
    {
    	if (isset($this->circuits[$circuit])) {
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
    public function isAllowedAction($circuit, $action)
    {
        if (isset($this->circuits[$circuit][$action])) {
            return true;
        }
        return false;
    }

    public function getData()
    {
        $data = array();
        $data['attributes'] = $this->attributes;
        $data['roles'] = $this->roles;
        $data['circuits'] = $this->circuits;
        $data['created'] = $this->createTime;
        $data['timeout'] = $this->timeout;
        $data['expires'] = $this->expirationTime;
        $data['authenticated'] = $this->authenticated;

        return $data;
    }

    public function setData($data)
    {
        if (isset($data['attributes'])) {
            $this->attributes = $data['attributes'];
        }
        if (isset($data['roles'])) {
            $this->roles = $data['roles'];
        }
        if (isset($data['circuits'])) {
            $this->circuits = $data['circuits'];
        }
        if(isset($data['created'])) {
            $this->createTime = $data['created'];
        }
        if(isset($data['timeout'])) {
            $this->timeout = $data['timeout'];
        }
        if(isset($data['expires'])) {
            $this->expirationTime = $data['expires'];
        }
        if(isset($data['authenticated'])) {
            $this->authenticated = $data['authenticated'];
        }
    }
}
