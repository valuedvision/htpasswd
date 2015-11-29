<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests\io;

use axy\htpasswd\io\Real;

/**
 * coversDefaultClass axy\htpasswd\io\Real
 */
class RealTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::load
     * covers ::save
     */
    public function testLoadSave()
    {
        $fn = __DIR__.'/../tmp/real.txt';
        $content = 'This is content of the file'.PHP_EOL;
        if (is_file($fn)) {
            unlink($fn);
        }
        $real = new Real($fn);
        $this->assertSame('', $real->load());
        $this->assertFileNotExists($fn);
        $real->save($content);
        $this->assertFileExists($fn);
        $this->assertSame($content, file_get_contents($fn));
        $this->assertSame($content, $real->load());
        $real2 = new Real($fn);
        $this->assertSame($content, $real2->load());
    }

    /**
     * covers ::load
     * covers ::save
     */
    public function testNotSpecified()
    {
        $real = new Real(null);
        $this->assertSame('', $real->load());
        $this->setExpectedException('axy\htpasswd\errors\FileNotSpecified');
        $real->save('Content');
    }

    /**
     * covers ::setFileName
     */
    public function testSetFilename()
    {
        $real = new Real(null);
        $fn = __DIR__.'/../tst/invalid';
        $real->setFileName($fn);
        $this->assertSame(file_get_contents($fn), $real->load());
    }
}
