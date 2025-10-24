<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Logger\Handler
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
use JMS\Serializer\Annotation\SerializedName;
use TechDivision\Import\Configuration\Jms\Configuration\Mailer;
use TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait;
use TechDivision\Import\Configuration\Logger\HandlerConfigurationInterface;

/**
 * The logger's handler configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Handler implements HandlerConfigurationInterface
{

    /**
     * The trait that provides parameter handling functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * The handler's DI ID to use.
     *
     * @var string
     * @Type("string")
     */
    protected $id;

    /**
     * The handler's formatter to use.
     *
     * @var \TechDivision\Import\Configuration\Logger\FormatterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Logger\Formatter")
     */
    protected $formatter;

    /**
     * The mailer logger configuration to use.
     *
     * @var Mailer
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Mailer")
     * @SerializedName("mailer")
     */
    protected Mailer $mailer;

    /**
     * Return's the handler's DI ID to use.
     *
     * @return string The DI ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the handler's formtter to use.
     *
     * @return \TechDivision\Import\Configuration\Logger\FormatterConfigurationInterface
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Return's the mailer configuration to use.
     *
     * @return Mailer The mailer configuration to use
     */
    public function getMailer()
    {
        return $this->mailer;
    }
}
