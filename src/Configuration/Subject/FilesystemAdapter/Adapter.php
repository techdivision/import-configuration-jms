<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Subject\FilesystemAdapter\Adapter
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Configuration\Subject\FilesystemAdapter;

use JMS\Serializer\Annotation\Type;
use TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait;
use TechDivision\Import\Configuration\Subject\FilesystemAdapter\AdapterConfigurationInterface;

/**
 * The import adapter's configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Adapter implements AdapterConfigurationInterface
{

    /**
     * The trait that provides parameter handling functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * The import adapter's unique DI identifier.
     *
     * @var string
     * @Type("string")
     */
    protected $type = 'League\Flysystem\Adapter\Local';

    /**
     * Return's the adapter's class name
     *
     * @return string The adapter's class name
     */
    public function getType()
    {
        return $this->type;
    }
}
