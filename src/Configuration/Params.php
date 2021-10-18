<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Params
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

use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * A simple JMS based params configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 *
 * @ExclusionPolicy("none")
 */
class Params
{

    /**
     * Trait that provides CSV configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;
}
