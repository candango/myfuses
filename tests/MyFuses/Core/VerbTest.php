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

use Candango\MyFuses\Core\AbstractVerb;
use Candango\MyFuses\Core\BasicCircuit;

use PHPUnit\Framework\TestCase;


/**
 * VerbTest - VerbTest.php
 *
 * Tests case that covers the MyFuses class.
 *
 * PHP version 5
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
        $this->mockVerb = new CustomMockVerb();
    }

    public function testGetInstanceReturns()
    {
        $circuit = new BasicCircuit();
        $this->assertEquals(false, false);
    }
}



class CustomMockVerb extends AbstractVerb{

}

$mockVerbData = array(
    'name' => "customMock",
    'namespace' => "test",
    'attributes' => array(
        'value' => "Mock Test"
    )
);

/**
/home/fgarcia/source/candango/myfuses/src/MyFuses/Core/Verbs/DoVerb.php:125:
array (size=3)
'name' => string 'do' (length=2)
'namespace' => string 'myfuses' (length=7)
'attributes' =>
array (size=1)
'action' => string 'header' (length=6)
 */