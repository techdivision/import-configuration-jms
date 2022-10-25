<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\CsvTrait
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

/**
 * A trait implementation that provides CSV configuration handling.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
trait CsvTrait
{

    /**
     * The subject's delimiter character for CSV files.
     *
     * @var string
     * @Type("string")
     * @SerializedName("delimiter")
     */
    protected $delimiter;

    /**
     * The subject's enclosure character for CSV files.
     *
     * @var string
     * @Type("string")
     * @SerializedName("enclosure")
     */
    protected $enclosure;

    /**
     * The subject's escape character for CSV files.
     *
     * @var string
     * @Type("string")
     * @SerializedName("escape")
     */
    protected $escape;

    /**
     * The subject's source charset for the CSV file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("from-charset")
     */
    protected $fromCharset;

    /**
     * The subject's target charset for a CSV file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("to-charset")
     */
    protected $toCharset;

    /**
     * The subject's file mode for a CSV target file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("file-mode")
     */
    protected $fileMode;

    /**
     * Return's the delimiter character to use, default value is comma (,) from configuration.json
     *
     * @return string The delimiter character
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * The enclosure character to use, default value is double quotation (") from configuration.json
     *
     * @return string The enclosure character
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * The escape character to use, default value is Unicode NULL (\u0000) from configuration.json
     *
     * @return string The escape character
     */
    public function getEscape()
    {
        return $this->escape;
    }

    /**
     * The file encoding of the CSV source file, default value is UTF-8.
     *
     * @return string The charset used by the CSV source file
     */
    public function getFromCharset()
    {
        return $this->fromCharset;
    }

    /**
     * The file encoding of the CSV targetfile, default value is UTF-8.
     *
     * @return string The charset used by the CSV target file
     */
    public function getToCharset()
    {
        return $this->toCharset;
    }

    /**
     * The file mode of the CSV target file, either one of write or append, default is write.
     *
     * @return string The file mode of the CSV target file
     */
    public function getFileMode()
    {
        return $this->fileMode;
    }

    /**
     * Set the delimiter character to use
     *
     * @param string $delimiter Delimiter one character
     * @return void
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Set the enclosure character to use
     *
     * @param string $enclosure Encloser one character
     * @return void
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * Set the escape character to use
     *
     * @param string $escape Escaper one character
     * @return void
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;
    }

    /**
     * Set the file encoding of the CSV source file
     *
     * @param string $fromCharset Charset like UTF-8
     * @return void
     */
    public function setFromCharset($fromCharset)
    {
        $this->fromCharset = $fromCharset;
    }

    /**
     * Set the file encoding of the CSV targetfile
     *
     * @param string $toCharset Charset like UTF-8
     * @return void
     */
    public function setToCharset($toCharset)
    {
        $this->toCharset = $toCharset;
    }

    /**
     * Set the file mode of the CSV target file.
     * @param string $fileMode Filemode like write ("w") or append ("a"), default is write.
     * @return void
     */
    public function setFileMode($fileMode)
    {
        $this->fileMode = $fileMode;
    }
}
