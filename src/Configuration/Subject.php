<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Subject
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
use JMS\Serializer\Annotation\PostDeserialize;
use TechDivision\Import\Configuration\ConfigurationInterface;
use TechDivision\Import\Configuration\PluginConfigurationInterface;
use TechDivision\Import\Configuration\SubjectConfigurationInterface;
use TechDivision\Import\Configuration\ListenerAwareConfigurationInterface;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\FileResolver;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\ImportAdapter;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\ExportAdapter;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\DateConverter;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\NumberConverter;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\FilesystemAdapter;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\FileWriter;

/**
 * The subject configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Subject implements SubjectConfigurationInterface, ListenerAwareConfigurationInterface
{

    /**
     * The trait that provides parameter handling functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * Trait that provides CSV configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ListenersTrait
     */
    use ListenersTrait;

    /**
     * The subject's unique DI identifier.
     *
     * @var string
     * @Type("string")
     * @SerializedName("id")
     */
    protected $id;

    /**
     * The subject's name.
     *
     * @var string
     * @Type("string")
     * @SerializedName("name")
     */
    protected $name;

    /**
     * The array with the subject's observers.
     *
     * @var array
     * @Type("array")
     */
    protected $observers = array();

    /**
     * The array with the subject's callbacks.
     *
     * @var array
     * @Type("array<string, array>")
     */
    protected $callbacks = array();

    /**
     * The array with the subject's frontend input callbacks.
     *
     * @var array
     * @Type("array<string, array>")
     * @SerializedName("frontend-input-callbacks")
     */
    protected $frontendInputCallbacks = array();

    /**
     * The flag to signal that the subjects needs a OK file to be processed or not.
     *
     * @var boolean
     * @Type("boolean")
     * @SerializedName("ok-file-needed")
     */
    protected $okFileNeeded = false;

    /**
     *The flag to signal that the subject has to create a .imported flagfile or not.
     *
     * @var boolean
     * @Type("boolean")
     * @SerializedName("create-imported-file")
     */
    protected $createImportedFile = true;

    /**
     * The import adapter configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\ImportAdapterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\ImportAdapter")
     * @SerializedName("import-adapter")
     */
    protected $importAdapter;

    /**
     * The export adapter configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\ExportAdapterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\ExportAdapter")
     * @SerializedName("export-adapter")
     */
    protected $exportAdapter;

    /**
     * The filesystem adapter configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\FilesystemAdapterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\FilesystemAdapter")
     * @SerializedName("filesystem-adapter")
     */
    protected $filesystemAdapter;

    /**
     * The file resolver configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\FileResolverConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\FileResolver")
     * @SerializedName("file-resolver")
     */
    protected $fileResolver;

    /**
     * The file writer configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\FileWriterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\FileWriter")
     * @SerializedName("file-writer")
     */
    protected $fileWriter;

    /**
     * The number converter configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\NumberConverterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\NumberConverter")
     * @SerializedName("number-converter")
     */
    protected $numberConverter;

    /**
     * The date converter configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\DateConverterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\DateConverter")
     * @SerializedName("date-converter")
     */
    protected $dateConverter;

    /**
     * The source directory that has to be watched for new files.
     *
     * @var string
     * @Type("string")
     * @SerializedName("source-dir")
     */
    protected $sourceDir;

    /**
     * The target directory with the files that has been imported.
     *
     * @var string
     * @Type("string")
     * @SerializedName("target-dir")
     */
    protected $targetDir;

    /**
     * A reference to the parent configuration instance.
     *
     * @var \TechDivision\Import\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * The configuration of the parent plugin.
     *
     * @var \TechDivision\Import\Configuration\PluginConfigurationInterface
     */
    protected $pluginConfiguration;

    /**
     * Lifecycle callback that will be invoked after deserialization.
     *
     * @return void
     * @PostDeserialize
     */
    public function postDeserialize()
    {

        // set a default import adatper if none has been configured
        if ($this->importAdapter === null) {
            $this->importAdapter = new ImportAdapter();
        }

        // set a default export adatper if none has been configured
        if ($this->exportAdapter === null) {
            $this->exportAdapter = new ExportAdapter();
        }

        // set a default filesystem adatper if none has been configured
        if ($this->filesystemAdapter === null) {
            $this->filesystemAdapter = new FilesystemAdapter();
        }

        // set a default file resolver if none has been configured
        if ($this->fileResolver === null) {
            $this->fileResolver = new FileResolver();
        }

        // set a default file writer if none has been configured
        if ($this->fileWriter === null) {
            $this->fileWriter = new FileWriter();
        }

        // set a default number converter if none has been configured
        if ($this->numberConverter === null) {
            $this->numberConverter = new NumberConverter();
        }

        // set a default date converter if none has been configured
        if ($this->dateConverter === null) {
            $this->dateConverter = new DateConverter();
        }
    }

    /**
     * Return's the multiple field delimiter character to use, default value is comma (,).
     *
     * @return string The multiple field delimiter character
     */
    public function getMultipleFieldDelimiter()
    {
        return $this->getConfiguration()->getMultipleFieldDelimiter();
    }

    /**
     * Return's the multiple value delimiter character to use, default value is comma (|).
     *
     * @return string The multiple value delimiter character
     */
    public function getMultipleValueDelimiter()
    {
        return $this->getConfiguration()->getMultipleValueDelimiter();
    }

    /**
     * Return's the delimiter character to use, default value is comma (,).
     *
     * @return string The delimiter character
     */
    public function getDelimiter()
    {
        return $this->getConfiguration()->getDelimiter();
    }

    /**
     * The enclosure character to use, default value is double quotation (").
     *
     * @return string The enclosure character
     */
    public function getEnclosure()
    {
        return $this->getConfiguration()->getEnclosure();
    }

    /**
     * The escape character to use, default value is backslash (\).
     *
     * @return string The escape character
     */
    public function getEscape()
    {
        return $this->getConfiguration()->getEscape();
    }

    /**
     * The file encoding of the CSV source file, default value is UTF-8.
     *
     * @return string The charset used by the CSV source file
     */
    public function getFromCharset()
    {
        return $this->getConfiguration()->getFromCharset();
    }

    /**
     * The file encoding of the CSV targetfile, default value is UTF-8.
     *
     * @return string The charset used by the CSV target file
     */
    public function getToCharset()
    {
        return $this->getConfiguration()->getToCharset();
    }

    /**
     * The file mode of the CSV target file, either one of write or append, default is write.
     *
     * @return string The file mode of the CSV target file
     */
    public function getFileMode()
    {
        return $this->getConfiguration()->getFileMode();
    }

    /**
     * Queries whether or not strict mode is enabled or not, default is True.
     *
     * Backward compatibility
     * debug = true strict = true -> isStrict == FALSE
     * debug = true strict = false -> isStrict == FALSE
     * debug = false strict = true -> isStrict == TRUE
     * debug = false strict = false -> isStrict == FALSE
     *
     * @return boolean TRUE if strict mode is enabled, else FALSE
     */
    public function isStrictMode()
    {
        if ($this->isDebugMode()) {
            return false;
        }
        return $this->getConfiguration()->isStrictMode();
    }

    /**
     * Return's the subject's source date format to use.
     *
     * @return string The source date format
     */
    public function getSourceDateFormat()
    {
        return $this->getDateConverter()->getSourceDateFormat();
    }

    /**
     * Return's the source directory that has to be watched for new files.
     *
     * @return string The source directory
     */
    public function getSourceDir()
    {
        return $this->sourceDir ? $this->sourceDir : $this->getConfiguration()->getSourceDir();
    }

    /**
     * Return's the target directory with the files that has been imported.
     *
     * @return string The target directory
     */
    public function getTargetDir()
    {
        return $this->targetDir ? $this->targetDir : $this->getConfiguration()->getTargetDir();
    }

    /**
     * Queries whether or not debug mode is enabled or not, default is TRUE.
     *
     * @return boolean TRUE if debug mode is enabled, else FALSE
     */
    public function isDebugMode()
    {
        return $this->getConfiguration()->isDebugMode();
    }

    /**
     * Return's the subject's unique DI identifier.
     *
     * @return string The subject's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the subject's name or the ID, if the name is NOT set.
     *
     * @return string The subject's name
     * @see \TechDivision\Import\Configuration\SubjectConfigurationInterface::getId()
     */
    public function getName()
    {
        return $this->name ? $this->name : $this->getId();
    }

    /**
     * Set's the reference to the configuration instance.
     *
     * @param \TechDivision\Import\Configuration\ConfigurationInterface $configuration The configuration instance
     *
     * @return void
     */
    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Return's the reference to the configuration instance.
     *
     * @return \TechDivision\Import\Configuration\ConfigurationInterface The configuration instance
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Set's the reference to the parent plugin configuration instance.
     *
     * @param \TechDivision\Import\Configuration\PluginConfigurationInterface $pluginConfiguration The parent plugin configuration instance
     *
     * @return void
     */
    public function setPluginConfiguration(PluginConfigurationInterface $pluginConfiguration)
    {
        $this->pluginConfiguration = $pluginConfiguration;
    }

    /**
     * Return's the reference to the parent plugin configuration instance.
     *
     * @return \TechDivision\Import\Configuration\PluginConfigurationInterface The parent plugin configuration instance
     */
    public function getPluginConfiguration()
    {
        return $this->pluginConfiguration;
    }

    /**
     * Return's the prefix for the import files.
     *
     * @return string The prefix
     */
    public function getPrefix()
    {
        return $this->getFileResolver()->getPrefix();
    }

    /**
     * Return's the array with the subject's observers.
     *
     * @return array The subject's observers
     */
    public function getObservers()
    {
        return $this->observers;
    }

    /**
     * Return's the array with the subject's callbacks.
     *
     * @return array The subject's callbacks
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * Return's the array with the subject's frontend input callbacks.
     *
     * @return array The subject's frontend input callbacks
     */
    public function getFrontendInputCallbacks()
    {
        return $this->frontendInputCallbacks;
    }

    /**
     * Set's the flag to signal that the an OK file is needed for the subject
     * to be processed.
     *
     * @param boolean $okFileNeeded TRUE if the subject needs an OK file, else FALSE
     *
     * @return void
     */
    public function setOkFileNeeded($okFileNeeded)
    {
        $this->okFileNeeded = $okFileNeeded;
    }

    /**
     * Queries whether or not that the subject needs an OK file to be processed.
     *
     * @return boolean TRUE if the subject needs an OK file, else FALSE
     */
    public function isOkFileNeeded()
    {
        // an OK file is ONLY needed, in case NO specific filename
        // has been passed as second command line argument
        if ($this->okFileNeeded) {
            // query whether or not a specific file has been
            // specified, if yes, we do NOT need an .OK file
            if ($this->getConfiguration() instanceof ConfigurationInterface && !empty($this->getConfiguration()->getFilename())) {
                return false;
            } else {
                return true;
            }
        }

        // return FALSE in case we generally
        // have not configured a .OK file
        return false;
    }

    /**
     * Queries whether or not the subject should create a .imported flagfile
     *
     * @return boolean TRUE if subject has to create the .imported flagfile, else FALSE
     */
    public function isCreatingImportedFile()
    {
        return $this->createImportedFile;
    }

    /**
     * Return's the import adapter configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\ImportAdapterConfigurationInterface The import adapter configuration instance
     */
    public function getImportAdapter()
    {
        return $this->importAdapter;
    }

    /**
     * Return's the export adapter configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\ExportAdapterConfigurationInterface The export adapter configuration instance
     */
    public function getExportAdapter()
    {
        return $this->exportAdapter;
    }

    /**
     * Return's the filesystem adapter configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\FilesystemAdapterConfigurationInterface The filesystem adapter configuration instance
     */
    public function getFilesystemAdapter()
    {
        return $this->filesystemAdapter;
    }

    /**
     * Return's the file resolver configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\FileResolverConfigurationInterface The file resolver configuration instance
     */
    public function getFileResolver()
    {
        return $this->fileResolver;
    }

    /**
     * Return's the file writer configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\FileWriterConfigurationInterface The file writer configuration instance
     */
    public function getFileWriter()
    {
        return $this->fileWriter;
    }

    /**
     * Return's the number converter configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\NumberConverterConfigurationInterface The number converter configuration instance
     */
    public function getNumberConverter()
    {
        return $this->numberConverter;
    }

    /**
     * Return's the date converter configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\DateConverterConfigurationInterface The date converter configuration instance
     */
    public function getDateConverter()
    {
        return $this->dateConverter;
    }

    /**
     * The array with the subject's custom header mappings.
     *
     * @return array The custom header mappings
     */
    public function getHeaderMappings()
    {
        return $this->getConfiguration()->getHeaderMappings();
    }

    /**
     * The array with the subject's custom image types.
     *
     * @return array The custom image types
     */
    public function getImageTypes()
    {
        return $this->getConfiguration()->getImageTypes();
    }

    /**
     * Return's the execution context configuration for the actualy plugin configuration.
     *
     * @return \TechDivision\Import\Configuration\ExecutionContextInterface The execution context to use
     */
    public function getExecutionContext()
    {
        return $this->getPluginConfiguration()->getExecutionContext();
    }

    /**
     * Load the default values from the configuration.
     *
     * @return array The array with the default values
     */
    public function getDefaultValues()
    {
        return $this->getConfiguration()->getDefaultValues();
    }

    /**
     * Return's the full opration name, which consists of the Magento edition, the entity type code and the operation name.
     *
     * @param string $separator The separator used to seperate the elements
     *
     * @return string The full operation name
     */
    public function getFullOperationName($separator = '/')
    {
        return $this->getPluginConfiguration()->getFullOperationName();
    }
}
