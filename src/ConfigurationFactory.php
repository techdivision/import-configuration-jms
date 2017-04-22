<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationFactory
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

use JMS\Serializer\SerializerBuilder;

/**
 * The configuration factory implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConfigurationFactory
{

    /**
     * Factory implementation to create a new initialized configuration instance.
     *
     * @param string $filename The configuration filename
     * @param string $type     The format of the configuration file, either one of json, yaml or xml
     *
     * @return \TechDivision\Import\Configuration\Jms\Configuration The configuration instance
     * @throws \Exception Is thrown, if the specified configuration file doesn't exist
     */
    public static function factory($filename, $type = 'json')
    {

        // load the JSON data
        if (!$jsonData = file_get_contents($filename)) {
            throw new \Exception(sprintf('Can\'t load configuration file %s', $filename));
        }

        // initialize the JMS serializer, load and return the configuration
        return SerializerBuilder::create()
            ->build()
            ->deserialize($jsonData, Configuration::class, $type);
    }
}
