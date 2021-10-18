<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\DateConverter
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
use TechDivision\Import\Utils\DependencyInjectionKeys;
use TechDivision\Import\Configuration\Subject\DateConverterConfigurationInterface;

/**
 * A simple date converter configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class DateConverter implements DateConverterConfigurationInterface
{

    /**
     * The date converter's class name.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_SUBJECT_DATE_CONVERTER_SIMPLE;

    /**
     * The source date format to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("source-date-format")
     */
    protected $sourceDateFormat = 'n/d/y, g:i A';

    /**
     * Returns the date converter's unique DI identifier.
     *
     * @return string The date converter's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the source date format to use.
     *
     * @return string The source date format
     */
    public function getSourceDateFormat()
    {
        return $this->sourceDateFormat;
    }
}
