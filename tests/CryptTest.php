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
    }
}
