<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests\io;

use axy\htpasswd\io\Test;

/**
 * coversDefaultClass axy\htpasswd\io\Test
 */
class TestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::load
     * covers ::save
     */
    public function testLoadSave()
    {
        $test = new Test();
        $this->assertSame('', $test->load());
        $test->save('One'.PHP_EOL.'Two'.PHP_EOL.'Three');
        $this->assertSame('One'.PHP_EOL.'Two'.PHP_EOL.'Three', $test->load());
        $this->assertSame(['One', 'Two', 'Three'], $test->getLines());
        $test->save('X');
        $this->assertSame('X', $test->load());
        $this->assertSame(['X'], $test->getLines());
    }

    /**
     * covers ::load
     */
    public function testConstructString()
    {
        $test = new Test('One'.PHP_EOL.'Two');
        $this->assertSame('One'.PHP_EOL.'Two', $test->load());
        $this->assertSame(['One', 'Two'], $test->getLines());
    }


    /**
     * covers ::load
     */
    public function testConstructArray()
    {
        $test = new Test(['One', 'Two']);
        $this->assertSame('One'.PHP_EOL.'Two', $test->load());
        $this->assertSame(['One', 'Two'], $test->getLines());
    }

    /**
     * covers ::setFileName
     */
    public function testSetFilename()
    {
        $test = new Test('test');
        $test->setFileName('some/file');
        $this->assertSame('test', $test->load());
    }
}
