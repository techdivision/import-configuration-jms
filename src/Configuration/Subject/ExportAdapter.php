<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Subject\ExportAdapter
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

namespace TechDivision\Import\Configuration\Jms\Configuration\Subject;

use JMS\Serializer\Annotation\Type;
use TechDivision\Import\Utils\DependencyInjectionKeys;
use TechDivision\Import\Configuration\Jms\Configuration\CsvTrait;
use TechDivision\Import\Configuration\Subject\ExportAdapterConfigurationInterface;

/**
 * The export adapter's configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ExportAdapter implements ExportAdapterConfigurationInterface
{

    /**
     * Trait that provides CSV configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\CsvTrait
     */
    use CsvTrait;

    /**
     * The export adapter's unique DI identifier.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_ADAPTER_EXPORT_CSV_FACTORY;

    /**
     * Return's the export adapter's unique DI identifier
     *
     * @return string The export adapter's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }
}
