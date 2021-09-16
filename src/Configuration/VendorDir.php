<?php

/**
 * TechDivision\Import\Configuration\VendorDir
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
use JMS\Serializer\Annotation\SerializedName;
use TechDivision\Import\Configuration\VendorDirConfigurationInterface;

/**
 * The vendor dir configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class VendorDir implements VendorDirConfigurationInterface
{

    /**
     * The path to the additional vendor directory.
     *
     * @var string
     * @Type("string")
     * @SerializedName("vendor-dir")
     */
    protected $vendorDir;

    /**
     * The array with the paths to the extension libraries.
     *
     * @var array
     * @Type("array")
     */
    protected $libraries = array();

    /**
     * Whether or the vendor directory is relative to the installation directory.
     *
     * @var boolean
     * @Type("boolean")
     */
    protected $relative = true;

    /**
     * Return's the path to the additional vendor directory.
     *
     * @return string The path to the additional vendor directory
     */
    public function getVendorDir()
    {
        return $this->vendorDir;
    }

    /**
     * Return's an array with the path to additional extension libraries.
     *
     * @return array The paths to additional extension libraries
     */
    public function getLibraries()
    {
        return $this->libraries;
    }

    /**
     * Query's whether or not the vendor directory is relative to the installation directory.
     *
     * @return boolean TRUE if the vendor dir is relative to the installation directory, else FALSE
     */
    public function isRelative()
    {
        return $this->relative;
    }
}
