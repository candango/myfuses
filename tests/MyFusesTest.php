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

use PHPUnit\Framework\TestCase;

/**
 * MyFusesTest - MyFusesTest.php
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
final class MyFusesTest extends TestCase
{

    public function testGetInstanceReturns()
    {
        $this->assertInstanceOf(MyFuses::class, MyFuses::getInstance());
    }

    public function testRootUrlOnRootAndHttp()
    {
        $previousScriptName = $_SERVER['SCRIPT_NAME'];

        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/index.php";

        $expectedUrl = "http://localhost/";
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());

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
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());

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
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());

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
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());

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
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());

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
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());

        unset($_SERVER['REQUEST_SCHEME']);
        unset($_SERVER['HTTP_HOST']);
        $_SERVER['SCRIPT_NAME'] = $previousScriptName;
    }

    public function testProtocolRequestSchemeHttp()
    {
        $expectedProtocol = "http";
        $_SERVER['REQUEST_SCHEME'] = "http";
        $this->assertEquals($expectedProtocol, MyFuses::getProcotol());
        unset($_SERVER['REQUEST_SCHEME']);
    }

    public function testProtocolRequestSchemeHttps()
    {
        $expectedProtocol = "https";
        $_SERVER['REQUEST_SCHEME'] = "https";
        $this->assertEquals($expectedProtocol, MyFuses::getProcotol());
        unset($_SERVER['REQUEST_SCHEME']);
    }

    public function testProtocolNoRequestScheme()
    {
        $expectedProtocol = "http";
        $this->assertEquals($expectedProtocol, MyFuses::getProcotol());
    }
}
