<?php

/**
 * TechDivision\Import\Configuration\DefaultLibraries
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Configuration;

use JMS\Serializer\Annotation\Type;
use TechDivision\Import\Configuration\DefaultLibrariesConfigurationInterface;

/**
 * The default libraries configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class DefaultLibraries implements DefaultLibrariesConfigurationInterface
{

    /**
     * The array with the paths to the default libraries.
     *
     * @var array
     * @Type("array")
     */
    protected $libraries = array();

    /**
     * Return's an array with the path to the default libraries.
     *
     * @return array The paths to the default libraries
     */
    public function getLibraries()
    {
        return $this->libraries;
    }
}
