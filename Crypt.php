<?php
/**
 * @package axy\htpasswd
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */

namespace axy\htpasswd;

use axy\crypt\APR1;

/**
 * Hash/verify password
 */
class Crypt
{
    /**
     * Hash a password
     *
     * @param string $password
     * @param string $algorithm [optional]
     * @return string
     */
    public static function hash($password, $algorithm = PasswordFile::ALG_MD5)
    {
        switch ($algorithm) {
            case PasswordFile::ALG_MD5:
                return APR1::hash($password);
            case PasswordFile::ALG_SHA1:
                return self::sha1($password);
            case PasswordFile::ALG_PLAIN:
                return $password;
        }
    }

    /**
     * Checks if a password matches a hash
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verify($password, $hash)
    {
        if ($password === $hash) {
            return true;
        }
        if (APR1::verify($password, $hash)) {
            return true;
        }
        if ($hash === self::sha1($password)) {
            return true;
        }
        return false;
    }

    /**
     * Hash by SHA-1 (Apache version)
     *
     * @param string $password
     * @return string
     */
    public static function sha1($password)
    {
        return '{SHA}'.base64_encode(sha1($password, true));
    }
}
