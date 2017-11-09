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
 * Parameterized Verb  - ParameterizedVerb.php
 * 
 * This is an abstract verb that handlers parameters.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      14a2de62f32d468d97979d4e9e6127d1f6df1b66
 */
abstract class ParameterizedVerb extends AbstractVerb
{
    /**
     * Verb parameters
     *
     * @var array
     */
    private $parameters = array();

    /**
     * Return verb parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set verb parameters
     *
     * @param string $name
     * @param string $value
     */
    public function addParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function getData()
    {
        $data = parent::getData();
        if (!is_null( $this->getParameters())) {
            foreach ($this->getParameters() as $name => $value) {
                $child = array();
                $child['name' ] = "parameter";
                $child['namespace'] = "myfuses";
                $child['attributes']['name'] = $name;
                $child['attributes']['value'] = $value;
                $data['children'][] = $child;
            }
        }
        return $data;
    }

    public function setData($data)
    {
        parent::setData($data);
        if (isset($data['children'])) {
            foreach ($data['children'] as $child) {
                $name = null;
                $value = null;
                if ($child[ 'name' ] == "parameter") {
                    if (isset($child['attributes']['name'])) {
                        $name = $child['attributes']['name'];
                    } else {
                        $params = $this->getErrorParams();
                        $params['verbName'] = "parameter";
                        $params['attrName'] = "name";
                        throw new VerbException($params,
                            VerbException::MISSING_REQUIRED_ATTRIBUTE);
                    }
                    if (isset($child['attributes']['value'])) {
                        $value = $child['attributes']['value'];
                    }
                }
                $this->addParameter($name, $value);
            }
        }
    }

    /**
     * Return the real parsed code
     *
     * @return string
     */
    public abstract function getRealParsedCode($commented, $identLevel);

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode($commented, $identLevel)
    {
        $strOut = parent::getParsedCode($commented, $identLevel);
        // TODO: This is not being used. Check if that should go.
        $id = uniqid();
        $contextClass = "Candango\\MyFuses\\Process\\Context";

        foreach ($this->getParameters() as $name => $value) {
            $strOut .= str_repeat("\t", $identLevel);
            $strOut .=  $contextClass . "::setParameter(\"" . $name .
                "\", \"" . $value . "\");\n";
        }
        $strOut .= $this->getRealParsedCode($commented, $identLevel);

        foreach ($this->getParameters() as  $name => $value) {
            $strOut .= str_repeat("\t", $identLevel);
            $strOut .=  $contextClass . "::restoreParameter(\"" .
                $name . "\");\n";
        }
        $strOut .=  "\n";

        return $strOut;
    }
}
