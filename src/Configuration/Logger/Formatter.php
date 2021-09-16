<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Logger\Formatter
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Configuration\Logger;

use JMS\Serializer\Annotation\Type;
use TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait;
use TechDivision\Import\Configuration\Logger\FormatterConfigurationInterface;

/**
 * The handler's formatter configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Formatter implements FormatterConfigurationInterface
{

    /**
     * The trait that provides parameter handling functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * The formatter's DI ID to use.
     *
     * @var string
     * @Type("string")
     */
    protected $id;

    /**
     * Return's the formatter's DI ID to use.
     *
     * @return string The type
     */
    public function getId()
    {
        return $this->id;
    }
}
