<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests;

use axy\htpasswd\Crypt;
use axy\htpasswd\PasswordFile;
use axy\crypt\APR1;

/**
 * coversDefaultClass axy\htpasswd\Crypt
 */
class CryptTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::hash
     */
    public function testHashMD5()
    {
        $hash = Crypt::hash('my-password', PasswordFile::ALG_MD5);
        $this->assertTrue(APR1::verify('my-password', $hash));
        $hash2 = Crypt::hash('default');
        $this->assertTrue(APR1::verify('default', $hash2));
    }

    /**
     * covers ::hash
     */
    public function testHashPlain()
    {
        $this->assertSame('my-password', Crypt::hash('my-password', PasswordFile::ALG_PLAIN));
    }

    /**
     * covers ::verify
     * @dataProvider providerVerify
     * @param string $password
     * @param string $hash
     * @param bool $expected
     */
    public function testVerify($password, $hash, $expected)
    {
        $this->assertSame($expected, Crypt::verify($password, $hash));
    }

    /**
     * @return array
     */
    public function providerVerify()
    {
        return [
            'plain' => ['password', 'password', true],
            'md5' => ['password', '$apr1$aGwevNmX$4WQ0UxE4TzhoaE6QkeBJJ0', true],
            'plain_as_md5' => ['$apr1$aGwevNmX$4WQ0UxE4TzhoaE6QkeBJJ0', '$apr1$aGwevNmX$4WQ0UxE4TzhoaE6QkeBJJ0', true],
            'fail_plain' => ['password', 'other', false],
            'fail_md5' => ['password', '$apr1$aGwevNmX$4WQ0UxE4TzhoaX6QkeBJJ0', false],
        ];
    }
}
