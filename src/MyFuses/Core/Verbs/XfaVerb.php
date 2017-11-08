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

namespace Candango\MyFuses\Core\Verbs;

/**
 * XfaVerb  - XfaVerb.php
 *
 * This is the eXit Fuse Action.
 *
 * XFA is the verb used to provide exit FuseActions to current Request
 * FuseAction.
 * The XFA is used in like this:
 * <xfa name="viewCart" value="cart.displayCartContents" />
 * and will add in current FuseAction like this:
 * $action->addXfa( "viewCart", "cart.displayCartContents" );
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      cea291d61da569150c494630909371bd6ff6e3c
 */
class XfaVerb extends AbstractVerb
{
    /**
     * XFA value
     * 
     * @var string
     */
    private $value;

    private $xfaName;

    /**
     * Return the XFA Value
     * 
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the XFA Value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Return the XFA name
     * 
     * @return string
     */
    public function getXfaName()
    {
        return $this->xfaName;
    }

    /**
     * Set the XFA name
     *
     * @param string $xfaName
     */
    public function setXfaName($xfaName)
    {
        $this->xfaName = $xfaName;
    }

    /**
     * Return the XFA data array
     * 
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();
        $data['attributes']['name'] = $this->getXfaName();
        $data['attributes']['value'] = $this->getValue();
        return $data;
    }

    /**
     * Recieve the XFA data array and put and set all properties 
     *
     * @param array $data
     */
    public function setData($data)
    {
        parent::setData($data);

        $this->setXfaName($data['attributes']['name']);

        if (count(explode(".", $data['attributes']['value'])) < 2) {
            $this->setValue($this->getAction()->getCircuit()->getName() .
                "." . $data['attributes']['value']);
        } else {
            $this->setValue($data['attributes']['value']);
        }
    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode($commented, $identLevel)
    {
        $strOut = parent::getParsedCode($commented, $identLevel);
        $strOut .= str_repeat("\t", $identLevel);
        $value = $this->getValue();
        if (count(explode(".", $value)) < 3) {
            if(!$this->getAction()->getCircuit()->getApplication()->
                isDefault()) {
                $value = $this->getAction()->getCircuit()->
                    getApplication()->getName() . "." . $value;
            }
        }

        $controllerClass = $this->getAction()->getCircuit()->
	        getApplication()->getControllerClass();
        $strOut .= $controllerClass . "::getInstance()->getRequest()->" .
            "getAction()->addXFA(\"" . $this->getXfaName() . "\", \"" .
            $value . "\");\n\n";
        // for compatibility
        return $strOut;
    }

}
