<?php

/**
 * TechDivision\Import\Configuration\Jms\Parsers\JsonParser
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Parsers;

use TechDivision\Import\Configuration\Jms\Iterators\DirnameFilter;
use TechDivision\Import\Configuration\Jms\Iterators\FilenameFilter;
use TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface;
use TechDivision\Import\Configuration\Jms\ConfigurationParserInterface;

/**
 * The JSON configuration parser implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class JsonParser implements ConfigurationParserInterface
{

    /**
     * The key for the configuration snippet that contains the additional vendor directory configuration.
     *
     * @var string
     */
    const ADDITIONAL_VENDOR_DIRS = 'additional-vendor-dirs';

    /**
     * The key with the libraries of the additional vendor directory.
     *
     * @var string
     */
    const LIBRARIES = 'libraries';

    /**
     * The key with the absolut/relative path to the vendor directory.
     *
     * @var string
     */
    const VENDOR_DIR = 'vendor-dir';

    /**
     * The key for the flag to decide whether or not the vendor is relative to the installation directory.
     *
     * @var string
     */
    const RELATIVE = 'relative';

    /**
     * The utility class that provides array handling functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface
     */
    protected $arrayUtil;

    /** @var array  */
    private $configFiles = [];

    /**
     * Initializes the parser with the array utility instance.
     *
     * @param \TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface $arrayUtil The utility instance
     */
    public function __construct(ArrayUtilInterface $arrayUtil)
    {
        $this->arrayUtil = $arrayUtil;
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

        // process the additional vendor directories, if available
        $this->processAdditionalVendorDirs($main, $installationDir, $defaultConfigurationDir);

        // return the JSON encoded configuration
        return json_encode($main, JSON_PRETTY_PRINT);
    }

    /**
     * Process the passed directory and merges/replaces the found
     * configurations into the passed main configuration.
     *
     * @param array  $main      The main configuration to merge/replace the found configurations into
     * @param string $directory The directory to be parsed for addtional configuration files
     *
     * @return void
     * @throws \Exception Is thrown if the configuration can not be loaded from the configuration files
     */
    protected function process(array &$main, string $directory) : void
    {

        // load the configuration filenames
        $filenames = $this->listContents($directory, 'json');

        // load the content of each found configuration file and merge it
        foreach ($filenames as $filename) {
            $this->configFiles[] = $filename;
            if (is_file($filename) && $content = json_decode(file_get_contents($filename), true)) {
                $main = $this->replace($main, $content);
            } else {
                throw new \Exception(sprintf('Can\'t load content of file %s', $filename));
            }
        }
    }

    /**
     * Process the configuration files found in the additional vendor directories
     * and merge/replace its content in the also passed main configuration file.
     *
     * @param array  $main                    The main configuration to merge/replace the additional configuration files to
     * @param string $installationDir         The assumed Magento installation directory
     * @param string $defaultConfigurationDir The default configuration directory
     *
     * @return void
     */
    protected function processAdditionalVendorDirs(array &$main, string $installationDir, string $defaultConfigurationDir) : void
    {

        // query whether or not additional vendor directories has been registered in the configuration
        if (isset($main[JsonParser::ADDITIONAL_VENDOR_DIRS]) && is_array($main[JsonParser::ADDITIONAL_VENDOR_DIRS])) {
            // iterate over the additional vendor directory configurations
            foreach ($main[JsonParser::ADDITIONAL_VENDOR_DIRS] as $additionalVendorDir) {
                // make sure the vendor directory has been set
                if (isset($additionalVendorDir[JsonParser::VENDOR_DIR])) {
                    // extract the relative path to the additional vendor directory
                    $vendorDir = $additionalVendorDir[JsonParser::VENDOR_DIR];
                    // extract the flag if the additional vendor directory is relative to the installation directory
                    $isRelative = isset($additionalVendorDir[JsonParser::RELATIVE]) ?? true;
                    // query whether or not libraries have been configured
                    if (isset($additionalVendorDir[JsonParser::LIBRARIES]) && is_array($additionalVendorDir[JsonParser::LIBRARIES])) {
                        // process the configuration found in the library directories
                        foreach ($additionalVendorDir[JsonParser::LIBRARIES] as $library) {
                            // concatenate the directory for the library
                            $libDir = sprintf('%s/%s/%s', $vendorDir, $library, $defaultConfigurationDir);
                            // prepend the installation directory, if the vendor is relative to it
                            if ($isRelative) {
                                $libDir = sprintf('%s/%s', $installationDir, $libDir);
                            }

                            // create the canonicalized absolute pathname and try to load the configuration
                            if (is_dir($libraryDir = realpath($libDir))) {
                                $this->process($main, $libraryDir);
                            } else {
                                throw new \Exception(sprintf('Can\'t find find library directory "%s"', $libDir));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Return's the array utility instance.
     *
     * @return \TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface The utility instance
     */
    protected function getArrayUtil() : ArrayUtilInterface
    {
        return $this->arrayUtil;
    }

    /**
     * Replaces the values of the first array with the ones from the arrays
     * that has been passed as additional arguments.
     *
     * @param array ...$arrays The arrays with the values that has to be replaced
     *
     * @return array The array with the replaced values
     */
    protected function replace(...$arrays)
    {
        return $this->getArrayUtil()->replace(...$arrays);
    }

    /**
     * List the filenames of a directory.
     *
     * @param string $directory The directory to list
     * @param string $suffix    The suffix of the files to list
     *
     * @return array A list of filenames
     */
    protected function listContents($directory = '', $suffix = '.*')
    {

        // initialize the recursive directory iterator
        $directory = new \RecursiveDirectoryIterator($directory);
        $directory->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);

        // initialize the filters for file- and dirname
        $filter = new DirnameFilter($directory, '/^(?!\.Trash)/');
        $filter = new FilenameFilter($directory, sprintf('/\.(?:%s)$/', $suffix));

        // initialize the array for the files
        $files = array();

        // load the files
        foreach (new \RecursiveIteratorIterator($filter) as $file) {
            array_unshift($files, $file);
        }

        // sort the files ascending
        usort($files, function ($a, $b) {
            return strcmp($a, $b);
        });

        // return the array with the files
        return $files;
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
        return $this->configFiles;
    }
}
