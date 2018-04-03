<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\Core;

/**
 * ClassDefinition  - ClassDefinition.php
 *
 * This class handle all class declared in myfuses.xml.
 *
 * @category   controller
 * @package    myfuses.core
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      195974621ca2e59668492bc79113b161f1910dc1
 */
class ClassDefinition implements ICacheable
{
    /**
     * Class name
     *
     * @var string
     */
    private $name;

    /**
     * Class path
     *
     * @var string
     */
    private $path;

    /**
     * Application where the class will be used
     *
     * @var Application
     */
    private $application;

    /**
     * Return the class name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the class name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return the class path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the class path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Return the complete class path.
     * Complete class path is <applciation path>+<class path>
     *
     * @return string
     */
    public function getCompletePath()
    {
        return $this->getApplication()->getPath() . $this->getPath();
    }

    /**
     * Get the Class Definition Application
     * 
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the Class Definition Application
     *
     * @param Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    public function getCachedCode() {
        $classDefinitionClass = "Candango\\MyFuses\\Core\\ClassDefinition";
        $strOut = "\$class = new " . $classDefinitionClass . "();\n";
        $strOut .= "\$class->setName(\"" . $this->getName() . "\");\n";
        $strOut .= "\$class->setPath(\"" . addslashes($this->getPath()) .
            "\");\n";
        $strOut .= "\$application->addClass(\$class);\n";
        return $strOut;
    }
}
