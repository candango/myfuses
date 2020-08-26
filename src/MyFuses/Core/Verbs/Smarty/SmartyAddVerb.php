<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2020 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

use Candango\MyFuses\Core\AbstractVerb;


class SmartyAddVerb extends AbstractVerb
{

    private $instanceName = "default";

    private $templateFile;

    /**
     *
     */
    public function getInstanceName()
    {
        return $this->instanceName;
    }

    /**
     *
     */
    public function setInstanceName($instanceName)
    {
        $this->instanceName = $instanceName;
    }

    /**
     *
     */
    public function getTemplateFile()
    {
        return $this->templateFile;
    }

    /**
     *
     */
    public function setTemplateFile($templateFile)
    {
        $this->templateFile = $templateFile;
    }


    /**
     * Get verb data
     *
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();

        if( $this->getInstanceName() != "default" ) {
            $data['attributes']['template'] = $this->getInstanceName();
        }

        $data['attributes']['file'] = $this->getTemplateFile();

        return $data;
    }

    /**
     * Set verb data
     *
     * @param array $data
     */
    public function setData($data)
    {
        parent::setData($data);

        if(isset($data['attributes']['template']))
        {
            $this->setInstanceName($data['attributes']['template']);
        }

        if(isset($data['attributes']['file']))
        {
            $this->setTemplateFile($data['attributes']['file']);
        }

    }

    /**
     * Return the parsed code
     *
     * @return string
     */
    public function getParsedCode($commented, $identLevel)
    {
        $strOut = parent::getParsedCode($commented, $identLevel );
        $strTemplateHandler = "Candango\MyFuses\Util\Template\TemplateHandler";
        $strOut .= str_repeat("\t", $identLevel);
        $strOut .= "\$templateHandler = " . $strTemplateHandler .
            "::getInstance(\"" . $this->getInstanceName() . "\");\n";
        $strOut .= str_repeat( "\t", $identLevel );
        $strOut .= "\$templateHandler->addFile( \"" .
            $this->getTemplateFile() . "\" );\n\n";
        return $strOut;
    }

}
