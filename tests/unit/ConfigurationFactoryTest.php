<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationFactoryTest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms;

/**
 * Test class for the JMS configuration factory.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConfigurationFactoryTest extends \PHPUnit_Framework_TestCase
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
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {

        // intialize the vendor directory
        $vendorDirectory = 'vendor';

        // the path of the JMS serializer directory, relative to the vendor directory
        $jmsDirectory = DIRECTORY_SEPARATOR . 'jms' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'src';

        // try to find the path to the JMS Serializer annotations
        if (!file_exists($annotationDirectory = $vendorDirectory . DIRECTORY_SEPARATOR . $jmsDirectory)) {
            // stop processing, if the JMS annotations can't be found
            throw new \Exception(
                sprintf(
                    'The jms/serializer libarary can not be found in %s',
                    $vendorDirectory
                )
            );
        }

        // register the autoloader for the JMS serializer annotations
        \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            $annotationDirectory
        );

        // initialize the configuration factory instance we want to test
        $this->configurationFactory = new ConfigurationFactory();
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
        $this->assertInstanceOf('TechDivision\Import\ConfigurationInterface', $configuration);
    }
}
