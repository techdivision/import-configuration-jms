<?php


/**
 * TechDivision\Import\Configuration\Jms\Listeners\Renderer\Debug\ConfigurationFileRenderer
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Listeners\Renderer\Debug;

use Jean85\PrettyVersions;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Expression\ExpressionEvaluator;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Visitor\Factory\JsonSerializationVisitorFactory;
use TechDivision\Import\Configuration\ConfigurationInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use TechDivision\Import\Configuration\Jms\Utils\SerializerContextKeys;

/**
 * A customer renderer that renders the configuration to a file by using the JMS serializer.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
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

        // try to load the JMS serializer
        $version = PrettyVersions::getVersion('jms/serializer');

        // query whether or not we're < than 2.0.0
        if (version_compare($version->getPrettyVersion(), '2.0.0', '<')) {
            // initialize the naming strategy
            $namingStrategy = new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy());

            // initialize the visitor because we want to set JSON options
            $visitor = new JsonSerializationVisitor($namingStrategy);
            $visitor->setOptions(JSON_PRETTY_PRINT);
        } else {
            // initialize the json visitor factory because we want to set JSON options
            $visitor = new JsonSerializationVisitorFactory();
        }

        // register the visitor in the builder instance
        $builder->setSerializationVisitor($format = 'json', $visitor);

        // create a new serialization context, because we need to pass a flag to signal that
        // this will be a debug dump and secret data, e. g. credentials has to be removed
        $serializationContext = new SerializationContext();
        $serializationContext->setAttribute(SerializerContextKeys::DEBUG_DUMP, true);

        // finally create the serializer instance and serialize configuration into a JSON string
        return $builder->build()->serialize($configuration, $format, $serializationContext);
    }
}
