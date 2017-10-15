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

require_once "myfuses/core/verbs/InvokeVerb.class.php";

/**
 * IfVerb  - IfVerb.php
 * 
 * This is a conditional verb. Using one condition IfVerb will switch the 
 * processes execution by true or false queues.
 *
 * @category   controller
 * @package    myfuses.core.verbs
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      5a0c505168c823101071032bec66f107a870dfff
 */
class IfVerb extends AbstractVerb
{
    private $condition;

    private $trueVerbs = array();

    private $falseVerbs = array();

    public function getCondition()
    {
        return $this->condition;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    public function getData()
    {
        $data = parent::getData();
        $data['attributes']['condition'] =  $this->getCondition();

        if (count($this->trueVerbs)) {
            $child['name'] = "true";
            foreach ($this->trueVerbs as $verb) {
                $child['children'][] = $verb->getData();
            }
            $data['children'][] = $child;
        }

        unset($child);

        if (count($this->falseVerbs)) {
            $child['name'] = "false";
            foreach ($this->falseVerbs as $verb) {
                $child['children'][] = $verb->getData();
            }
            $data['children'][] = $child;
        }
        return $data;
    }

    public function setData($data)
    {
        parent::setData($data);

        $this->setCondition($data['attributes']['condition']);

        if (isset($data['children'])) {
	        if (count($data['children'])) {
	            foreach ($data['children'] as $child) {
	                $type = $child['name'];

	                if (isset($child['children'])) {
	                    if (count($child['children'])) {
    	                    $this->setIfVerbs($type, $child['children']);
    	                }
	                }
	            }
	        }    
        }
    }

    private function setIfVerbs($type, $children)
    {
        $method = "";

        if ($type === 'true') {
            $method = "addTrueVerb";
        } else {
            $method = "addFalseVerb";
        }

        foreach ($children as $child) {
            $verb = AbstractVerb::getInstance($child, $this->getAction());
            if (!is_null($verb)) {
                $this->$method($verb);
            }
        }
    }

    /**
     * Add a true verb
     *
     * @param Verb $verb
     */
    public function addTrueVerb(Verb $verb)
    {
       $this->trueVerbs[] = $verb;
       $verb->setParent($this);
    }

	/**
     * Add a false verb
     *
     * @param Verb $verb
     */
    public function addFalseVerb(Verb $verb)
    {
       $this->falseVerbs[] = $verb;
       $verb->setParent($this);
    }

    /**
	 * Return the parsed code
	 *
	 * @return string
	 */
    public function getParsedCode($commented, $identLevel)
    {
	    $strOut = parent::getParsedCode($commented, $identLevel);

	    $trueOccour = false;

	    $strCondition = $this->getCondition();
	    $strCondition = str_replace(array(" NEQ ", " NeQ ", " nEQ ", " neQ ",
            " NEq ", " Neq ", " nEq ", " neq "), " != ", $strCondition);
	    $strCondition = str_replace(array(" EQ ", " eQ ", " EQ ", " eQ "),
	       " == ", $strCondition);
        $strCondition = str_replace(array(" IS ", " iS ", " Is ", " is "),
            " == ", $strCondition);
	    $strCondition = str_replace(array(" LTE ", " lTE ", " LtE ", " ltE ",
            " LTe ", " lTe ", " Lte ", " lte " ), " <= ", $strCondition);
	    $strCondition = str_replace(array(" GTE ", " gTE ", " GtE ", " gtE ",
	        " GTe ", " gTe ", " Gte ", " gte "), " >= ", $strCondition);
	    $strCondition = str_replace(array(" LT ", " lT ", " Lt ", " lt " ),
	        " < ", $strCondition);
        $strCondition = str_replace(array(" GT ", " gT ", " Gt ", " gt "),
            " > ", $strCondition);
	    $strCondition = str_replace(array(" OR ", " oR ", " Or ", " or " ),
	        " || ",  $strCondition);
	    $strCondition = str_replace(array(" AND ", " AnD ", " aND ", " anD ",
	        " ANd ", " And ", " aNd ", " and " ), " && ", $strCondition);

	    if (count($this->trueVerbs)) {
	        $strOut .= str_repeat("\t", $identLevel);
	        $strOut .= "if( " . $strCondition . " ) {\n";

		    foreach ($this->trueVerbs as $verb) {
		        $strOut .= $verb->getParsedCode($commented, $identLevel + 1);
		    }
		    $strOut .= str_repeat("\t", $identLevel);
		    $strOut .= "}\n";

		    $trueOccour = true;
	    }
        InvokeVerb::clearClassCall();
	    if (count($this->falseVerbs)) {
	        $strOut .= str_repeat("\t", $identLevel);

	        if ($trueOccour) {
	            $strOut .= "else {\n";
	        } else {
	            $strOut .= "if( !( " . $strCondition . " ) ) {\n";
	        }

	        foreach ($this->falseVerbs as $verb) {
	            $strOut .= $verb->getParsedCode($commented, $identLevel + 1);
	        }

	        $strOut .= str_repeat("\t", $identLevel);
	        $strOut .= "}\n";
	    }
	    InvokeVerb::clearClassCall();
	    return $strOut;
    }
}
