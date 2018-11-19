<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Subject
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
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Configuration;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\PostDeserialize;
use TechDivision\Import\ConfigurationInterface;
use TechDivision\Import\Configuration\SubjectConfigurationInterface;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\FileResolver;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\ImportAdapter;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\ExportAdapter;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\FilesystemAdapter;

/**
 * The subject configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Subject implements SubjectConfigurationInterface
{

    /**
     * The trait that provides parameter handling functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * The subject's unique DI identifier.
     *
     * @var string
     * @Type("string")
     * @SerializedName("id")
     */
    protected $id;

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
     * A reference to the parent configuration instance.
     *
     * @var \TechDivision\Import\ConfigurationInterface
     */
    protected $configuration;

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
     * The filesystem adapter configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\FileResolverConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\FileResolver")
     * @SerializedName("file-resolver")
     */
    protected $fileResolver;

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
     * Queries whether or not strict mode is enabled or not, default is TRUE.
     *
     * @return boolean TRUE if strict mode is enabled, else FALSE
     */
    public function isStrictMode()
    {
        return $this->getConfiguration()->isStrictMode();
    }

    /**
     * Return's the subject's source date format to use.
     *
     * @return string The source date format
     */
    public function getSourceDateFormat()
    {
        return $this->getConfiguration()->getSourceDateFormat();
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
     * Set's the reference to the configuration instance.
     *
     * @param \TechDivision\Import\ConfigurationInterface $configuration The configuration instance
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
     * @return \TechDivision\Import\ConfigurationInterface The configuration instance
     */
    public function getConfiguration()
    {
        return $this->configuration;
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
        return $this->okFileNeeded;
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
}
