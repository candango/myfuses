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

use Candango\MyFuses\Core\AbstractVerb;

/**
 * ResponseVerb  - ResponseVerb.php
 *
 * Sets parameters to the current response.
 *
 * Currently only content type is being set using the parameter type.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      c865d76098ec95139aac1937d3bb1a39bb874c74
 */
class ResponseVerb extends AbstractVerb
{
    /**
     * Response type, or the content type
     * 
     * @var string
     */
    private $type;

    /**
     * Returns the response type
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the response type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Return the response data array
     * 
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();
        $data['attributes']['type'] = $this->getType();
        return $data;
    }

    /**
     * Recieves the response data array and put and set all properties
     *
     * @param array $data
     */
    public function setData($data)
    {
        parent::setData($data);
        $this->setType($data['attributes']['type']);
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
        $controllerClass = "Candango\\MyFuses\\Controller";
        $strOut .= $controllerClass . "::getInstance()->setResponseType(\"" .
            $this->getType() . "\");\n\n";
        return $strOut;
    }
}
