<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2018 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .
    "../../src/MyFuses/Controller.php";

use PHPUnit\Framework\TestCase;
use Candango\MyFuses\Controller;

/**
 * MyFusesTest - ControllerTest.php
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
final class ControllerTest extends TestCase
{

    public function testGetInstanceReturns()
    {
        $this->assertInstanceOf(Controller::class, Controller::getInstance());
    }

    public function testRootUrlOnRootAndHttp()
    {
        $previousScriptName = $_SERVER['SCRIPT_NAME'];

        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/index.php";

        $expectedUrl = "http://localhost/";
        $this->assertEquals($expectedUrl, Controller::getRootUrl());

        unset($_SERVER['REQUEST_SCHEME']);
        unset($_SERVER['HTTP_HOST']);
        $_SERVER['SCRIPT_NAME'] = $previousScriptName;
    }

    public function testRootUrlOnRootAndHttps()
    {
        $previousScriptName = $_SERVER['SCRIPT_NAME'];

        $_SERVER['REQUEST_SCHEME'] = "https";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/index.php";

        $expectedUrl = "https://localhost/";
        $this->assertEquals($expectedUrl, Controller::getRootUrl());

        unset($_SERVER['REQUEST_SCHEME']);
        unset($_SERVER['HTTP_HOST']);
        $_SERVER['SCRIPT_NAME'] = $previousScriptName;
    }

    public function testRootUrlOnDirectoryAndHttp()
    {
        $previousScriptName = $_SERVER['SCRIPT_NAME'];

        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/directory/index.php";

        $expectedUrl = "http://localhost/directory/";
        $this->assertEquals($expectedUrl, Controller::getRootUrl());

        unset($_SERVER['REQUEST_SCHEME']);
        unset($_SERVER['HTTP_HOST']);
        $_SERVER['SCRIPT_NAME'] = $previousScriptName;
    }

    public function testRootUrlOnDirectoryAndHttps()
    {
        $previousScriptName = $_SERVER['SCRIPT_NAME'];

        $_SERVER['REQUEST_SCHEME'] = "https";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/directory/index.php";

        $expectedUrl = "https://localhost/directory/";
        $this->assertEquals($expectedUrl, Controller::getRootUrl());

        unset($_SERVER['REQUEST_SCHEME']);
        unset($_SERVER['HTTP_HOST']);
        $_SERVER['SCRIPT_NAME'] = $previousScriptName;
    }

    public function testRootUrlOnMultiDirectoryAndHttp()
    {
        $previousScriptName = $_SERVER['SCRIPT_NAME'];

        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/directory/directory1/directory2/index.php";

        $expectedUrl = "http://localhost/directory/directory1/directory2/";
        $this->assertEquals($expectedUrl, Controller::getRootUrl());

        unset($_SERVER['REQUEST_SCHEME']);
        unset($_SERVER['HTTP_HOST']);
        $_SERVER['SCRIPT_NAME'] = $previousScriptName;
    }

    public function testRootUrlOnMultiDirectoryAndHttps()
    {
        $previousScriptName = $_SERVER['SCRIPT_NAME'];

        $_SERVER['REQUEST_SCHEME'] = "https";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/directory/directory1/directory2/index.php";

        $expectedUrl = "https://localhost/directory/directory1/directory2/";
        $this->assertEquals($expectedUrl, Controller::getRootUrl());

        unset($_SERVER['REQUEST_SCHEME']);
        unset($_SERVER['HTTP_HOST']);
        $_SERVER['SCRIPT_NAME'] = $previousScriptName;
    }

    public function testProtocolRequestSchemeHttp()
    {
        $expectedProtocol = "http";
        $_SERVER['REQUEST_SCHEME'] = "http";
        $this->assertEquals($expectedProtocol, Controller::getProtocol());
        unset($_SERVER['REQUEST_SCHEME']);
    }

    public function testProtocolRequestSchemeHttps()
    {
        $expectedProtocol = "https";
        $_SERVER['REQUEST_SCHEME'] = "https";
        $this->assertEquals($expectedProtocol, Controller::getProtocol());
        unset($_SERVER['REQUEST_SCHEME']);
    }

    public function testProtocolNoRequestScheme()
    {
        $expectedProtocol = "http";
        $this->assertEquals($expectedProtocol, Controller::getProtocol());
    }
}
