<?php

/**
 * TechDivision\Import\Configuration\Jms\Parsers\JsonParser
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
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Parsers;

use TechDivision\Import\Configuration\Jms\ConfigurationParserInterface;

/**
 * The JSON configuration parser implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class JsonParser implements ConfigurationParserInterface
{

    /**
     * Parsing the configuration and merge it recursively.
     *
     * @param array $directories An array with diretories to parse
     *
     * @return void
     * @throws \Exception Is thrown if the configuration can not be loaded from the configuration files
     */
    public function parse(array $directories)
    {

        // initialize the array that'll contain the configuration structure
        $main = array();

        // iterate over the found directories to parse them for configuration files
        foreach ($directories as $directory) {
            // create an iterator to recursively parse through the directories
            $directory = new \RecursiveDirectoryIterator($directory);
            $iterator = new \RecursiveIteratorIterator($directory);
            $regex = new \RegexIterator($iterator, '/^.+\.json$/i', \RecursiveRegexIterator::GET_MATCH);

            // load the content of each found configuration file and merge it
            foreach ($regex as $filenames) {
                foreach ($filenames as $filename) {
                    if ($content = json_decode(file_get_contents($filename), true)) {
                        $main = array_merge_recursive($main, $content);
                    } else {
                        throw new \Exception(sprintf('Can\'t load content of file %s', $filename));
                    }
                }
            }
        }

        // return the JSON encoded configuration
        return json_encode($main, JSON_PRETTY_PRINT);
    }
}
