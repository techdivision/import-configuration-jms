<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Subject\FileWriter
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
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Configuration\Subject;

use JMS\Serializer\Annotation\Type;
use TechDivision\Import\Utils\DependencyInjectionKeys;
use TechDivision\Import\Configuration\Subject\FileWriterConfigurationInterface;

/**
 * The file writer's configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class FileWriter implements FileWriterConfigurationInterface
{

    /**
     * The file writer's Symfony DI name.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_SUBJECT_FILE_WRITER_OK_FILE_AWARE;

    /**
     * Initialize the instance with the file writer's Symfony DI name.
     *
     * @param string $id The Symfony DI name
     */
    public function __construct($id = DependencyInjectionKeys::IMPORT_SUBJECT_FILE_WRITER_OK_FILE_AWARE)
    {
        $this->id = $id;
    }

    /**
     * Return's the file writer's unique DI identifier.
     *
     * @return string The file writer's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }
}
