<?php

/**
 * TechDivision\Import\Configuration\Jms\Parsers\JsonParser
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Parsers;

use TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface;
use TechDivision\Import\Loaders\LoaderInterface;

/**
 * The JSON configuration parser implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ExtendedJsonParser extends JsonParser
{

    /**
     * The key for the configuration snippet that contains the extension libary configuration.
     *
     * @var string
     */
    const EXTENSION_LIBRARIES = 'extension-libraries';

    /**
     * The absolute path to the vendor directory
     *
     * @var string
     */
    protected $vendorDir;

    /**
     * Initializes the parser with the array utility instance.
     *
     * @param \TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface $arrayUtil       The utility instance
     * @param \TechDivision\Import\Loaders\LoaderInterface                    $vendorDirLoader The loader for the absolute vendor directory
     */
    public function __construct(ArrayUtilInterface $arrayUtil, LoaderInterface $vendorDirLoader)
    {

        // pass the array utility to the parent constructor
        parent::__construct($arrayUtil);

        // initialize the absolute path to the vendor directory
        $this->vendorDir = $vendorDirLoader->load();
    }

    /**
     * Parsing the configuration and merge it recursively.
     *
     * @param string $installationDir         The assumed Magento installation directory
     * @param string $defaultConfigurationDir The default configuration directory
     * @param array  $directories             An array with diretories to parse
     *
     * @return string The parsed configuration as string
     */
    public function parse(string $installationDir, string $defaultConfigurationDir, array $directories) : string
    {

        // initialize the array that'll contain the configuration structure
        $main = array();

        // iterate over the found directories to parse them for configuration files
        foreach ($directories as $directory) {
            $this->process($main, $directory);
        }

        // process the configuration of the extension libaries, if available
        $this->processExtensionLibraries($main, $defaultConfigurationDir);

        // process the additional vendor directories, if available
        $this->processAdditionalVendorDirs($main, $installationDir, $defaultConfigurationDir);

        // return the JSON encoded configuration
        return json_encode($main, JSON_PRETTY_PRINT);
    }

    /**
     * iterate over the found configurations files path
     *
     * @param array $directories The etc directories
     *
     * @return array
     */
    public function getConfigurationFiles(array $directories): array
    {
        return parent::getConfigurationFiles($directories);
    }

    /**
     * Process the configuration files found in the extension libary directories
     * and merge/replace its content in the also passed main configuration file.
     *
     * @param array  $main                    The main configuration to merge/replace the extension libary files to
     * @param string $defaultConfigurationDir The default configuration directory
     *
     * @return void
     */
    protected function processExtensionLibraries(array &$main, string $defaultConfigurationDir) : void
    {

        // query whether or not extension libary directories has been registered in the configuration
        if (isset($main[ExtendedJsonParser::EXTENSION_LIBRARIES]) && is_array($main[ExtendedJsonParser::EXTENSION_LIBRARIES])) {
            // iterate over the extension library directory configurations
            foreach ($main[ExtendedJsonParser::EXTENSION_LIBRARIES] as $extensionLibrary) {
                // prepare the absolute path to the extension library
                $libDir = sprintf('%s/%s/%s', $this->vendorDir, $extensionLibrary, $defaultConfigurationDir);

                // create the canonicalized absolute pathname and try to load the configuration
                if (is_dir($libraryDir = realpath($libDir))) {
                    $this->process($main, $libraryDir);
                }
            }
        }
    }
}
