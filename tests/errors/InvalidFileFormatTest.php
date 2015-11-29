<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests\errors;

use axy\htpasswd\errors\InvalidFileFormat;

/**
 * coversDefaultClass axy\htpasswd\errors\InvalidFileFormat
 */
class InvalidFileFormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::getFileName
     */
    public function testCreate()
    {
        $e = new InvalidFileFormat('/tmp/htpasswd');
        $this->assertSame('/tmp/htpasswd', $e->getFileName());
        $this->assertSame('Htpasswd file /tmp/htpasswd has invalid format', $e->getMessage());
    }
}
