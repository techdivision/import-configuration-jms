<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationParserInterface
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms;

/**
 * Interface for configuration parser implementations.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
interface ConfigurationParserInterface
{

    /**
     * Parsing the configuration and merge it recursively.
     *
     * @param string $installationDir         The assumed Magento installation directory
     * @param string $defaultConfigurationDir The default configuration directory
     * @param array  $directories             An array with diretories to parse
     *
     * @return string The parsed configuration as string
     */
    public function parse(string $installationDir, string $defaultConfigurationDir, array $directories) : string;

    /**
     * @param array $directories The etc directories
     *
     * @return array
     */
    public function getConfigurationFiles(array $directories) : array;
}
