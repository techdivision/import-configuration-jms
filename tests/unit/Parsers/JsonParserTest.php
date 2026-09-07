<?php

/**
 * TechDivision\Import\Configuration\Jms\Parsers\JsonParserTest
 *
 * PHP version 7
 *
 * @author    MET <met@techdivision.com>
 * @copyright 2026 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms\Parsers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use TechDivision\Import\Configuration\Jms\Utils\ArrayUtil;

/**
 * Test class for the JSON configuration parser implementation.
 *
 * Covers the fix that stops a single unreadable/foreign/malformed *.json file from aborting the whole configuration
 * merge (previously an \Exception was thrown unconditionally for any such file), as well as a related latent bug where
 * a validly-parsed but empty JSON file (e.g. "{}") was misclassified as an error because json_decode(...) === [] is
 * falsy in PHP.
 *
 * @author    MET <met@techdivision.com>
 * @copyright 2026 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class JsonParserTest extends TestCase
{

    /**
     * The temporary directory used to hold the fixture files for a single test.
     *
     * @var string
     */
    protected $tmpDir;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('json-parser-test-', true);
        mkdir($this->tmpDir, 0777, true);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        foreach (glob($this->tmpDir . DIRECTORY_SEPARATOR . '*') as $file) {
            unlink($file);
        }
        rmdir($this->tmpDir);
    }

    /**
     * A directory containing one valid config file and one file that is not valid JSON at all must no longer abort the
     * whole merge - the invalid file is skipped and the valid file's content is still merged in
     *
     * @return void
     */
    public function testForeignUnreadableFileNoLongerAbortsTheMerge()
    {
        // A legitimate configuration snippet
        file_put_contents(
            $this->tmpDir . DIRECTORY_SEPARATOR . 'valid-config.json',
            '{"source-dir": "var/pacemaker/import"}'
        );

        // A file that just happens to end in .json but isn't valid JSON at all (e.g. a stray file dropped by an
        // unrelated component, or one that was still being written to when this scan happened)
        file_put_contents(
            $this->tmpDir . DIRECTORY_SEPARATOR . 'shipment123_000-01.json',
            'not valid json at all'
        );

        $parser = new JsonParser(new ArrayUtil());

        $result = json_decode($parser->parse($this->tmpDir, 'etc', [$this->tmpDir]), true);

        $this->assertSame('var/pacemaker/import', $result['source-dir']);
    }

    /**
     * A validly-parsed but empty JSON object ("{}") must not be treated as an error - json_decode('{}', true) === [] is
     * falsy in PHP, which previously caused this case to be misclassified as an unreadable file
     *
     * @return void
     */
    public function testEmptyButValidJsonObjectIsNotTreatedAsAnError()
    {
        file_put_contents($this->tmpDir . DIRECTORY_SEPARATOR . 'empty-config.json', '{}');
        file_put_contents(
            $this->tmpDir . DIRECTORY_SEPARATOR . 'valid-config.json',
            '{"source-dir": "var/pacemaker/import"}'
        );

        $parser = new JsonParser(new ArrayUtil());

        $result = json_decode($parser->parse($this->tmpDir, 'etc', [$this->tmpDir]), true);

        $this->assertSame('var/pacemaker/import', $result['source-dir']);
    }

    /**
     * When a logger is injected, an unreadable/foreign file must be reported through it instead of raising an exception
     *
     * @return void
     */
    public function testUnreadableFileIsReportedToTheInjectedLogger()
    {
        file_put_contents($this->tmpDir . DIRECTORY_SEPARATOR . 'shipment123_000-01.json', 'not valid json at all');

        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->once())->method('warning')->with($this->stringContains('shipment123_000-01.json'));

        $parser = new JsonParser(new ArrayUtil(), $logger);
        $parser->parse($this->tmpDir, 'etc', [$this->tmpDir]);
    }

    /**
     * Without an injected logger, parsing must still work (falls back to error_log()) instead of requiring a logger or
     * throwing
     *
     * @return void
     */
    public function testWorksWithoutAnInjectedLogger()
    {
        file_put_contents($this->tmpDir . DIRECTORY_SEPARATOR . 'shipment123_000-01.json', 'not valid json at all');
        file_put_contents(
            $this->tmpDir . DIRECTORY_SEPARATOR . 'valid-config.json',
            '{"source-dir": "var/pacemaker/import"}'
        );

        $parser = new JsonParser(new ArrayUtil());

        $result = json_decode($parser->parse($this->tmpDir, 'etc', [$this->tmpDir]), true);

        $this->assertSame('var/pacemaker/import', $result['source-dir']);
    }
}
