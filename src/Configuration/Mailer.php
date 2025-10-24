<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Mailer
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
use TechDivision\Import\Configuration\Jms\Configuration\Mailer\Transport;
use TechDivision\Import\Configuration\MailerConfigurationInterface;

/**
 * The mailer configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Mailer implements MailerConfigurationInterface
{

    /**
     * The trait that provides parameter configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * The DI ID used to create the mailer instance.
     *
     * @var string
     * @Type("string")
     */
    protected string $id;

    /**
     * The mailer transport configuration to use.
     *
     * @var Transport
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Mailer\Transport")
     */
    protected Transport $transport;

    /**
     * Return's the DI ID used to create the mailer instance.
     *
     * @return string The DI ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the mailer transport configuration to use.
     *
     * @return Transport The transport configuration to use
     */
    public function getTransport()
    {
        return $this->transport;
    }
}
