<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd\tests;

use axy\htpasswd\Crypt;
use axy\htpasswd\PasswordFile;
use axy\crypt\APR1;
use axy\crypt\BCrypt;

/**
 * coversDefaultClass axy\htpasswd\Crypt
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
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
     * covers ::hash
     */
    public function testHashSha1()
    {
        $password = 'my-password';
        $sha1 = '{SHA}7b1eEZ+UutufmaZ6xv9MelIErWE=';
        $this->assertSame($sha1, Crypt::hash($password, PasswordFile::ALG_SHA1));
    }

    /**
     * covers ::hash
     */
    public function testHashCrypt()
    {
        $password = '123456';
        $hash = Crypt::hash($password, PasswordFile::ALG_CRYPT);
        $this->assertInternalType('string', $hash);
        $pattern = '~^([a-zA-Z0-9/\.]{2})[a-zA-Z0-9/\.]{11}$~s';
        if (!preg_match($pattern, $hash, $matches)) {
            $this->fail('Crypt pattern');
        }
        $this->assertSame($hash, crypt($password, $matches[1]));
    }

    /**
     * covers ::hash
     */
    public function testHashBCrypt()
    {
        $password = 'mypassword=123456';
        $hash = Crypt::hash($password, PasswordFile::ALG_BCRYPT);
        $this->assertInternalType('string', $hash);
        $this->assertTrue((bool)preg_match('~^\$2y\$05\$[A-Za-z0-9/\.]{53}$~is', $hash));
        $this->assertTrue(BCrypt::verify($password, $hash));
    }

    /**
     * covers ::hash
     */
    public function testHashBCryptCost()
    {
        $password = 'mypassword=123456';
        $hash = Crypt::hash($password, PasswordFile::ALG_BCRYPT, ['cost' => 7]);
        $this->assertInternalType('string', $hash);
        $this->assertTrue((bool)preg_match('~^\$2y\$07\$[A-Za-z0-9/\.]{53}$~is', $hash));
        $this->assertTrue(BCrypt::verify($password, $hash));
    }

    /**
     * covers ::hash
     */
    public function testHashUndefined()
    {
        $this->assertNull(Crypt::hash('password', 'undefined'));
    }

    /**
     * covers ::verify
     * @dataProvider providerVerify
     * @param string $password
     * @param string $hash
     * @param bool $expected [optional]
     */
    public function testVerify($password, $hash, $expected = true)
    {
        $this->assertSame($expected, Crypt::verify($password, $hash));
    }

    /**
     * @return array
     */
    public function providerVerify()
    {
        return [
            'plain' => ['password', 'password'],
            'md5' => ['password', '$apr1$aGwevNmX$4WQ0UxE4TzhoaE6QkeBJJ0'],
            'sha1' => ['password', '{SHA}W6ph5Mm5Pz8GgiULbPgzG37mj9g='],
            'crypt' => ['password', 'rOVL0k/supDAY'],
            'bcrypt' => ['pass1234', '$2y$05$skTPtV45nT7GyeUIMrmUMuI8iqFPcvROoDoTI2oUXTCVaebvdeZmq'],
            'bcrypt-c4' => ['pass1234', '$2y$04$ce5G.RR1gl4/UiYqMinJo.pBM71xPFS4Q9MOSrgW1ptch0h.q6ytC'],
            'plain_as_md5' => ['$apr1$aGwevNmX$4WQ0UxE4TzhoaE6QkeBJJ0', '$apr1$aGwevNmX$4WQ0UxE4TzhoaE6QkeBJJ0'],
            'plain_as_sha1' => ['{SHA}W6ph5Mm5Pz8GgiULbPgzG37mj9g=', '{SHA}W6ph5Mm5Pz8GgiULbPgzG37mj9g='],
            'plain_as_crypt' => ['rOVL0k/supDAY', 'rOVL0k/supDAY'],
            'fail_plain' => ['password', 'other', false],
            'fail_md5' => ['password', '$apr1$aGwevNmX$4WQ0UxE4TzhoaX6QkeBJJ0', false],
            'fail_sha1' => ['password', '{SHA}W6ph5Mm5Zz8GgiULbPgzG37mj9g=', false],
            'fail_crypt' => ['password', 'rOVL0z/supDAY', false],
            'fail_bcrypt' => ['pass1234', '$2y$05$skTPtV45nT7GyeUIMrmUMuI8iqFPcvROODoTI2oUXTCVaebvdeZmq', false],
        ];
    }

    /**
     * covers ::sha1
     * @dataProvider providerSha1
     * @param string $password
     * @param string $expected
     */
    public function testSha1($password, $expected)
    {
        $this->assertSame($expected, Crypt::sha1($password));
    }

    /**
     * @return array
     */
    public function providerSha1()
    {
        return [
            ['password', '{SHA}W6ph5Mm5Pz8GgiULbPgzG37mj9g='],
            ['my-password-long-long-long', '{SHA}79Dt2/mp7D80ZQdLIOxJScmlttU='],
        ];
    }

    /**
     * covers ::cryptVerify
     * @dataProvider providerCryptVerify
     * @param string $password
     * @param string $hash
     * @param bool $expected [optional]
     */
    public function testCryptVerify($password, $hash, $expected = true)
    {
        $this->assertSame($expected, Crypt::cryptVerify($password, $hash));
    }

    /**
     * @return array
     */
    public function providerCryptVerify()
    {
        return [
            ['password', 'rOVL0k/supDAY'],
            ['password', 'l2eNr.2J4AvwE'],
            ['long-long-password', 'pviOZdeKeC.vU'],
            ['long-long', 'pviOZdeKeC.vU'],
            ['long-lon', 'pviOZdeKeC.vU'],
            ['long-lo', 'pviOZdeKeC.vU', false],
            ['password', 'l3eNr.2J4AvwE', false],
        ];
    }

    /**
     * covers ::cryptHash
     * @dataProvider providerCryptHash
     * @param string $password
     */
    public function testCryptHash($password)
    {
        $hash = Crypt::cryptHash($password);
        $this->assertInternalType('string', $hash);
        $this->assertTrue(Crypt::cryptVerify($password, $hash));
    }

    /**
     * @return array
     */
    public function providerCryptHash()
    {
        return [
            ['password'],
            ['my-password'],
            ['qwe-rty'],
        ];
    }
}
