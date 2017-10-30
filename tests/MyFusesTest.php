<?php
use PHPUnit\Framework\TestCase;

/**
 * @corvers MyFuses
 */
final class MyFusesTest extends TestCase
{
    public function testGetInstanceReturns()
    {
        $this->assertInstanceOf(MyFuses::class, MyFuses::getInstance());
    }

    public function testRootUrlOnRootAndHttp()
    {
        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/index.php";
        $expectedUrl = "http://localhost/";
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());
    }

    public function testRootUrlOnRootAndHttps()
    {
        $_SERVER['REQUEST_SCHEME'] = "https";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/index.php";
        $expectedUrl = "https://localhost/";
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());
    }

    public function testRootUrlOnDirectoryAndHttp()
    {
        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/directory/index.php";
        $expectedUrl = "http://localhost/directory/";
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());
    }

    public function testRootUrlOnDirectoryAndHttps()
    {
        $_SERVER['REQUEST_SCHEME'] = "https";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/directory/index.php";
        $expectedUrl = "https://localhost/directory/";
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());
    }

    public function testRootUrlOnMultiDirectoryAndHttp()
    {
        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/directory/directory1/directory2/index.php";
        $expectedUrl = "http://localhost/directory/directory1/directory2/";
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());
    }

    public function testRootUrlOnMultiDirectoryAndHttps()
    {
        $_SERVER['REQUEST_SCHEME'] = "https";
        $_SERVER['HTTP_HOST'] = "localhost";
        $_SERVER['SCRIPT_NAME'] = "/directory/directory1/directory2/index.php";
        $expectedUrl = "https://localhost/directory/directory1/directory2/";
        $this->assertEquals($expectedUrl, MyFuses::getRootUrl());
    }

    public function testProtocolRequestSchemeHttp()
    {
        $expectedProtocol = "http";
        $_SERVER['REQUEST_SCHEME'] = "http";
        $this->assertEquals($expectedProtocol, MyFuses::getProcotol());
    }

    public function testProtocolRequestSchemeHttps()
    {
        $expectedProtocol = "https";
        $_SERVER['REQUEST_SCHEME'] = "https";
        $this->assertEquals($expectedProtocol, MyFuses::getProcotol());
    }

    public function testProtocolNoRequestScheme()
    {
        $expectedProtocol = "http";
        $this->assertEquals($expectedProtocol, MyFuses::getProcotol());
    }
}
