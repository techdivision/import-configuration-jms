<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\ListenersTrait
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Configuration;

use JMS\Serializer\Annotation\Type;

/**
 * A trait implementation that provides listener handling.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
trait ListenersTrait
{

    /**
     * ArrayCollection with the listeners.
     *
     * @var array
     * @Type("array")
     */
    protected $listeners = array();

    /**
     * Return's the array with the configured listeners.
     *
     * @return array The array with the listeners
     */
    public function getListeners()
    {
        return $this->listeners;
    }
}
