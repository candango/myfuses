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
use Candango\MyFuses\Exceptions\VerbException;

/**
 * SwitchVerb  - SwitchVerb.php
 * 
 * This is a conditional verb. You can use this verb to switch between multiples
 * processes queues.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      e11ab240af266452d5e789c9a3d41eba683a3de9
 */
class SwitchVerb extends AbstractVerb
{
    /**
     * Condition to be switched
     *
     * @var string
     */
    private $condition;

	/**
     * Case verbs collection
     *
     * @var array
     */
    private $caseVerbs = array();

    /**
     * Default verbs collection
     *
     * @var array
     */
    private $defaultVerbs = array();

    /**
     * Return the switch condition
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Set the switch condition
     *
     * @param string $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    public function getData()
    {
        $data = parent::getData();
        $data['attributes']['condition'] =  $this->getCondition();

        if (count($this->caseVerbs)) {
            foreach ($this->caseVerbs as $key => $verbs) {
                $child = null;
                $child['name'] = "case";
                $child['namespace'] = "myfuses";
                $child['attributes']['value'] = $key;
                foreach ($verbs as $verb) {
                    $child['children'][] = $verb->getData();
                }
                $data['children'][] = $child;
            }
            
        }

        if (count( $this->defaultVerbs)) {
            $child = null;
            $child['name'] = "default";
            $child['namespace'] = "myfuses";
            foreach ($this->defaultVerbs as $verb) {
                $child[ 'children' ][] = $verb->getData();
            }
            $data['children'][] = $child;
        }

        return $data;
    }

    public function setData($data)
    {
        parent::setData($data);
        if (isset($data['attributes']['condition'])) {
            $this->setCondition($data['attributes']['condition']);
        } else {
            $params = $this->getErrorParams();
            $params['attrName'] = "condition";
            throw new VerbException($params,
                VerbException::MISSING_REQUIRED_ATTRIBUTE);
        }

        if (isset( $data['children'])) {
	        if (count( $data['children'])) {
	            foreach ($data['children'] as $child) {
	                switch ($child['name']) {
	                    case 'case':
	                        $this->setCaseVerbs($child);
	                        break;
	                    case 'default':
	                        $this->setDefaultVerbs($child);
	                        break;
	                }
	            }
	        }
        }
    }

    /**
     * Set the switch case verbs
     *
     * @param array $caseChild
     * @throws VerbException
     */
    private function setCaseVerbs($caseChild)
    {
        if (isset($caseChild['attributes']['value'])) {
            $this->caseVerbs[$caseChild['attributes']['value']] = array();

            if (isset($caseChild['children'])) {
    	        if (count( $caseChild['children'])) {
        	        foreach ($caseChild['children'] as $child) {
                        $verb = AbstractVerb::getInstance($child,
                            $this->getAction());
                        $verb->setParent($this);
                        if (!is_null($verb)) {
                            $this->caseVerbs[
                                $caseChild['attributes']['value']][] = $verb;
                        }
                    }
    	        }    
            }
        } else {
            $params = $this->getErrorParams();
            $params['verbName'] = 'case';
            $params['attrName'] = "condition";
            throw new VerbException($params,
                VerbException::MISSING_REQUIRED_ATTRIBUTE);
        }
    }

    /**
     * Set the switch default verbs
     *
     * @param array $defaultChild
     */
    private function setDefaultVerbs($defaultChild)
    {
        if (isset( $defaultChild['children'])) {
	        if (count($defaultChild['children'])) {
	            foreach ($defaultChild['children'] as $child) {
                    $verb = AbstractVerb::getInstance($child,
                        $this->getAction());
                    $verb->setParent($this);
                    if (!is_null($verb)) {
                        $this->defaultVerbs[] = $verb;
                    }
                }
	        }    
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

	    $switchOccour = false;

	    if (count($this->caseVerbs) || count($this->defaultVerbs)) {
	        $switchOccour = true;
	    }

	    if ($switchOccour) {
	        $strOut .= str_repeat("\t", $identLevel);
	        $strOut .= "switch (" . $this->getCondition() . ") {\n";
	    }

        foreach ($this->caseVerbs as $key => $caseVerbs) {
            if (count($caseVerbs)) {
                $strOut .= str_repeat("\t", $identLevel + 1);
                $strOut .= "case (\"" . $key . "\") :\n";
                foreach ($caseVerbs as $verb) {
                    $strOut .= $verb->getParsedCode(
                        $commented, $identLevel + 2);
                }
                $strOut .= str_repeat("\t", $identLevel + 2);
                $strOut .= "break;\n";
                InvokeVerb::clearClassCall();
            }
		}

        if (count($this->defaultVerbs)) {
            $strOut .= str_repeat("\t", $identLevel + 1);
            $strOut .= "default :\n";
            foreach ($this->defaultVerbs as $verb) {
                $strOut .= $verb->getParsedCode($commented, $identLevel + 2);
            }
            $strOut .= str_repeat("\t", $identLevel + 2);
            $strOut .= "break;\n";
            InvokeVerb::clearClassCall();
        }

        if ($switchOccour) {
	        $strOut .= str_repeat( "\t", $identLevel );
	        $strOut .= "}\n";
	    }
	    return $strOut;
    }
}
