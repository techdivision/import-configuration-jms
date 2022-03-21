<?php

/**
 * TechDivision\Import\Configuration\Jms\Iterators\FilenameFilter
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Iterators;

/**
 * A filter implementation that filters filenames based on the passed regex.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class FilenameFilter extends AbstractFilesystemRegexFilter
{

    /**
     * Filter files against the regex.
     *
     * @return bool
     * @see \RecursiveRegexIterator::accept()
     */
    public function accept(): bool
    {
        return (!$this->isFile() || preg_match($this->regex, $this->getFilename()));
    }
}
