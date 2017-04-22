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
     * Test's the factory() method.
     *
     * @return void
     */
    public function testFactory()
    {

        // load the configuration
        $configuration = ConfigurationFactory::factory(
            __DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'
        );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf(Configuration::class, $configuration);
    }
}
