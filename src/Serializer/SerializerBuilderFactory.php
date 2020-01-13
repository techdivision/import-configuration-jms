<?php

/**
 * TechDivision\Import\Configuration\Jms\Serializer\SerializerBuilderFactory
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

namespace TechDivision\Import\Configuration\Jms\Serializer;

use JMS\Serializer\SerializerBuilder;

/**
 * Factory for the serializer builder instances.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class SerializerBuilderFactory implements SerializerBuilderFactoryInterface
{

    /**
     * The serializer builder class name.
     *
     * @var string
     */
    protected $builderClassName;

    /**
     * Initializes the factory with the passed builder class name.
     *
     * @param string $builderClassName The builder class name
     */
    public function __construct($builderClassName = SerializerBuilder::class)
    {
        $this->builderClassName = $builderClassName;
    }

    /**
     * The serializer builder instance.
     *
     * @return \JMS\Serializer\SerializerBuilder The builder instance
     */
    public function createSerializerBuilder()
    {

        // initialize the reflection class instance
        $reflectionClass = new \ReflectionClass($this->builderClassName);

        // create the new instance
        return $reflectionClass->newInstance();
    }
}
