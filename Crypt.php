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
            case PasswordFile::ALG_PLAIN:
                return $password;
        }
    }
}
