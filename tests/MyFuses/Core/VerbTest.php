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

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .
    "../../../src/MyFuses/Controller.php";

use Candango\MyFuses\Controller;
use Candango\MyFuses\Core\AbstractVerb;
use Candango\MyFuses\Core\BasicApplication;
use Candango\MyFuses\Core\BasicCircuit;
use Candango\MyFuses\Core\FuseAction;

use Candango\MyFuses\Core\Verbs\DoVerb;
use Candango\MyFuses\Core\Verbs\IncludeVerb;

use PHPUnit\Framework\TestCase;


class TestCustomMockVerb extends AbstractVerb{

}

/**
 * VerbTest - VerbTest.php
 *
 * Tests case that covers the MyFuses class.
 *
 * @category   tests
 * @package    myfuses.tests
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      3b84558078c197418cfe757efde0d19b0806d45a
 * @covers     MyFuses
 */
final class VerbTest extends TestCase
{

    private $mockVerb;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $controller = Controller::getInstance();

        $this->application = new BasicApplication();
        $this->application->setController($controller);

        $circuit = new BasicCircuit();
        $circuit->setName("test");
        $this->application->addCircuit($circuit);

        $action = new FuseAction($circuit);
        $action->setName("test");
        $circuit->addAction($action);

        $verbPaths = array(
            "test" => __DIR__
        );
        $circuit->setVerbPaths(serialize($verbPaths));

    }

    public function testGetDoVerbGetInstanceClass(){
        $mockDoVerbData = array(
            'name' => "do",
            'namespace' => "myfuses",
            'attributes' => array(
                'action' => "test"
            )
        );
        $verb = AbstractVerb::getInstance($mockDoVerbData,
            $this->getTestAction());

        $this->assertInstanceOf(DoVerb::class, $verb);
    }

    public function testGetIncludeVerbGetInstanceClass(){
        $mockDoVerbData = array(
            'name' => "include",
            'namespace' => "myfuses",
            'attributes' => array(
                'file' => "test"
            )
        );
        $verb = AbstractVerb::getInstance($mockDoVerbData,
            $this->getTestAction());

        $this->assertInstanceOf(IncludeVerb::class, $verb);
    }

    public function testGetCustomVerbGetInstanceClass()
    {
        $mockCustomVerbData = array(
            'name' => "customMock",
            'namespace' => "test",
            'attributes' => array(
                'value' => "Mock Test"
            )
        );

        $verb = AbstractVerb::getInstance($mockCustomVerbData,
            $this->getTestAction());

        $this->assertInstanceOf(TestCustomMockVerb::class, $verb);
    }

    /**
     * Returns the test circuit
     *
     * @return BasicCircuit
     */
    private function getTestCircuit(){
        return $this->application->getCircuit("test");
    }

    /**
     * Returns the test action
     *
     * @return FuseAction
     */
    private function getTestAction(){
        return $this->getTestCircuit()->getAction("test");

    }
}
