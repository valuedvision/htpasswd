<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests\errors;

use axy\htpasswd\errors\FileNotSpecified;

/**
 * coversDefaultClass axy\htpasswd\errors\FileNotSpecified
 */
class FileNotSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::getFileName
     */
    public function testCreate()
    {
        $e = new FileNotSpecified();
        $this->assertSame('Htpasswd file is not specified', $e->getMessage());
    }
}
