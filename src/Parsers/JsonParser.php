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
     * @param array  $directories An array with diretories to parse
     * @param string $format      The format of the configuration data, either one of json, yaml or xml
     *
     * @return void
     */
    public function parse(array $directories)
    {

        $main = array();

        foreach ($directories as $directory) {

            $directory = new \RecursiveDirectoryIterator($directory);
            $iterator = new \RecursiveIteratorIterator($directory);
            $regex = new \RegexIterator($iterator, '/^.+\.json$/i', \RecursiveRegexIterator::GET_MATCH);

            $filenames = array_keys(iterator_to_array($regex));

            foreach ($filenames as $filename) {

                if ($content = json_decode(file_get_contents($filename), true)) {
                    $main = array_merge_recursive($main, $content);
                } else {
                    error_log(sprintf('Can\'t load content of file %s', $filename));
                }
            }
        }

        return json_encode($main, JSON_PRETTY_PRINT);
    }
}
