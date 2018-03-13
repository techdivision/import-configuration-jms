<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Listener
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
use TechDivision\Import\Configuration\ListenerConfigurationInterface;

/**
 * The listener configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Listener implements ListenerConfigurationInterface
{

    /**
     * The listeners's unique DI identifier.
     *
     * @var string
     * @Type("string")
     * @SerializedName("id")
     */
    protected $id;

    /**
     * The event name the listener has to be registered.
     *
     * @var string
     * @Type("string")
     */
    protected $event;

    /**
     * Return's the listener's unique DI identifier
     *
     * @return string The listener's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the event name the listener has to be registered.
     *
     * @return string The event name
     */
    public function getEvent()
    {
        return $this->event;
    }
}
