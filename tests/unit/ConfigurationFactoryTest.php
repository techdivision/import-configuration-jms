<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationFactoryTest
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms;

use PHPUnit\Framework\TestCase;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TechDivision\Import\Utils\DependencyInjectionKeys;

/**
 * Test class for the JMS configuration factory.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConfigurationFactoryTest extends TestCase
{

    /**
     * The configuration factory instance.
     *
     * @var \TechDivision\Import\Configuration\Jms\ConfigurationFactory
     */
    protected $configurationFactory;

    /**
     *
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {

        // create a mock configuration parser factory
        $mockConfigurationParserFactory = $this->getMockBuilder(ConfigurationParserFactory::class)
            ->disableOriginalConstructor()
            ->getMock();


        // initialize the vendor directory
        if (file_exists('vendor/autoload.php')) {
            $vendorDir = dirname('vendor/autoload.php');
        } elseif (file_exists(__DIR__  . '/../../vendor/autoload.php')) {
            $vendorDir = dirname(__DIR__ . '/../../vendor/autoload.php');
        } else {
            $this->fail('Can\'t find vendor directory to initialize annotation autoloader');
        }

        // mock the DI container
        $mockContainer = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();
        $mockContainer->expects($this->any())
            ->method('getParameter')
            ->with(DependencyInjectionKeys::CONFIGURATION_VENDOR_DIR)
            ->willReturn($vendorDir);

        // create a new serializer builder instance
        $configurationBuilder = new SerializerBuilder();

        // initialize the configuration factory instance we want to test
        $this->configurationFactory = new ConfigurationFactory($mockContainer, $mockConfigurationParserFactory, $configurationBuilder);
    }

    /**
     * Test's the factory() method.
     *
     * @return void
     */
    public function testFactory()
    {

        // load the configuration
        $configuration = $this->configurationFactory->factory(
            __DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'
        );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf('TechDivision\Import\Configuration\ConfigurationInterface', $configuration);
    }

    /**
     * Test's the factoryFromString() method with simple params.
     *
     * @return void
     */
    public function testFactoryFromStringWithSimpleParamsOption()
    {

        // load the configuration
        $configuration = $this->configurationFactory->factoryFromString(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'),
            'json',
            '{"params":{"test":"test-01"}}'
        );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf('TechDivision\Import\Configuration\ConfigurationInterface', $configuration);
        $this->assertSame('test-01', $configuration->getParam('test'));
    }

    /**
     * Test's the factoryFromString() method with complex params.
     *
     * @return void
     */
    public function testFactoryFromStringWithComplexParamsOption()
    {

        // load the configuration
        $configuration = $this->configurationFactory->factoryFromString(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'),
            'json',
            '{"params":{"test":["test-01","test-02"]}}'
        );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf('TechDivision\Import\Configuration\ConfigurationInterface', $configuration);
        $this->assertSame(array('test-01', 'test-02'), $configuration->getParam('test'));
    }

    /**
     * Test's the factoryFromString() method with merged complex params.
     *
     * @return void
     */
    public function testFactoryFromStringWithMergedComplexParamsOption()
    {

        // load the configuration
        $configuration = $this->configurationFactory->factoryFromString(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'),
            'json',
            '{"params":{"test-array":["test-01","test-02"]}}'
            );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf('TechDivision\Import\Configuration\ConfigurationInterface', $configuration);
        $this->assertSame(array('test-01', 'test-02'), $configuration->getParam('test-array'));
    }
}
