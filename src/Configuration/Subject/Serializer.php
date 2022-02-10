<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Subject\Serializer
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Configuration\Subject;

use JMS\Serializer\Annotation\Type;
use TechDivision\Import\Utils\DependencyInjectionKeys;
use TechDivision\Import\Serializer\Configuration\SerializerConfigurationInterface;

/**
 * The import/export adapter's serializer configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Serializer implements SerializerConfigurationInterface, \TechDivision\Import\Configuration\Subject\SerializerConfigurationInterface
{

    /**
     * The serializer's factory class name.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_SERIALIZER_FACTORY_CSV_VALUE;

    /**
     * Returns the serializer's factory unique DI identifier.
     *
     * @return string The unique DI identifier of the serializer factory
     */
    public function getId()
    {
        return $this->id;
    }
}
