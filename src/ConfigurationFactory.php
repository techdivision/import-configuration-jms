<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationFactory
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms;

use Composer\Autoload\ClassLoader;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TechDivision\Import\Configuration\ConfigurationInterface;
use TechDivision\Import\Configuration\Jms\Configuration\Params;
use TechDivision\Import\Configuration\ConfigurationFactoryInterface;
use Jean85\PrettyVersions;
use TechDivision\Import\Utils\DependencyInjectionKeys;

/**
 * The configuration factory implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConfigurationFactory implements ConfigurationFactoryInterface
{

    /**
     * The container instance.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * The configuration parser factory instance used to create a parser that
     * parsers and merges the configuration from different directories.
     *
     * @var \TechDivision\Import\Configuration\Jms\ConfigurationParserFactoryInterface
     */
    protected $configurationParserFactory;

    /**
     * The configuration class name to use.
     *
     * @var string
     */
    protected $configurationClassName;

    /**
     * The serializer builder instance.
     *
     * @var \JMS\Serializer\SerializerBuilder
     */
    protected $serializerBuilder;

    /**
     * Initializes the instance with the configuration parser factory instance.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                  $container                  The container instance
     * @param \TechDivision\Import\Configuration\Jms\ConfigurationParserFactoryInterface $configurationParserFactory The configuration parser factory instance
     * @param \JMS\Serializer\SerializerBuilder                                          $serializerBuilder          The serializer builder instance to use
     * @param string                                                                     $configurationClassName     The configuration class name to use
     */
    public function __construct(
        ContainerInterface $container,
        ConfigurationParserFactoryInterface $configurationParserFactory,
        SerializerBuilder $serializerBuilder,
        $configurationClassName = Configuration::class
    ) {

        // initialize the member variables with the passed instances
        $this->container = $container;
        $this->serializerBuilder = $serializerBuilder;
        $this->configurationClassName = $configurationClassName;
        $this->configurationParserFactory = $configurationParserFactory;

        // load the actual vendor directory and entity type code
        $vendorDir = $this->getVendorDir();

        // the path of the JMS serializer directory, relative to the vendor directory
        $jmsDir = DIRECTORY_SEPARATOR . 'jms' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'src';

        // try to find the path to the JMS Serializer annotations
        if (!file_exists($annotationDir = $vendorDir . DIRECTORY_SEPARATOR . $jmsDir)) {
            // stop processing, if the JMS annotations can't be found
            throw new \Exception(
                sprintf(
                    'The jms/serializer libarary can not be found in one of "%s"',
                    implode(', ', $vendorDir)
                )
            );
        }

        // try to load the JMS serializer
        $version = PrettyVersions::getVersion('jms/serializer');

        // query whether or not we're > than 1.14.1
        if (version_compare($version->getPrettyVersion(), '2.0.0', '<')) {
            // register the autoloader for the JMS serializer annotations
            \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
                'JMS\Serializer\Annotation',
                $annotationDir
            );
        } else {
            // initialize the composer class loader
            $classLoader = new ClassLoader();
            $classLoader->addPsr4('JMS\\Serializer\\', array($annotationDir));
            // register the class loader to load annotations
            \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($classLoader, 'loadClass'));
        }
    }

    /**
     * Return's the DI container instance.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface The DI container instance
     */
    protected function getContainer() : ContainerInterface
    {
        return $this->container;
    }

    /**
     * Return's the configuration parser factory instance.
     *
     * @return \TechDivision\Import\Configuration\Jms\ConfigurationParserFactoryInterface The configuration parser factory instance
     */
    protected function getConfigurationParserFactory() : ConfigurationParserFactoryInterface
    {
        return $this->configurationParserFactory;
    }

    /**
     * Return's the configuration class name to use.
     *
     * @return string The configuration class name
     */
    protected function getConfigurationClassName() : string
    {
        return $this->configurationClassName;
    }

    /**
     * Return's the serializer builder instance to use.
     *
     * @return \JMS\Serializer\SerializerBuilder The serializer builder instance
     */
    protected function getSerializerBuilder() : SerializerBuilder
    {
        return $this->serializerBuilder;
    }

    /**
     * Returns the configuration parser for the passed format.
     *
     * @param string $format The format of the configuration file to return the parser for (either one of json, yaml or xml)
     *
     * @return \TechDivision\Import\Configuration\Jms\ConfigurationParserInterface The configuration parser instance
     */
    protected function getConfigurationParser($format) : ConfigurationParserInterface
    {
        return $this->getConfigurationParserFactory()->factory($format);
    }

    /**
     * Factory implementation to create a new initialized configuration instance.
     *
     * @param string $filename   The configuration filename
     * @param string $format     The format of the configuration file, either one of json, yaml or xml
     * @param string $params     A serialized string with additional params that'll be passed to the configuration
     * @param string $paramsFile A filename that contains serialized data with additional params that'll be passed to the configuration
     *
     * @return \TechDivision\Import\Configuration\Jms\Configuration The configuration instance
     * @throws \Exception Is thrown, if the specified configuration file doesn't exist
     */
    public function factory($filename, $format = 'json', $params = null, $paramsFile = null) : ConfigurationInterface
    {

        // try to load the JSON data
        if ($data = file_get_contents($filename)) {
            // initialize the JMS serializer, load and return the configuration
            return $this->factoryFromString($data, $format, $params, $paramsFile);
        }

        // throw an exception if the data can not be loaded from the passed file
        throw new \Exception(sprintf('Can\'t load configuration file %s', $filename));
    }

    /**
     * Factory implementation to create a new initialized configuration instance from a file
     * with configurations that'll be parsed and merged.
     *
     * @param string $installationDir         The assumed Magento installation directory
     * @param string $defaultConfigurationDir The default configuration directory
     * @param array  $directories             An array with diretories to parse and merge
     * @param string $format                  The format of the configuration file, either one of json, yaml or xml
     * @param string $params                  A serialized string with additional params that'll be passed to the configuration
     * @param string $paramsFile              A filename that contains serialized data with additional params that'll be passed to the configuration
     *
     * @return \TechDivision\Import\Configuration\Jms\Configuration The configuration instance
     */
    public function factoryFromDirectories($installationDir, $defaultConfigurationDir = 'etc', array $directories = array(), $format = 'json', $params = null, $paramsFile = null) : ConfigurationInterface
    {
        return $this->factoryFromString($this->getConfigurationParser($format)->parse($installationDir, $defaultConfigurationDir, $directories), $format, $params, $paramsFile);
    }

    /**
     * @param array  $directories An array with diretories to parse and merge
     * @param string $format      The format of the configuration file, either one of json, yaml or xml
     * @return array
     */
    public function getConfigurationFiles(array $directories = array(), $format = 'json')
    {
        return $this->getConfigurationParser($format)->getConfigurationFiles($directories);
    }

    /**
     * Factory implementation to create a new initialized configuration instance.
     *
     * @param string $data       The configuration data
     * @param string $format     The format of the configuration data, either one of json, yaml or xml
     * @param string $params     A serialized string with additional params that'll be passed to the configuration
     * @param string $paramsFile A filename that contains serialized data with additional params that'll be passed to the configuration
     *
     * @return \TechDivision\Import\Configuration\ConfigurationInterface The configuration instance
     */
    public function factoryFromString($data, $format = 'json', $params = null, $paramsFile = null) : ConfigurationInterface
    {

        // initialize the JMS serializer, load and return the configuration
        $data = $this->toArray($data, $this->getConfigurationClassName(), $format);

        // merge the params, if specified with the --params option
        if ($params) {
            $this->replaceParams(
                $data,
                $this->toArray(
                    $params,
                    Params::class,
                    $format
                )
            );
        }

        // merge the param loaded from the file, if specified with the --params-file option
        if ($paramsFile && is_file($paramsFile)) {
            $this->replaceParams(
                $data,
                $this->toArray(
                    file_get_contents($paramsFile),
                    Params::class,
                    pathinfo($paramsFile, PATHINFO_EXTENSION)
                )
            );
        }

        // finally, create and return the configuration from the merge data
        return $this->getSerializerBuilder()->build()->fromArray($data, $this->getConfigurationClassName());
    }

    /**
     * Creates and returns an array/object tree from the passed array.
     *
     * @param array  $data The data to create the object tree from
     * @param string $type The object type to create
     *
     * @return mixed The array/object tree from the passed array
     */
    protected function fromArray(array $data, $type)
    {
        return $this->getSerializerBuilder()->build()->fromArray($data, $type);
    }

    /**
     * Deserializes the data, converts it into an array and returns it.
     *
     * @param string $data   The data to convert
     * @param string $type   The object type for the deserialization
     * @param string $format The data format, either one of JSON, XML or YAML
     *
     * @return array The data as array
     */
    protected function toArray($data, $type, $format) : array
    {

        // load the serializer builde
        $serializer = $this->getSerializerBuilder()->build();

        // deserialize the data, convert it into an array and return it
        return $serializer->toArray($serializer->deserialize($data, $type, $format));
    }

    /**
     * Merge the additional params in the passed configuration data and replaces existing ones with the same name.
     *
     * @param array $data   The array with configuration data
     * @param array $params The array with additional params to merge
     *
     * @return void
     */
    protected function replaceParams(&$data, $params) : void
    {
        $data = array_replace_recursive($data, $params);
    }

    /**
     * Return's the absolute path to the actual vendor directory.
     *
     * @return string The absolute path to the actual vendor directory
     * @throws \Exception Is thrown, if none of the possible vendor directories can be found
     */
    protected function getVendorDir()
    {
        return $this->getContainer()->getParameter(DependencyInjectionKeys::CONFIGURATION_VENDOR_DIR);
    }
}
