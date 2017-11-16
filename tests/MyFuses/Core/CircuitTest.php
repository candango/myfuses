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
 * CircuitTest - CircuitTest.php
 *
 * Tests case that covers the MyFuses class.
 *
 * @category   tests
 * @package    myfuses.tests
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      3b84558078c197418cfe757efde0d19b0806d45a
 * @covers     MyFuses
 */
final class CircuitTest extends TestCase
{

    public function testDefaults()
    {
        $circuit = new BasicCircuit();
        $this->assertFalse($circuit->wasBuilt());
        $this->assertFalse($circuit->isModified());
        $this->assertFalse($circuit->isLoaded());
        $this->assertEquals("optimistic", $circuit->getSecurity());
    }
}
