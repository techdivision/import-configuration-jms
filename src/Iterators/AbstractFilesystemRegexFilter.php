<?php

/**
 * TechDivision\Import\Configuration\Jms\Iterators\AbstractFilesystemRegexFilter
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
 * An abstract filesystem regex filter implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
abstract class AbstractFilesystemRegexFilter extends \RecursiveRegexIterator
{

    /**
     * The regex used to filter the files.
     *
     * @var string
     */
    protected $regex;

    /**
     * Initializes the filter with the passed iterator and the regex.
     *
     * @param \RecursiveIterator $it    The iterator intance to be filtered
     * @param string             $regex The regex used to filter
     */
    public function __construct(\RecursiveIterator $it, $regex)
    {

        // set the regex
        $this->regex = $regex;

        // pass the regex and the iterator to the parent instance
        parent::__construct($it, $regex);
    }
}
