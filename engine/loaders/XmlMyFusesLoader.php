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

require_once MyFuses::MYFUSES_ROOT_PATH . "core/AbstractPlugin.php";
require_once MyFuses::MYFUSES_ROOT_PATH . "core/AbstractExceptionPlugin.php";
require_once MyFuses::MYFUSES_ROOT_PATH . "core/AbstractVerb.php";
require_once MyFuses::MYFUSES_ROOT_PATH . "core/Application.php";
require_once MyFuses::MYFUSES_ROOT_PATH . "core/ClassDefinition.php";
require_once MyFuses::MYFUSES_ROOT_PATH . "core/BasicCircuit.php";
require_once MyFuses::MYFUSES_ROOT_PATH . "core/FuseAction.php";
require_once MyFuses::MYFUSES_ROOT_PATH . "engine/AbstractMyFusesLoader.class.php";

/**
 * AbstractMyFusesLoader - AbstractMyFusesLoader.php
 *
 * XML MyFuses loader. Loads myfuses.xml, fusebox.xml and circuit.xml files
 * in order to provide the data used to config, build and execute the
 * application.
 *
 * @category   controller
 * @package    myfuses.engine
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      4ea81cee237c94b5349825934ecad7e2675c7355
 */
class XmlMyFusesLoader extends AbstractMyFusesLoader
{
    /**
     * My Fuses application file constant
     * 
     * @var string
     * @static
     */
    const MYFUSES_APP_FILE = "myfuses.xml";

    /**
     * My Fuses php application file constant
     * 
     * @var string
     * @static 
     */
    const MYFUSES_PHP_APP_FILE = "myfuses.xml.php";

    const CIRCUIT_FILE = "circuit.xml";

    const CIRCUIT_PHP_FILE = "circuit.xml.php";

    /**
     * Enter description here...
     *
     * @return array
     */
    public function getApplicationData()
    {
        $this->chooseApplicationFile();

        $rootNode = $this->loadApplicationFile();

        $data = self::getDataFromXml("myfuses", $rootNode);
        $data['file'] = $this->getApplication()->getFile();
        return $data;
    }

	/**
     * Find the file that the given application is using
     * TODO Throw some exception here!!!
     *
     * @return boolean
     */
    private function chooseApplicationFile()
    {
        if (is_file($this->getApplication()->getPath() .
            self::MYFUSES_APP_FILE)) {
            $this->getApplication()->setFile(self::MYFUSES_APP_FILE);
            return true;
        }

        if (is_file( $this->getApplication()->getPath() .
            self::MYFUSES_PHP_APP_FILE)) {
            $this->getApplication()->setFile(self::MYFUSES_PHP_APP_FILE);
            return true;
        }

        return false;
    }

    public function applicationWasModified()
    {
        $this->chooseApplicationFile();
        // FIXME: This decision should be taken by the application
        if(filemtime($this->getApplication()->getCompleteFile()) >
            $this->getApplication()->getLastLoadTime()) {
            return true;
        }
        return false;
    }

    public function circuitWasModified($name)
    {
        $data = $this->getCachedApplicationData();

        if (!isset($data['circuits']['name'])) {
            return false;
        }

        $file = $this->getApplication()->getPath() .
            $data['circuits'][$name]['attributes']['path'] .
            $data['circuits'][ $name][ 'attributes' ]['file'];

        if (filemtime($file) >
            $data['circuits'][$name]['attributes']['lastloadtime']) {
            return true;
        }

        return false;
    }

    // TODO Throw some exception here!!!
    private function chooseCircuitFile(Circuit $circuit)
    {
        $circuitPath = $circuit->getApplication()->getPath() .
            $circuit->getPath();

        if (is_file($circuitPath . self::CIRCUIT_FILE)) {
            $circuit->setFile(self::CIRCUIT_FILE);
            return true;
        }

        if (is_file($circuitPath . self::CIRCUIT_PHP_FILE)) {
            $circuit->setFile(self::CIRCUIT_PHP_FILE);
            return true;
        }

        return false;
    }

