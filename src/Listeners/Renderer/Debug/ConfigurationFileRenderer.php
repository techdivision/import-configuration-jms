<?php


/**
 * TechDivision\Import\Configuration\Jms\Listeners\Renderer\Debug\ConfigurationFileRenderer
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
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Listeners\Renderer\Debug;

use JMS\Serializer\Expression\ExpressionEvaluator;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use TechDivision\Import\Configuration\ConfigurationInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * A customer renderer that renders the configuration to a file by using the JMS serializer.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConfigurationFileRenderer extends \TechDivision\Import\Listeners\Renderer\Debug\ConfigurationFileRenderer
{

    /**
     * Returns a string representation of the actual configuration instance.
     *
     * @param \TechDivision\Import\Configuration\ConfigurationInterface $configuration The configuration instance to dump
     *
     * @return string The string representation of the actual configuration instance
     */
    protected function dump(ConfigurationInterface $configuration) : string
    {

        // initialize the serializer
        $builder = SerializerBuilder::create();
        $builder->addDefaultSerializationVisitors();
        $builder->setExpressionEvaluator(new ExpressionEvaluator(new ExpressionLanguage()));

        // initialize the naming strategy
        $namingStrategy = new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy());

        // initialize the visitor because we want to set JSON options
        $visitor = new JsonSerializationVisitor($namingStrategy);
        $visitor->setOptions(JSON_PRETTY_PRINT);

        // register the visitor in the builder instance
        $builder->setSerializationVisitor($format = 'json', $visitor);

        // finally create the serializer instance and serialize configuration into a JSON string
        return $builder->build()->serialize($configuration, $format);
    }
}
