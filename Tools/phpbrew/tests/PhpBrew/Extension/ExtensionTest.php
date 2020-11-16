<?php

namespace PhpBrew\Tests\Extension;

use PhpBrew\Extension\ExtensionFactory;
use PhpBrew\Testing\VCRAdapter;
use PHPUnit\Framework\TestCase;

/**
 * ExtensionTest
 *
 * @large
 * @group extension
 */
class ExtensionTest extends TestCase
{
    public function setUp()
    {
        VCRAdapter::enableVCR($this);
    }

    public function tearDown()
    {
        VCRAdapter::disableVCR();
    }

    /**
     * We use getenv to get the path of extension directory because in data provider method
     * the path member is not setup yet.
     */
    public function testXdebug()
    {
        $ext = ExtensionFactory::lookup('xdebug', array(getenv('PHPBREW_EXTENSION_DIR')));
        $this->assertInstanceOf('PhpBrew\Extension\Extension', $ext);
        $this->assertInstanceOf('PhpBrew\Extension\PeclExtension', $ext);
        $this->assertEquals('xdebug', $ext->getName());
        $this->assertEquals('xdebug', $ext->getExtensionName());
        $this->assertEquals('xdebug.so', $ext->getSharedLibraryName());
        $this->assertTrue($ext->isZend());
    }

    public function testOpcache()
    {
        $ext = ExtensionFactory::lookup('opcache', array(getenv('PHPBREW_EXTENSION_DIR')));
        $this->assertInstanceOf('PhpBrew\Extension\Extension', $ext);
        $this->assertInstanceOf('PhpBrew\Extension\M4Extension', $ext);
        $this->assertEquals('opcache', $ext->getName());
        $this->assertEquals('opcache', $ext->getExtensionName());
        $this->assertEquals('opcache.so', $ext->getSharedLibraryName());
        $this->assertTrue($ext->isZend());
    }

    public function testOpenSSL()
    {
        $ext = ExtensionFactory::lookup('openssl', array(getenv('PHPBREW_EXTENSION_DIR')));
        $this->assertInstanceOf('PhpBrew\Extension\Extension', $ext);
        $this->assertInstanceOf('PhpBrew\Extension\M4Extension', $ext);
        $this->assertEquals('openssl', $ext->getName());
        $this->assertEquals('openssl', $ext->getExtensionName());
        $this->assertEquals('openssl.so', $ext->getSharedLibraryName());
        $this->assertFalse($ext->isZend());
    }

    public function testSoap()
    {
        $ext = ExtensionFactory::lookup('soap', array(getenv('PHPBREW_EXTENSION_DIR')));
        $this->assertInstanceOf('PhpBrew\Extension\Extension', $ext);
        $this->assertInstanceOf('PhpBrew\Extension\PeclExtension', $ext);
        $this->assertEquals('soap', $ext->getName());
        $this->assertEquals('soap', $ext->getExtensionName());
        $this->assertEquals('soap.so', $ext->getSharedLibraryName());
        $this->assertFalse($ext->isZend());
    }

    public function testSplTypes()
    {
        $ext = ExtensionFactory::lookup('SPL_Types', array(getenv('PHPBREW_EXTENSION_DIR')));
        $this->assertInstanceOf('PhpBrew\Extension\Extension', $ext);
        $this->assertInstanceOf('PhpBrew\Extension\PeclExtension', $ext);
        $this->assertEquals('SPL_Types', $ext->getName());
        $this->assertEquals('spl_types', $ext->getExtensionName());
        $this->assertEquals('spl_types.so', $ext->getSharedLibraryName());
        $this->assertFalse($ext->isZend());
    }

    public function testXhprof()
    {
        $ext = ExtensionFactory::lookup('xhprof', array(getenv('PHPBREW_EXTENSION_DIR')));
        $this->assertInstanceOf('PhpBrew\Extension\Extension', $ext);
        $this->assertInstanceOf('PhpBrew\Extension\PeclExtension', $ext);
        $this->assertEquals('xhprof', $ext->getName());
        $this->assertEquals('xhprof', $ext->getExtensionName());
        $this->assertEquals('xhprof.so', $ext->getSharedLibraryName());
        $this->assertFalse($ext->isZend());
    }

    public function extensionNameProvider()
    {
        $extNames = scandir(getenv('PHPBREW_EXTENSION_DIR'));
        $data = array();

        foreach ($extNames as $extName) {
            if ($extName == "." || $extName == "..") {
                continue;
            }
            $data[] = array($extName);
        }
        return $data;
    }


    /**
     * @dataProvider extensionNameProvider
     */
    public function testGenericExtensionMetaInformation($extName)
    {
        $ext = ExtensionFactory::lookup($extName, array(getenv('PHPBREW_EXTENSION_DIR')));
        $this->assertInstanceOf('PhpBrew\Extension\Extension', $ext);
        $this->assertNotEmpty($ext->getName());
    }
}