    /**
     * Load the application file
     */
    private function loadApplicationFile()
    {
        // TODO: Not being used. Check if we should remove it
        $appMethods = array(
            'circuits' => "loadCircuits",
            'classes' => "loadClasses",
            'parameters' => "loadParameters"
        );

        // TODO verify if all conditions is satisfied for a file load occurs
        if (@!$fp = fopen($this->getApplication()-> getCompleteFile() , "r")) {
            throw new MyFusesFileOperationException(
                $this->getApplication()->getCompleteFile(), 
                MyFusesFileOperationException::OPEN_FILE);
        }

        if (!flock($fp, LOCK_SH)) {
            throw new MyFusesFileOperationException(
                $this->getApplication()->getCompleteFile(),
                MyFusesFileOperationException::LOCK_FILE);
        }

        MyFuses::getInstance()->getDebugger()->registerEvent(
            new MyFusesDebugEvent(MyFusesDebugger::MYFUSES_CATEGORY,
                "Getting Application file \"" .
                $this->getApplication()->getCompleteFile() . "\""));

        $fileCode = fread($fp, filesize($this->getApplication()->
            getCompleteFile()));

        try {
            // FIXME put no warning modifier in SimpleXMLElement call
            $rootNode = @new SimpleXMLElement($fileCode);
        } catch (Exception $e) {
            // FIXME handle error
            echo "<b>" . $this->getApplication()->getCompleteFile() .
                "<b><br>";
            die($e->getMessage());
        }

        return $rootNode;
    }

    /**
     * Returns the circuit data from it's xml definition
     *
     * @param Circuit $circuit
     * @return array
     */
    public function getCircuitData(Circuit $circuit)
    {
        $this->chooseCircuitFile($circuit);
        
        $rootNode = $this->loadCircuitFile($circuit);

        return self::getDataFromXml("circuit", $rootNode);
    }

    /**
     * Load a circuit file
     * 
     * @param Circuit $circuit
     * @return SimpleXMLElement
     * @throws MyFusesFileOperationException
     */
    private function loadCircuitFile(Circuit $circuit)
    {
        $circuitFile = $circuit->getCompleteFile();

        // TODO verify if all conditions is satisfied for a file load occurs
        if (@!$fp = fopen($circuitFile ,"r")) {
            throw new MyFusesFileOperationException($circuitFile,
                MyFusesFileOperationException::OPEN_FILE);
        }
        // FIXME Fixing an error occurred with CGI GATEWAYS.
        // FIXME Suppressing redirect with CGI!!!
        if (!isset($_SERVER["GATEWAY_INTERFACE"])) {
            if (!flock($fp, LOCK_SH)) {
                throw new MyFusesFileOperationException(
                    $circuitFile, MyFusesFileOperationException::LOCK_FILE);
            }
        }

        $fileCode = fread($fp, filesize($circuitFile));

        try {
            // FIXME put no warning modifier in SimpleXMLElement call
            @$rootNode = new SimpleXMLElement($fileCode);
        } catch (Exception $e) {
            // FIXME handle error
            echo "<b>" . $circuitFile . "<b><br>";
            die($e->getMessage());
        }

        return $rootNode;
    }

    public static function getDataFromXML($name, SimpleXMLElement $node)
    {
        $nameX = explode("_ns_", $name);

        if (count($nameX) > 1) {
            $data['name'] = $nameX[1];
            $data['namespace'] = $nameX[0];
        } else {
            $data['name'] = $name;
            $data['namespace'] = "myfuses";
        }

        if (count($node->getDocNamespaces(true))) {
            $data['docNamespaces'] = $node->getDocNamespaces(true);

            foreach ($data['docNamespaces'] as $namespace => $value) {
                foreach ($node->attributes($namespace, true) as
                    $name => $attribute) {
                    $data['namespaceattributes'][$namespace][$name] =
                        "" . $attribute;
                }
            }
        }

        foreach ($node->attributes() as $key => $attribute) {
            $data['attributes'][$key] = "" . $attribute;
        }

        if (count($node->children())) {
            foreach ($node->children() as $key => $child) {
                // PoG StYlEzZz
                $xml = preg_replace(
                    "@([<|</])(\w+|\d+):(\w+|\d+)( |)@", "$1$2_ns_$3$4", 
                    $child->asXML());
                $xml = preg_replace(
                    "@(\w+|\d+):(\w+|\d+)([=])@", "$1_ns_$2$3", $xml);
                $child = new SimpleXMLElement(preg_replace(
                    "@([<|</])(\w+|\d+):(\w+|\d+)( |)@", "$1$2_ns_$3$4", 
                    $xml));
                $data['children'][] = self::getDataFromXML($key, $child);
            }
        }

        return $data;
    }
}
