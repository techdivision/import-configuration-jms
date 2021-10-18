<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\NumberConverter
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
use TechDivision\Import\Configuration\Subject\NumberConverterConfigurationInterface;

/**
 * A simple number configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class NumberConverter implements NumberConverterConfigurationInterface
{

    /**
     * The number converter's class name.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_SUBJECT_NUMBER_CONVERTER_SIMPLE;

    /**
     * The locale to use.
     *
     * @var string
     * @Type("string")
     */
    protected $locale = 'en_US';

    /**
     * Return's the number converter's unique DI identifier.
     *
     * @return string The number converter's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the locale to use.
     *
     * @return string The locale
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
