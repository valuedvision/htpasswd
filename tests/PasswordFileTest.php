<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests;

use axy\htpasswd\PasswordFile;
use axy\htpasswd\io\Test;

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

    /**
     * covers ::setPassword
     * covers ::save
     * covers ::isUserExists
     */
    public function testSetPassword()
    {
        $io = new Test();
        $file = new PasswordFile($io);
        $this->assertTrue($file->setPassword('one', 'one-password', PasswordFile::ALG_PLAIN));
        $this->assertTrue($file->isUserExist('one'));
        $this->assertFalse($file->isUserExist('two'));
        $this->assertTrue($file->setPassword('two', 'two-password', PasswordFile::ALG_PLAIN));
        $this->assertTrue($file->isUserExist('two'));
        $this->assertSame([''], $io->getLines());
        $file->save();
        $file2 = new PasswordFile($io);
        $this->assertTrue($file->isUserExist('one'));
        $this->assertFalse($file2->setPassword('one', 'new-password', PasswordFile::ALG_PLAIN));
        $this->assertTrue($file2->setPassword('three', 'three-password', PasswordFile::ALG_PLAIN));
        $file2->save();
        $expected = [
            'one:new-password',
            'two:two-password',
            'three:three-password',
            '',
        ];
        $this->assertSame($expected, $io->getLines());
    }

    /**
     * covers ::verify
     */
    public function testRealVerify()
    {
        $file = new PasswordFile(__DIR__.'/tst/test');
        $this->assertTrue($file->isUserExist('one'));
        $this->assertTrue($file->isUserExist('two'));
        $this->assertFalse($file->isUserExist('three'));
        $this->assertTrue($file->verify('one' ,'one-password'));
        $this->assertTrue($file->verify('two' ,'two-password'));
        $this->assertFalse($file->verify('three' ,'three-password'));
        $this->assertFalse($file->verify('one' ,'two-password'));
        $this->assertFalse($file->verify('two' ,'none'));
    }

    /**
     * covers ::load
     * @expectedException \axy\htpasswd\errors\InvalidFileFormat
     */
    public function testInvalid()
    {
        $file = new PasswordFile(__DIR__.'/tst/invalid');
        $file->isUserExist('one');
    }

    /**
     * covers ::remove
     * covers ::setFileName
     */
    public function testRemove()
    {
        $fnSource = __DIR__.'/tst/test';
        $fn = __DIR__.'/tmp/test';
        if (is_file($fn)) {
            unlink($fn);
        }
        $file = new PasswordFile($fnSource);
        $file->setFileName($fn);
        $this->assertFalse($file->remove('none'));
        $this->assertTrue($file->remove('one'));
        $this->assertFalse($file->remove('one'));
        $this->assertFalse($file->isUserExist('one'));
        $this->assertTrue($file->isUserExist('two'));
        $file->save();
        $this->assertFileExists($fn);
        $expected = 'two:$apr1$Hcy4Z1A2$OhLViOzdKWWIuF..c/90U0';
        $this->assertSame($expected, trim(file_get_contents($fn)));
    }
}
