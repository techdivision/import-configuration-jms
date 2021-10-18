<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Subject\FilesystemAdapter
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
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\PostDeserialize;
use TechDivision\Import\Utils\BunchKeys;
use TechDivision\Import\Utils\DependencyInjectionKeys;
use TechDivision\Import\Configuration\Subject\FileResolverConfigurationInterface;

/**
 * The file resolver's configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class FileResolver implements FileResolverConfigurationInterface
{

    /**
     * The file resolver's Symfony DI name.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_SUBJECT_FILE_RESOLVER_OK_FILE_AWARE;

    /**
     * The prefix/meta sequence of the import files.
     *
     * @var string
     * @Type("string")
     */
    protected $prefix = FileResolverConfigurationInterface::DEFAULT_PREFIX;

    /**
     * The filename/meta sequence of the import files.
     *
     * @var string
     * @Type("string")
     */
    protected $filename = FileResolverConfigurationInterface::DEFAULT_FILENAME;

    /**
     * The counter/meta sequence of the import files.
     *
     * @var string
     * @Type("string")
     */
    protected $counter = FileResolverConfigurationInterface::DEFAULT_COUNTER;

    /**
     * The file suffix for import files.
     *
     * @var string
     * @Type("string")
     */
    protected $suffix = FileResolverConfigurationInterface::DEFAULT_SUFFIX;

    /**
     * The file suffix for OK file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("ok-file-suffix")
     */
    protected $okFileSuffix = FileResolverConfigurationInterface::DEFAULT_OK_FILE_SUFFIX;

    /**
     * The separator char for the elements of the file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("element-separator")
     */
    protected $elementSeparator = FileResolverConfigurationInterface::DEFAULT_ELEMENT_SEPARATOR;

    /**
     * The elements to create the regex pattern that is necessary decide a subject handles a file or not.
     *
     * @var string
     * @Type("array")
     * @SerializedName("pattern-elements")
     */
    protected $patternElements = null;

    /**
     * Initialize the instance with the file resolver's Symfony DI name.
     *
     * @param string $id The Symfony DI name
     */
    public function __construct($id = DependencyInjectionKeys::IMPORT_SUBJECT_FILE_RESOLVER_OK_FILE_AWARE)
    {
        // set the Symfony DI of the file resolver
        $this->id = $id;
        // initialize the pattern elements
        $this->patternElements = BunchKeys::getAllKeys();
    }

    /**
     * Return's the file resolver's unique DI identifier.
     *
     * @return string The file resolvers's unique DI identifier
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Set's the prefix/meta sequence for the import files.
     *
     * @param string $prefix The prefix
     *
     * @return void
     */
    public function setPrefix(string $prefix) : void
    {
        $this->prefix = $prefix;
    }

    /**
     * Return's the prefix/meta sequence for the import files.
     *
     * @return string The prefix
     */
    public function getPrefix() : string
    {
        return $this->prefix;
    }

    /**
     * Query's whether or not a custom prefix has been configured for the
     * file resolver.
     *
     * @param string $defaultPrefix The default prefix to match against
     *
     * @return boolean TRUE if the file resolver has a custom prefix, else FALSE
     */
    public function hasPrefix($defaultPrefix = FileResolverConfigurationInterface::DEFAULT_PREFIX) : bool
    {
        return strcmp($this->getPrefix(), $defaultPrefix) <> 0;
    }

    /**
     * Return's the filename/meta sequence of the import files.
     *
     * @return string The suffix
     */
    public function getFilename() : string
    {
        return $this->filename;
    }

    /**
     * Query's whether or not a custom filename has been configured for the
     * file resolver.
     *
     * @param string $defaultFilename The default filename to match against
     *
     * @return boolean TRUE if the file resolver has a custom filename, else FALSE
     */
    public function hasFilename($defaultFilename = FileResolverConfigurationInterface::DEFAULT_FILENAME) : bool
    {
        return strcmp($this->getFilename(), $defaultFilename) <> 0;
    }

    /**
     * Return's the counter/meta sequence of the import files.
     *
     * @return string The suffix
     */
    public function getCounter() : string
    {
        return $this->counter;
    }

    /**
     * Query's whether or not a custom counter has been configured for the
     * file resolver.
     *
     * @param string $defaultCounter The default counter to match against
     *
     * @return boolean TRUE if the file resolver has a custom counter, else FALSE
     */
    public function hasCounter($defaultCounter = FileResolverConfigurationInterface::DEFAULT_COUNTER) : bool
    {
        return strcmp($this->getCounter(), $defaultCounter) <> 0;
    }

    /**
     * Return's the suffix for the import files.
     *
     * @return string The suffix
     */
    public function getSuffix() : string
    {
        return $this->suffix;
    }

    /**
     * Return's the suffix for the OK file.
     *
     * @return string The OK file suffix
     */
    public function getOkFileSuffix() : string
    {
        return $this->okFileSuffix;
    }

    /**
     * Return's the delement separator char.
     *
     *  @return string The element separator char
     */
    public function getElementSeparator() : string
    {
        return $this->elementSeparator;
    }

    /**
     * Set's the the elements the filenames consists of.
     *
     * @param array $patternElements The array with the filename elements
     *
     * @return void
     */
    public function setPatternElements(array $patternElements) : void
    {
        $this->patternElements = $patternElements;
    }

    /**
     * Return's the elements the filenames consists of.
     *
     * @return array The array with the filename elements
     */
    public function getPatternElements() : array
    {
        return $this->patternElements;
    }

    /**
     * Lifecycle callback that will be invoked after deserialization.
     *
     * @return void
     * @PostDeserialize
     */
    public function postDeserialize() : void
    {

        // set the default pattern elements
        if ($this->patternElements === null) {
            $this->patternElements = BunchKeys::getAllKeys();
        }
    }
}
