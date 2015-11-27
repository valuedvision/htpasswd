<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests;

use axy\htpasswd\PasswordFile;

/**
 * coversDefaultClass axy\htpasswd\PasswordFile
 */
class PasswordFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::getFileName
     */
    public function testGetFileName()
    {
        $file = new PasswordFile('/tmp/htpasswd');
        $this->assertSame('/tmp/htpasswd', $file->getFileName());
    }
}
